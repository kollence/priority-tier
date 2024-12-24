<?php 

namespace App\Exports;

use App\Models\DataImport;

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
}