<?php

declare(strict_types=1);

namespace App\Enums;

enum StockStatus: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
    case OutOfStock = 'out_of_stock';

    public static function getAllValues(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }
}
