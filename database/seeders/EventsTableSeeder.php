<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        Event::create([
            'start_date' => '2024-03-08T08:00:00',
            'end_date' => '2024-03-08T10:00:00',
            'recurrence' => 'none',
            'day_of_week' => 'Fri',
            'name_of_client' => 'első',
        ]);

        Event::create([
            'start_date' => '2024-01-01T10:00:00',
            'end_date' => '2025-01-01T10:00:00',
            'recurrence' => 'every week even',
            'day_of_week' => 'Mon',
            'name_of_client' => 'második',
        ]);

        Event::create([
            'start_date' => '2024-01-03T12:00:00',
            'end_date' => '2025-01-03T16:00:00',
            'recurrence' => 'every week odd',
            'day_of_week' => 'Wed',
            'name_of_client' => 'harmadik',
        ]);

        Event::create([
            'start_date' => '2024-01-05T10:00:00',
            'end_date' => '2025-01-05T16:00:00',
            'recurrence' => 'every week',
            'day_of_week' => 'Fri',
            'name_of_client' => 'negyedik',
        ]);

        Event::create([
            'start_date' => '2024-06-06T16:00:00',
            'end_date' => '2024-11-30T20:00:00',
            'recurrence' => 'every week',
            'day_of_week' => 'Thu',
            'name_of_client' => 'ötödik',
        ]);

    }
}
