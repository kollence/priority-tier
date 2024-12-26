<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'data_import_id',
        'row_number',
        'column_name',
        'invalid_value',
        'validation_message'
    ];

}
