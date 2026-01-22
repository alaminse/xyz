<?php

namespace App\Enums;


use Spatie\Enum\Enum;

/**
 * @method static self PENDING()
 * @method static self ACTIVE()
 * @method static self INACTIVE()
 * @method static self DELETED()
 * @method static self BANNED()
 * @method static self SEEN()
 * @method static self FREETRIAL()
 * @method static self EXPIRED()
 */
class Status extends Enum
{
    protected static function values(): array
    {
        return [
            'PENDING'       => 0,
            'ACTIVE'        => 1,
            'INACTIVE'      => 2,
            'DELETED'       => 3,
            'BANNED'        => 4,
            'SEEN'          => 5,
            'FREETRIAL'     => 6,
            'EXPIRED'       => 7,
        ];
    }

    public function title(): string|null
    {
        return match ($this->value) {
            0 => 'Pending',
            1 => 'Active',
            2 => 'Inactive',
            3 => 'Deleted',
            4 => 'Banned',
            5 => 'Seen',
            6 => 'FreeTrial',
            7 => 'Expired',
            default => null,
        };
    }
    public function message(): string|null
    {
        return match ($this->value) {
            0 => 'info',
            1 => 'success',
            2 => 'warning',
            3 => 'danger',
            4 => 'secondary',
            5 => 'info',
            6 => 'primary',
            7 => 'danger',
            default => null,
        };
    }
}
