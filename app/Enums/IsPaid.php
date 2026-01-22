<?php

namespace App\Enums;


use Spatie\Enum\Enum;

/**
 * @method static self ISPAID()
 * @method static self FREE()
 */
class IsPaid extends Enum
{
    protected static function values(): array
    {
        return [
            'FREE'      => 0,
            'ISPAID'    => 1,
        ];
    }

    public function title(): string|null
    {
        return match ($this->value) {
            0       => 'Free',
            1       => 'Paid',
            default => null,
        };
    }
    public function message(): string|null
    {
        return match ($this->value) {
            0 => 'info',
            1 => 'success',
            default => null,
        };
    }
}
