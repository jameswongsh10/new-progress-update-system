<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Facades\Calendar;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;


class FullCalendarController extends Controller
{
    public function admincalendar(Request $request) {
//        if($request->ajax()) {
//            $data = CalendarEvent::whereDate('start_date', '>=', $request->start)
//                ->whereDate('end_date', '<=', $request->end)
//                ->get(['id', 'title', 'start_date', 'end_date']);
//            return response()->json($data);
//        }
//        return view('admincalendar');
        $scheduleEvents= [];
        $data = CalendarEvent::all();
        if($data->count()) {
            foreach($data as $key => $value) {
                $scheduleEvents[] = Calendar::event(
                    $value->title,
                    true,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date),
                );
            }
        }
        $calendar = Calendar::addEvents($scheduleEvents);
        return view('admincalendar', compact('calendar'));
    }

}
