<?php

namespace App\Models;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkerAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
