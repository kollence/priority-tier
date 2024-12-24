<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataImport extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'filename', 'status'];

    public function logs()
    {
        return $this->hasMany(ImportLog::class);
    }

    public function audits()
    {
        return $this->hasMany(ImportAudit::class);
    }
}
