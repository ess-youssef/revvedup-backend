<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EventsController extends Controller
{
    public function list(Request $request) {
        $month = (int) $request->query("month");
        $year = (int) $request->query("year");

        if (!$month) {
            $month = date('M');
        }
        if (!$year) {
            $month = date('Y');
        }

        if ($month <= 0 || $month > 12 || $year <= 2000) {
            abort(422, "Invalid date");
        }

        $events = Event::where("start_date", ">=", $year . "-" . $month . "-01")->where("start_date", "<=", $year . "-" . $month . "-31")->get();

        return $events;
    }

    public function register(Request $request) {
        $eventData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'location' => 'required|max:255',
        ]);

        $startDate = new Carbon($eventData["start_date"]);
        $endDate = new Carbon($eventData["end_date"]);

        if ($startDate > $endDate) {
            abort(422, "End date should be greater or equal than start date");
        }

        $eventData["start_date"] = $startDate->format("Y-m-d");
        $eventData["end_date"] = $endDate->format("Y-m-d");

        $event = Event::create($eventData);
        return response()->json($event, 201);
    }

    public function show(Request $request, Event $event) {
        if ($request->bearerToken()) {
            $user = Auth::guard("sanctum")->user();
            if ($user) {
                Auth::setUser($user);
            }
        }
        $event->loadCount("attendance");
        return $event;
    }

    public function deleteEvent(Event $event)
    {
        $user = auth()->user();
        if ($user->role != "ADMIN") {
            abort(403, "Forbidden");
        }
        $event->delete();
        return ["message" => "Event deleted sucessfully"];
    }

    public function editEvent(Request $request, Event $event)
    {
        $user = auth()->user();

        if ($user->role != "ADMIN") {
            abort(403, "Forbidden");
        }

        $eventData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'location' => 'required|max:255',
        ]);

        $startDate = new Carbon($eventData["start_date"]);
        $endDate = new Carbon($eventData["end_date"]);

        if ($startDate > $endDate) {
            abort(422, "End date should be greater or equal than start date");
        }

        $eventData["start_date"] = $startDate->format("Y-m-d");
        $eventData["end_date"] = $endDate->format("Y-m-d");

        $event->update($eventData);

        return $event;
    }

    public function toggleAttendance(Event $event) {
        $event->attendance()->toggle(auth()->user()->id);
        $event->loadCount("attendance");
        return $event;
    }
}
