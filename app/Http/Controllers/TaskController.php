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
     * Ajax method to get the month and the id of the user
     */
    public function getData()
    {
        $this->month = $_POST['myMonth'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + 86400, "/");
        setcookie("month", $this->month, time() + 86400, "/");
    }

    /**
     * Ajax method to get the task id, and the information of that task.
     */
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
     * Method used to render the monthly view of the user.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * Ajax method to get the week and the user id
     */
    public function getWeek()
    {
        $this->week = $_POST['myWeek'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + 86400, "/");
        setcookie("week", $this->week, time() + 86400, "/");
    }

    /**
     * A method used to render the weekly view of that user.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function weekView()
    {
        if (!isset($_COOKIE["user"])) {
            echo "Cookie named '" . "' is not set!";
        } else {
            $id = $_COOKIE['user'];
            $date = date('Y-m-d', strtotime($_COOKIE['week'] . ' + 1 days'));

            $statuses = Status::where('is_active', '=', '1')->get();

            $statusTask = StatusTask::where('user_id', $id)->whereDate('status_date', '>=', $date)->whereDate('status_date', '<=', date('Y-m-d', strtotime($date . ' + 6 days')))->get();

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
     * Ajax method to set the cookie of today's day.
     */
    public function getDay()
    {
        setcookie("day", $_POST['myDay'], time() + 86400, "/");
    }

    /**
     * Add a task for user.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function addTask()
    {
        $today = date('Y-m-d', strtotime($_COOKIE['day'] . ' +1 days'));
        $settings = Setting::where('is_active', '=', 1)->get();
        return view('addtask', compact('settings', 'today'));
    }

    /**
     * An ajax method used to get the keyword for task filtration.
     */
    public function getKeyword()
    {
        $this->keyword = $_POST['myKeyword'];
        setcookie('myKeyword', $this->keyword, time() + 86400, "/");
    }

    /**
     * A method for task filtration
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function filteredView()
    {
        $id = $_COOKIE['user'];
        $date = $_COOKIE['month'];

        if (!isset($_COOKIE["myKeyword"])) {
            return $this->monthlyView();
        } else {
            $keyword = $_COOKIE['myKeyword'];

            $statuses = Status::where('is_active', '=', '1')->get();

            $groupByTaskID = Task::where('user_id', $id)->where('task_title', 'like', array('%' . $keyword . '%'))->get()->groupBy(function ($data) {
                return $data->id;
            });

            $taskTitleArray = array();

            foreach ($groupByTaskID as $task => $value) {
                $taskTitle = Task::where('id', $task)->first();
                array_push($taskTitleArray, $taskTitle);
            }

            $statusTask = StatusTask::where('user_id', $id)->whereMonth('created_at', $date)->get();

            return view('monthview', compact('statusTask', 'date', 'groupByTaskID', 'taskTitleArray', 'statuses'));

        }
    }

    /**
     * A method which is used for editing an existing task.
     * @param $id Task's id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
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
