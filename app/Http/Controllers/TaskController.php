<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Calendar;
use App\Models\Setting;
use App\Models\Status;
use App\Models\StatusTask;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class TaskController extends Controller
{
    public $month, $user, $week, $day, $keyword, $taskId;

    /**
     * Get month that clicked by Admin.
     * Gets the user's ID that are currently being view by admin.
     * Set the month and user's ID as cookie.
     */
    public function getData()
    {
        $this->month = $_POST['myMonth'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + 86400, "/");
        setcookie("month", $this->month, time() + 86400, "/");
    }

    public function getTaskId()
    {
        $this->taskId = $_POST['taskId'];
        $returnTask = array();
        $task = Task::where('id', $this->taskId)->first();
        $statusTask = StatusTask::where('task_id', $this->taskId)
            ->where('user_id', $_COOKIE['isLoggedIn'])
            ->first();

        $returnTask['status_id'] = $statusTask->status_id;
        $returnTask['user_id'] = $_COOKIE['isLoggedIn'];
        $returnTask['taskId'] = $task->id;
        $returnTask['desc'] = $task->task_description;
        $returnTask['start_date'] = $task->start_date;
        $returnTask['end_date'] = $task->end_date;

        echo json_encode($returnTask);
    }

    /**
     * Show specific user's task for the selected month.
     *
     * @return \Illuminate\Http\Response
     */
    public function monthlyView()
    {
        if (!isset($_COOKIE["user"])) {
            echo "Cookie named '" . "' is not set!";
        } else {
            $id = $_COOKIE['user'];
            $date = $_COOKIE['month'];
        }
        $statuses = Status::where('is_active', '=', '1')->get();

        $statusTask = StatusTask::where('user_id', $id)->whereMonth('created_at', $date)->get();

        //Get all the tasks added by user at this month and group it by task_id.
        $groupByTaskID = StatusTask::where('user_id', $id)->whereMonth('created_at', $date)->orderBy('created_at')->get()->groupBy(function ($data) {
            return $data->task_id;
        });

        $taskTitleArray = array();

        //search for task name
        foreach ($groupByTaskID as $task => $value) {
            $taskTitle = Task::where('id', $task)->first();
            array_push($taskTitleArray, $taskTitle);
        }
        setcookie('filter', false, time() + (-3600), "/");
        return view('monthview', compact('statusTask', 'date', 'groupByTaskID', 'taskTitleArray', 'statuses'));
    }

    /**
     * Get week that clicked by admin.
     * Gets the user's ID that are currently being view by admin.
     * Set the week and user's ID as cookie.
     */
    public function getWeek()
    {
        $this->week = $_POST['myWeek'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + 86400, "/");
        setcookie("week", $this->week, time() + 86400, "/");
    }

    /**
     * Show specific user's task for the selected week.
     *
     * @return \Illuminate\Http\Response
     */
    public function weekView()
    {
        if (!isset($_COOKIE["user"])) {
            echo "Cookie named '" . "' is not set!";
        } else {
            $id = $_COOKIE['user'];
            $date = date('Y-m-d', strtotime($_COOKIE['week'] . ' + 1 days'));

            $statuses = Status::where('is_active', '=', '1')->get();

            //Get all the tasks added by user within the week.
            $statusTask = StatusTask::where('user_id', $id)->whereDate('status_date', '>=', $date)->whereDate('status_date', '<=', date('Y-m-d', strtotime($date . ' + 6 days')))->get();

            //Get all the tasks added by user within the week and group it by task_id.
            $groupByTaskID = StatusTask::where('user_id', $id)->whereDate('status_date', '>=', $date)->whereDate('status_date', '<=',date('Y-m-d', strtotime($date . ' + 6 days')))->orderBy('status_date')->get()->groupBy(function ($data) {
                return $data->task_id;
            });

            $taskTitleArray = array();

            //search for task name
            foreach ($groupByTaskID as $task => $value) {
                $taskTitle = Task::where('id', $task)->first();
                array_push($taskTitleArray, $taskTitle);
            }
            setcookie('filter', false, time() + (-3600), "/");
            return view('weekView', compact('statusTask', 'date', 'groupByTaskID', 'taskTitleArray', 'statuses'));
        }
    }

    /**
     * Get the date of the day which is selected by the Admin.
     * Set the date as cookie.
     */
    public function getDay()
    {
        setcookie("day", $_POST['myDay'], time() + 86400, "/");
    }

    public function addTask()
    {
        $today = date('Y-m-d', strtotime($_COOKIE['day'] . ' +1 days'));
        $settings = Setting::where('is_active', '=', 1)->get();
        return view('addtask', compact('settings', 'today'));
    }

    /**
     * Get keyword from search text box and set it as cookie.
     */
    public function getKeyword()
    {
        $this->keyword = $_POST['myKeyword'];
        setcookie('myKeyword', $this->keyword, time() + 86400, "/");
    }

    /**
     * Display filtered result which searched by the Admin.
     *
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function filteredView($type)
    {
        $id = $_COOKIE['user'];
        if(!strcmp($type,"month")){
            $date = $_COOKIE['month'];
        }else{
            $date = date('Y-m-d', strtotime($_COOKIE['week'] . ' + 1 days'));
        }

        if (!isset($_COOKIE["myKeyword"])) {

            if(!strcmp($type,"month")){
                return $this->monthlyView();
            }else{
                return $this->weekView();
            }
        } else {
            $keyword = $_COOKIE['myKeyword'];

            $statuses = Status::where('is_active', '=', '1')->get();

            //Get tasks which its title is alike to the keyword and group them by Task id.
            $groupByTaskID = Task::where('user_id', $id)->where('task_title', 'like', array('%' . $keyword . '%'))->get()->groupBy(function ($data) {
                return $data->id;
            });

            $taskTitleArray = array();

            foreach ($groupByTaskID as $task => $value) {
                $taskTitle = Task::where('id', $task)->first();
                //Push the task title into the array
                array_push($taskTitleArray, $taskTitle);
            }

            if(!strcmp($type,"month")){
                $statusTask = StatusTask::where('user_id', $id)->whereMonth('created_at', $date)->get();
                return view('monthview', compact('statusTask', 'date', 'groupByTaskID', 'taskTitleArray', 'statuses'));

            }else{
                $statusTask = StatusTask::where('user_id', $id)->whereDate('status_date', '>=', $date)->whereDate('status_date', '<=', date('Y-m-d', strtotime($date . ' + 6 days')))->get();
                return view('weekView', compact('statusTask', 'date', 'groupByTaskID', 'taskTitleArray', 'statuses'));
            }

        }
    }

    public function editTask($id)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $statuses = Status::where('is_active', '=', '1')->get();
            $statusTask = StatusTask::find($id);
            return view('editTask', compact('statusTask', 'statuses'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    // the ID here is the status task ID
    public function updateTask(Request $request, int $id)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {

            $request->validate([
                'status' => 'required',
                'remark' => 'required',
            ]);

            try {
                $existingStatusTask = StatusTask::find($id);
                $existingStatusTask->status_id = $request->input('status');
                $existingStatusTask->task_remark = $request->input('remark');
                $save = $existingStatusTask->save();
                if ($save) {
                    return redirect('/monthlyView')->with('success', 'Task has been updated.');
                }
            } catch (\Exception $e) {
                return back()->with('failed', 'Please check your information and try again.');
            }
        }
    }
}
