<?php

namespace App\Enums;

enum TargetType: string
{
    case MIN = 'min';
    case HOUR = 'h';
    case METER = 'm';
    case SECOND = 's';
    case KILOGRAM = 'kg';
    case TIMES = 'mal';
}
