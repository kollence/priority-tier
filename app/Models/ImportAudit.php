<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportAudit extends Model
{
    protected $fillable = [
        'data_import_id',
        'row_number',
        'column_name',
        'old_value',
        'new_value'
    ];
}
