<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Acaronlex\LaravelCalendar\Facades\Calendar;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    public function admindashboard() {
        $teams = Team::all();
        return view('dashboard.admindashboard', compact('teams'));
    }

    public function userdashboard() {
        $scheduleEvents= [];
        $id = Session::get('isLoggedIn');
        $user = User::find($id);
        $data = Task::all();
        if($data->count()) {
            foreach($data as $key => $value) {
                if($value->user_id == $user->id) {
                    $scheduleEvents[] = Calendar::event(
                        $value->task_title,
                        true,
                        new \DateTime($value->start_date),
                        new \DateTime($value->end_date),
                    );
                }
            }
        }
        $calendar = Calendar::addEvents($scheduleEvents);
        $calendar->setOptions([
            'weekNumbers' => 'local',
            'navLinks' => 'true',
            'firstDay' => '1'
        ]);
        $calendar->setCallbacks([
            'navLinkDayClick' => 'function(date, jsEvent) {
                window.location.href = "/addtask";
            }',
            'navLinkWeekClick' => 'function(week, jsEvent) {
                console.log(week.toString());
            }'
        ]);
        return view('dashboard.userdashboard', compact('user', 'calendar'));
    }

    public function teamview($id) {
        $team = Team::find($id);

        return view('teamview', compact('team'));
    }


//
//    public function adduser() {
//        return view('adduser');
//    }
//
//    public function edituser() {
//        return view('edituser');
//    }
//
//
    public function userweekview() {
        return view('userweekview');
    }
}
