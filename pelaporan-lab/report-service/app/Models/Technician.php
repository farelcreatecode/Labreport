<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kontak',
        'spesialisasi',
        'status',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
