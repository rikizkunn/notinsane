<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataProduksi extends Model
{
    /** @use HasFactory<\Database\Factories\DataProduksiFactory> */
    use HasFactory;

    protected $table = 'data_produksi';

    protected $fillable = ['tanggal', 'area', 'mesin_peralatan', 'keterangan', 'created_by'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checksheets(): HasMany
    {
        return $this->hasMany(Checksheet::class);
    }
}
