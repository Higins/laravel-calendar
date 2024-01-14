<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class EventRequest extends FormRequest
{
    private $errorArray = [];
    public function rules()
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['date', 'after:start_date'],
        ];
    }

    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            $start_date = Carbon::parse($this->input('start_date'));
            $end_date = Carbon::parse($this->input('end_date'));

            $this->validateNoOverlap($validator, $start_date, $end_date);
            $this->validateWithinOpeningHours($validator, $start_date);
        });
    }

    protected function validateNoOverlap($validator, $start_date, $end_date)
    {
        $overlappingEvent = Event::where(function ($query) use ($start_date, $end_date) {
            return $query->whereBetween('start_date', [$start_date, $end_date])
                ->orWhereBetween('end_date', [$start_date, $end_date]);
        })->get();

        if ($overlappingEvent->count() > 0) {
            $this->errorArray = ['error' => 'Overlapping event!'];
        }
    }

    protected function validateWithinOpeningHours($validator, $start_date)
    {
        $currentDay = $start_date->format('D');

        if (array_key_exists($currentDay, Event::$storeSchedule)) {
            foreach (Event::$storeSchedule[$currentDay] as $opening_start => $opening_end) {
                $opening_start = Carbon::createFromFormat('Y-m-d H:i', $start_date->format('Y-m-d') . ' ' . $opening_start);
                $opening_end = Carbon::createFromFormat('Y-m-d H:i', $start_date->format('Y-m-d') . ' ' . $opening_end);

                if ($start_date->lt($opening_start) || $start_date->gte($opening_end)) {
                    $this->errorArray = ['error' => 'It falls outside of opening hours!'];
                }
            }
        }
    }
    public function getError()
    {
        return $this->errorArray;
    }

}
