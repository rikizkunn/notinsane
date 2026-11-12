<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = ['periode', 'tanggal_dibuat', 'id_pembuat'];

    protected function casts(): array
    {
        return ['tanggal_dibuat' => 'date'];
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pembuat');
    }
}
