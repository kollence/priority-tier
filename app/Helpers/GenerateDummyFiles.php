<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GenerateDummyFiles
{
    /**
     * ■ Dummy file for the orders import is in the attachment. Create 2 more different import types, with different headers and file sizes. One of those import types should accept two files that are completely different and import them into two different database tables
     */
    public static function generateFiles()
    {
        $config = Config::get('import_types');

        // Base directory for dummy files
        $outputDir = __DIR__ . '/dummy_files/';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
        /**
         * Generate dummy data based on headers_to_db configuration.
         */
        function generateDummyData(array $fields, int $rowCount): array
        {
            $dummyRows = [];
            for ($i = 0; $i < $rowCount; $i++) {
            $row = [];
            foreach ($fields as $field => $details) {
                switch ($details['type']) {
                case 'date':
                    $row[] = date('Y-m-d', strtotime("+$i days"));
                    break;
                case 'string':
                    if ($field === 'channel') {
                    $row[] = $i % 2 === 0 ? 'PT' : 'Amazon';
                    } elseif ($field === 'sku') {
                    $row[] = 'SKU' . str_pad(rand(1, 5), 3, '0', STR_PAD_LEFT);
                    } else {
                    $row[] = ucfirst($field) . " $i";
                    }
                    break;
                case 'email':
                    $row[] = strtolower($field) . "$i@example.com";
                    break;
                case 'integer':
                    $row[] = rand(1, 100);
                    break;
                case 'double':
                    $row[] = number_format(rand(1, 100) + rand(0, 99) / 100, 2);
                    break;
                default:
                    $row[] = 'N/A';
                }
            }
            $dummyRows[] = $row;
            }
            return $dummyRows;
        }

        /**
         * Create a CSV file with the given headers and data.
         */
        function createCsvFile(array $headers, array $data, string $fileName)
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Add headers
            $sheet->fromArray(array_merge([$headers], $data), null, 'A1');

            $writer = new Csv($spreadsheet);
            $writer->save($fileName);
        }

        /**
         * Create an XLSX file with the given headers and data.
         */
        function createXlsxFile(array $headers, array $data, string $fileName)
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Add headers
            $sheet->fromArray(array_merge([$headers], $data), null, 'A1');

            $writer = new Xlsx($spreadsheet);
            $writer->save($fileName);
        }

        // Generate dummy files for each configuration section
        foreach ($config as $section => $details) {
            if (!isset($details['files'])) {
                continue; // Skip if no files key exists
            }

            foreach ($details['files'] as $fileKey => $fileDetails) {
                if (!isset($fileDetails['headers_to_db'])) {
                    continue; // Skip if no headers_to_db exists
                }

                $headers = array_keys($fileDetails['headers_to_db']);
                $dummyData = generateDummyData($fileDetails['headers_to_db'], 5); // Generate 5 rows of dummy data

                // File names
                $csvFileName = $outputDir . "{$section}_{$fileKey}.csv";
                $xlsxFileName = $outputDir . "{$section}_{$fileKey}.xlsx";

                // Generate CSV
                createCsvFile($headers, $dummyData, $csvFileName);

                // Generate XLSX
                createXlsxFile($headers, $dummyData, $xlsxFileName);
            }
        }

        echo "Dummy files generated in $outputDir\n";
    }
}
