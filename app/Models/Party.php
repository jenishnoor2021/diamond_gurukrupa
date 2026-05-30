<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dimonds()
    {
        return $this->hasMany(Dimond::class, 'parties_id');
    }
}
