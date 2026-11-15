<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lab',
        'lokasi',
        'kepala_lab',
    ];

    /**
     * Complaints submitted for this lab.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'lab_id');
    }

    /**
     * Facilities located in this lab.
     */
    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class, 'lab_id');
    }
}
