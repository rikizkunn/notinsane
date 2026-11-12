<?php

namespace App\Enums;

enum Role: string
{
    case Administrator = 'administrator';
    case Operator = 'operator';
    case Supervisor = 'supervisor';
    case Manajemen = 'manajemen';

    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::Operator => 'Operator / Engineer',
            self::Supervisor => 'Supervisor',
            self::Manajemen => 'Manajemen',
        };
    }
}
