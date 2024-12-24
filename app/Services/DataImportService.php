<?php
namespace App\Services;

use App\Models\Import;
use App\Models\ImportLog;
use App\Models\ImportAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataImportService
{
    public function processRow($importId, $rowNumber, $data, $config)
    {
        $rules = $this->buildValidationRules($config['headers_to_db']);

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {

            foreach ($validator->failed() as $column => $rules) {
                ImportLog::create([
                    'data_import_id' => $importId,
                    'row_number' => $rowNumber,
                    'column_name' => $column,
                    'invalid_value' => $data[$column] ?? null,
                    'validation_message' => $validator->errors()->first($column)
                ]);
            }
            return false;
        }

        return $this->updateOrCreateRecord($importId, $rowNumber, $data, $config);
    }

    private function buildValidationRules($headerConfig)
    {
        $rules = []; 
        foreach ($headerConfig as $column => $config) { 
            $columnRules = []; 
            if (!empty($config['type'])) { 
                if ($config['type'] === 'double') {
                    $columnRules[] = 'numeric';
                } else {
                    $columnRules[] = $config['type'];
                }
            } 
            foreach ($config['validation'] as $key => $rule) { 
                if (is_array($rule)) { 
                    if ($key === 'in') { 
                        $columnRules[] = "in:" . implode(',', $rule); 
                    } elseif ($key === 'exists') { 
                        $columnRules[] = "exists:{$rule['table']},{$rule['column']}"; 
                    } 
                } else { 
                    $columnRules[] = $rule; 
                } 
            } 
            $rules[$column] = implode('|', $columnRules); 
        } 
        return $rules;
    }

    private function updateOrCreateRecord($importId, $rowNumber, $data, $config)
    {
        $searchKeys = array_intersect_key($data, array_flip($config['update_or_create']));

        DB::beginTransaction();
        try {
            $record = DB::table($config['type'])->where($searchKeys)->first();
            $isUpdate = $record !== null;

            if ($isUpdate) {
                foreach ($data as $column => $value) {
                    if ($record->$column !== $value) {
                        ImportAudit::create([
                            'data_import_id' => $importId,
                            'row_number' => $rowNumber,
                            'column_name' => $column,
                            'old_value' => $record->$column,
                            'new_value' => $value
                        ]);
                    }
                }
                DB::table($config['type'])->where($searchKeys)->update($data);
            } else {
                DB::table($config['type'])->insert($data);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            
            ImportLog::create([
                'data_import_id' => $importId,
                'row_number' => $rowNumber,
                'column_name' => '*',
                'invalid_value' => json_encode($data),
                'validation_message' => preg_replace('/\(.*$/', '', $e->getMessage()) // Shorten the error message
            ]);
            return false;
        }
    }
}