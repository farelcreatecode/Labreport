<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lab_id',
        'category_id',
        'technician_id',
        'nomor_pc',
        'deskripsi_masalah',
        'foto_bukti',
        'status',
        'tanggapan',
    ];

    /**
     * Appends accessors to array and JSON representations.
     */
    protected $appends = [
        'status_label',
        'report_number',
    ];

    /**
     * User who submitted the complaint.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lab where the issue occurred.
     */
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    /**
     * Category of the issue / facility.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Technician assigned to resolve the complaint.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Audit trail history for status changes.
     */
    public function histories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ComplaintHistory::class, 'complaint_id')->latest('created_at');
    }

    /**
     * Accessor for status label in Indonesian:
     * pending -> Menunggu, diproses -> Diproses, selesai -> Selesai, ditolak -> Ditolak.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    'pending' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'ditolak' => 'Ditolak',
                    default => ucfirst($this->status),
                };
            }
        );
    }

    /**
     * Accessor for formatted report number (#LR-0001).
     */
    protected function reportNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => '#LR-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT)
        );
    }

    /**
     * Accessor for public photo URL.
     */
    protected function fotoBuktiUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto_bukti ? Storage::url($this->foto_bukti) : null
        );
    }
}
