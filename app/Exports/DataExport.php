<?php 

namespace App\Exports;

use App\Models\DataImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataExport
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        return $this->data;
    }
    
    public function headings(): array
    {
        return array_keys((array)$this->data->first());
    }

    public static function downloadExcel($data, $filename) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        
        $data = array_map(function ($item) {
            return (array)$item;
        }, $data);
        $headers = array_keys($data[0]);
        $column = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($column, 1, $header);
            $column++;
        }
        
        // Add data
        $row = 2;
        foreach ($data as $rowData) {
            $column = 1;
            foreach ($rowData as $value) {
                $sheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}