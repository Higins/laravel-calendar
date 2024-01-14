<?php

namespace App\Services;

use App\Models\Event;

class EventsService
{
    public function getEvents()
    {
        $events = Event::all();
        $calendarEvents = [];

        foreach ($events as $event) {
            $baseEvent = [
                'title' => $event->name_of_client,
                'allDay' => false,
            ];

            $this->addRecurrenceData($calendarEvents, $baseEvent, $event);
        }

        return $calendarEvents;
    }

    protected function addRecurrenceData(&$calendarEvents, $baseEvent, $event)
    {
        switch ($event->recurrence) {
            case 'every week odd':
                $this->addExtraProperty($calendarEvents, $baseEvent, $event, 'red');
                break;
            case 'every week even':
                $this->addExtraProperty($calendarEvents, $baseEvent, $event, 'purple');
                break;
            case 'every week':
                $this->addExtraProperty($calendarEvents, $baseEvent, $event, 'green');
                break;
            case 'every day':
                $this->addExtraProperty($calendarEvents, $baseEvent, $event, 'orange');
                break;
            default:
                $calendarEvents[] = array_merge($baseEvent, [
                    'color' => 'brown',
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                ]);
        }
    }

    protected function addExtraProperty(&$calendarEvents, $baseEvent, $event, $color)
    {
        $calendarEvents[] = array_merge($baseEvent, [
            'groupId' => $event->recurrence,
            'color' => $color,
            'startRecur' => $event->start_date,
            'endRecur' => $event->end_date,
            'daysOfWeek' => [Event::initDayNumber($event->day_of_week)],
        ]);
    }


}
