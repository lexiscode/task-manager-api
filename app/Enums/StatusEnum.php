<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumToArray;

enum StatusEnum: string
{
    use EnumToArray;
    case PENDING = 'Pending';
    case IN_PROGRESS = 'In Progress';
    case COMPLETED = 'Completed';
}
