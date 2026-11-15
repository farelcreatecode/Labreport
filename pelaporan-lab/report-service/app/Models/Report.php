<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_laporan',
        'nama_pelapor',
        'kontak_pelapor',
        'laboratory_id',
        'facility_id',
        'nomor_pc',
        'deskripsi_masalah',
        'status',
        'technician_id',
        'tanggapan',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class)->orderBy('created_at', 'desc');
    }

    public static function generateKode(): string
    {
        $prefix = '#LR-' . date('Ym') . '-';
        $latest = static::where('kode_laporan', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if (! $latest) {
            return $prefix . '0001';
        }

        $lastSeq = (int) substr($latest->kode_laporan, -4);
        return $prefix . str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);
    }
}
