<?php

namespace App\Enum;

enum WeekScrapStatusEnum: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
}
