<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataImport extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'type', 'filename', 'status', 'filepath'];

    public function logs()
    {
        return $this->hasMany(ImportLog::class);
    }

    public function audits()
    {
        return $this->hasMany(ImportAudit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
