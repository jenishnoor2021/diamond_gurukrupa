<?php

namespace App\Models;

use App\Models\Dimond;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dimonds()
    {
        return $this->belongsTo(Dimond::class);
    }
}
