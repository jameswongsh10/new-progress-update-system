<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Facades\Calendar;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;


class FullCalendarController extends Controller
{
    public function usercalendar(Request $request, $id) {
//        if($request->ajax()) {
//            $data = CalendarEvent::whereDate('start_date', '>=', $request->start)
//                ->whereDate('end_date', '<=', $request->end)
//                ->get(['id', 'title', 'start_date', 'end_date']);
//            return response()->json($data);
//        }
//        return view('admincalendar');
        $scheduleEvents= [];
        $user = User::find($id);
        $data = Task::all();
        setcookie("id", $id, time() + (86400 * 30), "/");

        if($data->count()) {
            foreach($data as $key => $value) {
                if($value->user_id == $user->id) {
                    $scheduleEvents[] = Calendar::event(
                        $value->task_title,
                        true,
                        new \DateTime($value->start_date),
                        new \DateTime(date('Y-m-d', strtotime($value->end_date . ' +1 days'))),

                    );
                }
            }
        }
        $calendar = Calendar::addEvents($scheduleEvents);
        $calendar->setOptions([
            'weekNumbers' => 'local',
            'weekNumberCalculation' => 'local',
            'navLinks' => 'true',
            'firstDay' => '1'
        ]);
        $calendar->setCallbacks([
            'navLinkWeekClick' => 'function(weekStart, jsEvent) {
                console.log(\'week start\', weekStart.toISOString().substring(0, 10));
                console.log(\'coords\', jsEvent.pageX, jsEvent.pageY);

                const value = `; ${document.cookie}`;
                const userId = value.split(`; ${"id"}=`);
                const weekValue = weekStart.toISOString().substring(0, 10);
                if (userId.length === 2){
                var id = userId.pop().split(\';\').shift();
                }

                $.ajax({
                type:\'POST\',
                url:"/getWeek",
                data:{myID:id,myWeek:weekValue},
                success:function(data){
                    window.location.href = "/weekView";
                }
            });
            }'
        ]);
        return view('calendar', compact('calendar', 'user'));
    }

    public function getDate(){
        return \Acaronlex\LaravelCalendar\Calendar::event($this->getDate())->isAllDay();
    }
}
