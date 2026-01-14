<?php

namespace App\Models;

use App\Models\Dimond;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Daily extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dimonds()
    {
        return $this->belongsTo(Dimond::class);
    }
}
