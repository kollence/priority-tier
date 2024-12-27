<?php

namespace App\Jobs;

use App\Mail\ImportErrorMail;
use App\Models\DataImport;
use App\Services\DataImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessDataImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $importId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($importId)
    {
        $this->importId = $importId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $import = DataImport::find($this->importId);
        if (!$import) {
            return;
        }

        $config = config("import_types.{$import->type}");
        if (!$config) {
            $import->update(['status' => 'failed']);
            return;
        }

        try {
            $spreadsheet = IOFactory::load(storage_path("app/private/{$import->filepath}"));
            $rows = $spreadsheet->getActiveSheet()->toArray();
            $headers = array_shift($rows);

            $importService = new DataImportService();
            foreach ($config['files'] as $fileKey => $fileConfig) {
                $headersToDb = array_merge(['type' => $import->type], $fileConfig);
                foreach ($rows as $index => $row) {
                    $data = array_combine($headers, $row);
                    $importService->processRow($import->id, $index + 2, $data, $headersToDb);
                }
            }
            if($import->logs->count() > 0) {
                $importLogs = $import->logs->toArray();
                // ■ If an error occurs during import. Send email notification using Laravel Event and Listener.
                // this file didn't want to accept auth()->user()->email or Auth::user()->email
                Mail::to('admin@mail.com')->send(new ImportErrorMail($importLogs));
            }
            $import->update(['status' => 'completed']);
        } catch (\Exception $e) {
            $import->update(['status' => 'failed']);
        }
    }
}