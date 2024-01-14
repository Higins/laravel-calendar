<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'recurrence', 'name_of_client', 'day_of_week'];

    public static array $storeSchedule = [
        'Mon' => ['07:00' => '20:00'],
        'Tue' => ['07:00' => '20:00'],
        'Wed' => ['07:00' => '20:00'],
        'Thu' => ['07:00' => '20:00'],
        'Fri' => ['07:00' => '20:00'],
        'Sat' => ['07:00' => '20:00'],
        'Sun' => ['07:00' => '20:00'],
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Event $model) {
            $start_date = Carbon::parse($model->start_date);
            $model->day_of_week = $start_date->format('D');
        });
    }
    public static function initDayNumber($day)
    {
        return array_flip(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'])[$day];
    }
}
