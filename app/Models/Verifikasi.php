<?php

namespace App\Models;

use App\Enums\StatusPersetujuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verifikasi extends Model
{
    protected $table = 'verifikasi';

    protected $fillable = [
        'checksheet_id',
        'supervisor_id',
        'tanggal_verifikasi',
        'status_persetujuan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_verifikasi' => 'date',
            'status_persetujuan' => StatusPersetujuan::class,
        ];
    }

    public function checksheet(): BelongsTo
    {
        return $this->belongsTo(Checksheet::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
