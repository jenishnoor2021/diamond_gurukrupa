<?php

namespace App\Models;

use App\Models\Dimond;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Process extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'issue_weight' => 'float',
        'return_weight' => 'float',
        'price' => 'float',
    ];

    public function dimonds()
    {
        return $this->belongsTo(Dimond::class);
    }
}
