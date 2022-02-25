<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Facades\Calendar;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Status;
use App\Models\StatusTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $day;

    public function index()
    {
        $scheduleEvents= [];
        $id = $_COOKIE['isLoggedIn'];
        $user = User::find($id);
        $data = Task::all();
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
            'navLinks' => 'true',
            'firstDay' => '1'
        ]);
        $calendar->setCallbacks([
            'navLinkDayClick' => 'function(date, jsEvent) {
                const today = date.toISOString().substring(0, 10);

                var todayPlusOne = new Date();
                var dd = String(date.getDate()).padStart(2, "0");
                var mm = String(date.getMonth() + 1).padStart(2, "0");
                var yyyy = date.getFullYear();

                todayPlusOne = yyyy + "-" + mm + "-" + dd;

//                if(moment().format("YYYY-MM-DD") === todayPlusOne) {
                    $.ajax({
                        type:\'POST\',
                        url:"/getDay",
                        data:{myDay:today},
                        success:function(data){
                            window.location.href = "/userdashboard/create";
                        }
                    });
//                } else {
//                    alert("Wrong date selected.")
//                }
            }',
        ]);
        return view('userdashboard.index', compact('user', 'calendar'));
    }

    public function getDay()
    {
        $this->day = $_POST['myDay'];
        setcookie("day", $this->day, time() + (86400 * 30), "/");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = date('Y-m-d', strtotime($_COOKIE['day'] . ' +1 days'));
        setcookie('today', $today, time() + (86400), "/");
        $id = $_COOKIE['isLoggedIn'];
        $tasks = Task::where('user_id', $id)->get();
        $settings = Setting::where('is_active', '=', 1)->get();
        $statuses = Status::where('is_active', '=', 1)->get();
        return view('userdashboard.create', compact('settings','today', 'tasks', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateArray = array();
        //if it is an existing task
        if(strcmp($request->tasks, "newTaskOption") != 0) {
            $validateArray = [
                'description' => 'required',
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date'
            ];

            $this->validate($request, $validateArray);

            $taskSave = Task::where('id', $request->get('tasks'))->update([
                    'end_date' => $request->input('end_date'),
            ]);

            $newStatusTask = new StatusTask();
            $newStatusTask->status_id = $request->input('status');
            $newStatusTask->task_id = $request->get('tasks');
            $newStatusTask->user_id = $_COOKIE['isLoggedIn'];
            $newStatusTask->task_description = $request->input('description');
            $newStatusTask->task_remark = '';
            $newStatusTask->status_date = $_COOKIE['today'];
            $statusTaskSave = $newStatusTask->save();

            if ($taskSave && $statusTaskSave) {
                return redirect('/userdashboard')->with('success', 'Task Updated');
            }
        }
        //if it is a new task
        else {
            $validateArray = [
                'newtask' => 'required',
                'description' => 'required',
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date'
            ];

            $this->validate($request, $validateArray);

            //save for task
            $newTask = new Task();
            $newTask->user_id = $_COOKIE['isLoggedIn'];
            $newTask->task_title = $request->input('newtask');
            $newTask->start_date = $request->input('start_date');
            $newTask->end_date = $request->input('end_date');
            $taskSave = $newTask->save();

            $newStatusTask = new StatusTask();
            $newStatusTask->status_id = $request->input('status');
            $newStatusTask->task_id = $newTask->id;
            $newStatusTask->user_id = $_COOKIE['isLoggedIn'];
            $newStatusTask->task_description = $request->input('description');
            $newStatusTask->task_remark = '';
            $newStatusTask->status_date = $_COOKIE['today'];
            $statusTaskSave = $newStatusTask->save();


            if($taskSave && $statusTaskSave) {
                return redirect('/userdashboard')->with('success', 'Task Added');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
