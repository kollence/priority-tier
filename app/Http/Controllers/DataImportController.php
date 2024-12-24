<?php

namespace App\Http\Controllers;

use App\Models\DataImport;
use App\Services\DataImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataImportController extends Controller
{
    public function index()
    {
        $importTypes = Config::get('import_types');

        return view('data-import.index', compact('importTypes'));
    }

    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'import_type' => 'required|string',
            'file.*' => 'required|file|mimes:xlsx,csv'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            foreach ($request->file('file') as $file) {
                // Store the file in the 'uploads' directory
                $filePath = $file->store('uploads');
                $typeAndFile = explode('-', $request->import_type);
                $type = $typeAndFile[0];
                $fileType = $typeAndFile[1];
                $import = DataImport::create([
                    'type' => $type,
                    'filename' => $fileType,
                    'filepath' => $filePath,
                    'status' => 'processing'
                ]);

                $spreadsheet = IOFactory::load($file->getPathname());
                $rows = $spreadsheet->getActiveSheet()->toArray();
                // dd($rows);
                $headers = array_shift($rows);
                // dd($headers);

                $importService = new DataImportService();
                $config = config("import_types.{$type}");
                // dd($config);
                // Loop through each file in the config
                foreach ($config['files'] as $fileKey => $fileConfig) {
                    $headersToDb = array_merge(['type' => $type], $fileConfig);
                    // dd($headersToDb);
                    foreach ($rows as $index => $row) {
                        // Combine headers with data in the row
                        $data = array_combine($headers, $row);

                        // Pass the correct `headers_to_db` config to the processRow method
                        $importService->processRow($import->id, $index + 2, $data, $headersToDb);
                    }
                }
                // dd($fo);
                $import->update(['status' => 'completed']);
            }

            return redirect()->route('data-import.index')->with('success', 'File uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->route('data-import.index')->with('error', $e->getMessage());
        }
    }

    public function show($type, $file)
    {
        $importType = config("import_types.$type");
        $headers = $importType['files'][$file]['headers_to_db'];

        $data = DB::table($type)->paginate(10);

        return view('data-import.show', compact('type', 'importType', 'headers', 'data', 'file'));
    }

    public function export($type, $file, Request $request)
    {
        $query = DB::table($type);
        $importType = config("import_types.$type");
        $searchParamsArray = $importType['files'][$file]['headers_to_db'];

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
            $q->where('sku', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('cost', 'like', "%{$search}%")
              ->orWhere('price', 'like', "%{$search}%")
              ->orWhere('stock', 'like', "%{$search}%");
            });
        }

        $data = $query->get();
        return Excel::download(new DataExport($data), "$type.xlsx");
    }

    public function audits($type, $id)
    {
        $audits = ImportAudit::where('table', $type)
            ->where('record_id', $id)
            ->get();
        return response()->json($audits);
    }

    public function imports()
    {
        $imports = Import::with('user')->paginate(10);
        return view('imports.index', compact('imports'));
    }

    public function logs($id)
    {
        $logs = ImportLog::where('import_id', $id)->get();
        return response()->json($logs);
    }
}
