<?php

namespace App\Models;

use App\Events\ImportError;
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

    protected static function booted() 
    { 
        static::created(function ($importLog) { 
            event(new ImportError($importLog)); 
        }); 
    }
}
