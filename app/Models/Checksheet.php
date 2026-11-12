<?php

namespace App\Models;

use App\Enums\ChecksheetStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Checksheet extends Model
{
    /** @use HasFactory<\Database\Factories\ChecksheetFactory> */
    use HasFactory;

    protected $fillable = [
        'data_produksi_id',
        'user_id',
        'tanggal_pemeriksaan',
        'hasil_pemeriksaan',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pemeriksaan' => 'date',
            'status' => ChecksheetStatus::class,
        ];
    }

    public function dataProduksi(): BelongsTo
    {
        return $this->belongsTo(DataProduksi::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifikasi(): HasOne
    {
        return $this->hasOne(Verifikasi::class);
    }
}
