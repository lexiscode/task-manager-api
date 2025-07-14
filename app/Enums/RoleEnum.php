<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumToArray;

enum RoleEnum: string
{
    use EnumToArray;
    case ADMIN = 'admin';
    case MEMBER = 'member';
}
