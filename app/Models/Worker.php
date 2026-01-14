<?php

namespace App\Models;

use App\Models\WorkerBarcode;
use App\Models\WorkerAttendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Worker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function barcodes()
    {
        return $this->belongsTo(WorkerBarcode::class);
    }

    public function attendances()
    {
        return $this->hasMany(WorkerAttendance::class);
    }
}
