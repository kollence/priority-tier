<?php

namespace App\Services;

use App\Models\Import;
use App\Models\ImportLog;
use App\Models\ImportAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataImportService
{
    /**
    *    ● The validation structure shown in config does not have to be identical.
    *    ● During import, if any column does not pass validation, the current row is skipped and is
    *    not inserted into the database.
    *    ● When a row is skipped, it is necessary to keep logs in a separate table about the following
    *    things:
    *    ○ In which import exactly did the problem occur.
    *    ○ Exactly on which row the error occurred.
    *    ○ Which column caused the error and what value it had at that moment.
    *    ○ Corresponding validation message.
     */
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
            // return false;
        }

        return $this->updateOrCreateRecord($importId, $rowNumber, $data, $config);
    }
    /**
     * ■ The type parameter specifies the data type to which the value should be converted before writing
     * to the database.
     * ■ The validation parameter specifies which rules should be applied to the column value that comes
     * in the import.
     * ● Possible rules:
     * ○ required - The column needs to have none empty value.
     * ○ unique - The column value under validation cannot exist in a defined table and
     * column.
     * ○ exist - The column value under validation must exist in a defined table and column.
     * ○ in - The column value must be one of defined values.
     */
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
    /**  The update_or_create parameter: if a combination of incoming column values already exists in
    * the database, an update should be performed instead of creating a new record.
    * ● When the row is updated during the import, each change needs to be audited.
    * ● It is necessary to know:
    * ○ Exactly in which import the change happened
    * ○ Exactly which row has changed
    * ○ Which column
    * ○ Old and new column values
    */
    private function updateOrCreateRecord($importId, $rowNumber, $data, $config)
    {
        $searchKeys = array_intersect_key($data, array_flip($config['update_or_create']));

        DB::beginTransaction();
        try {
            $record = DB::table($config['type'])->where($searchKeys)->first();
            $isUpdate = $record !== null;

            if ($isUpdate) {
                $updatedFields = [];
                foreach ($data as $column => $value) {
                    if (($record->$column === null && $value !== null) ||
                        ($record->$column !== null && !((string)$record->$column === (string)$value))) {
                        $updatedFields[$column] = $value;
                        ImportAudit::create([
                            'data_import_id' => $importId,
                            'row_number' => $rowNumber,
                            'column_name' => $column,
                            'old_value' => $record->$column,
                            'new_value' => $value
                        ]);
                    }
                }
                if (!empty($updatedFields)) {
                    DB::table($config['type'])->where($searchKeys)->update($updatedFields);
                }
            } else {
                DB::table($config['type'])->insert($data);
            }

            DB::commit();
            // return true;
        } catch (\Exception $e) {
            DB::rollBack();
            
            ImportLog::create([
                'data_import_id' => $importId,
                'row_number' => $rowNumber,
                'column_name' => '*',
                'invalid_value' => json_encode($data),
                'validation_message' => preg_replace('/\(.*$/', '', $e->getMessage()) // Shorten the error message
            ]);
            // return false;
        }
    }
}
