<?php

namespace App\Enums;

enum ChecksheetStatus: string
{
    case Draft = 'draft';
    case Dikirim = 'dikirim';
    case Disetujui = 'disetujui';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Dikirim => 'Menunggu verifikasi',
            self::Disetujui => 'Disetujui',
            self::Ditolak => 'Ditolak',
        };
    }

    // Bootstrap badge color for this status
    public function badge(): string
    {
        return match ($this) {
            self::Draft => 'secondary',
            self::Dikirim => 'warning',
            self::Disetujui => 'success',
            self::Ditolak => 'danger',
        };
    }
}
