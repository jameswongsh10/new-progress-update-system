<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Facades\Calendar;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

/**
 * This file uses a library by acaronlex, https://github.com/acaronlex/laravel-calendar and a JS plugin called Full Calendar, https://fullcalendar.io/
 */

class FullCalendarController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     *
     * An id is taken in to find the user which the admin has clicked into, and render the specific tasks which belongs to them.
     * Then a calendar will be rendered with the tasks.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function usercalendar(Request $request, $id) {
        $scheduleEvents= [];
        $user = User::find($id);
        $data = Task::all();
        setcookie("id", $id, time() + 86400, "/");

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
        // A callback function is added to the calendar, which allows the user to be able to click on the week number.
        // An ajax call is being triggered whenever the user clicks on the week number, which transport the week value to the next page.
        $calendar->setCallbacks([
            'navLinkWeekClick' => 'function(weekStart, jsEvent) {
                console.log(weekStart.toISOString().substring(0, 10));

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
}
