<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Services\EventsService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


class EventController extends Controller
{
    protected $eventsServiceData;

    public function __construct(EventsService $eventsService)
    {
        $this->eventsServiceData = $eventsService;
    }
    public function index(): View
    {
        return view('welcome', ['eventTypes' => \App\Enums\EventType::cases(), 'events' => Event::all()]);
    }
    public function store(EventRequest $request): JsonResponse
    {
        if (!empty($request->getError())) {
            return response()->json($request->getError());
        }

        Event::create($request->all());

        return response()->json(['success' => 'Event created!']);
    }

    public function events(): JsonResponse
    {
        return response()->json($this->eventsServiceData->getEvents());
    }
}
