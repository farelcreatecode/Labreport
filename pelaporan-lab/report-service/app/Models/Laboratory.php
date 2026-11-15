<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lab',
        'lokasi',
        'kapasitas',
        'deskripsi',
    ];

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
