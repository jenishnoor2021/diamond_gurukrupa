<?php

namespace App\Models;

use App\Models\Party;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFileAttribute($photo)
    {
        return $this->uploads . $photo;
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
