<?php

namespace App\Enums;

enum EventType: string
{
    case none = 'none';
    case every_day = 'every day';
    case every_week = 'every week';
    case every_week_odd = 'every week odd';
    case every_week_even = 'every week even';
}
