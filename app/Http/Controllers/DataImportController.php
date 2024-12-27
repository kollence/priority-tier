<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Jobs\ProcessDataImport;
use App\Models\DataImport;
use App\Models\ImportAudit;
use App\Models\ImportLog;
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

    /**
     *   ■ User then selects a xlsx or csv file, and clicks on import
     *   ■ The backend should then check if the user has permissions to import this import type. Check if the
     *   file extensions are correct. Check if the required headers are present in each file. If any of these
     *   are false, then redirect back to the import form and inform the user about what the issue is.
     *   ■ If the import type contains multiple files, at least one is required.
     */
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
            $typeAndFile = explode('-', $request->import_type);
            $type = $typeAndFile[0];
            $fileType = $typeAndFile[1];

            // Check user permissions
            if (!auth()->user()->can("import-$type")) {
                return redirect()->route('data-import.index')
                    ->with('error', "You don't have permission to import $type data.");
            }

            // Get import configuration
            $config = config("import_types.{$type}");
            if (!$config) {
                return response()->json(['errors' => 'Invalid import type.'], 422);
            }

            foreach ($request->file('file') as $file) {
                // Validate file extension
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['xlsx', 'csv'])) {
                    return response()->json(['errors' => 'Invalid file format. Only XLSX and CSV files are allowed.'], 422);
                }

                // Load spreadsheet and validate headers
                $spreadsheet = IOFactory::load($file->getPathname());
                $rows = $spreadsheet->getActiveSheet()->toArray();
                $headers = array_shift($rows);
                
                // Check required headers
                $requiredHeaders = $config['files'][$fileType]['headers_to_db'];
                $missingHeaders = array_diff(array_keys($requiredHeaders), $headers);
                
                if (!empty($missingHeaders)) {
                    return response()->json(['errors' => 'Missing required headers: ' . implode(', ', $missingHeaders)], 422);
                }

                // Store the file and create import record
                $filePath = $file->store('uploads');
                $import = DataImport::create([
                    'user_id' => auth()->id(),
                    'type' => $type,
                    'filename' => $fileType,
                    'filepath' => $filePath,
                    'status' => 'processing'
                ]);
                /**
                 * ■ The import itself should be executed in the background as a background process via queues and jobs.
                 */
                // Dispatch the job
                ProcessDataImport::dispatch($import->id);
            }
            return response()->json(['success' => 'File started upload!']);
        } catch (\Exception $e) {
            $import->update(['status' => 'failed']);
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function show($type, $file, Request $request)
    {
        $importType = config("import_types.$type");
        $headers = $importType['files'][$file]['headers_to_db'];
        $searchParamsArray = $importType['files'][$file]['headers_to_db'];
        $query = DB::table($type);

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchParamsArray, $searchTerm) {
                foreach (array_keys($searchParamsArray) as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        }
        $data = $query->paginate(10);
        return view('data-import.show', compact('type', 'importType', 'headers', 'data', 'file'));
    }

    public function export($type, $file)
    {
        $data = DB::table($type)->get()->toArray();
        return DataExport::downloadExcel($data, "$type.xlsx");
    }

    public function audits($type, $id)
    {
        $audits = ImportAudit::where('table', $type)
            ->where('record_id', $id)
            ->get();
        return response()->json($audits);
    }

    public function imports(Request $request)
    {
        $query = DataImport::where('user_id', auth()->user()->id);
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereDate('created_at', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('type', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('filename', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('status', 'LIKE', "%{$searchTerm}%");
            });
        }
        $imports = $query->paginate(10);
        
        
        return view('imports.index', compact('imports'));
    }

    public function logs($id)
    {
        $logs = ImportLog::where('data_import_id', $id)->get();
        return response()->json($logs);
    }
}
