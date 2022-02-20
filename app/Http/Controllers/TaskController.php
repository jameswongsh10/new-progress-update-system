<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Calendar;
use App\Models\Setting;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class TaskController extends Controller
{
    public $month, $user, $week, $day, $taskId;

    public function getData()
    {
        $this->month = $_POST['myMonth'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + (86400 * 30), "/");
        setcookie("month", $this->month, time() + (86400 * 30), "/");
    }

    public function getTaskId() {
        $this->taskId = $_POST['taskId'];
        $returnTask = array();
        $task = Task::where('id', $this->taskId)->first();

        $returnTask['taskId'] = $task->id;
        $returnTask['desc'] = $task->task_description;
        $returnTask['start_date'] = $task->start_date;
        $returnTask['end_date'] = $task->end_date;

        echo json_encode($returnTask);
    }

    public function monthlyView()
    {
        if (!isset($_COOKIE["user"])) {
            echo "Cookie named '" . "' is not set!";
        } else {
            $id = $_COOKIE['user'];
            $date = $_COOKIE['month'];
        }
        $tasks = Task::where('user_id', $id)->whereMonth('start_date', $date)->paginate();
        return view('monthview', compact('tasks'));
    }

    public function getWeek()
    {
        $this->week = $_POST['myWeek'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + (86400 * 30), "/");
        setcookie("week", $this->week, time() + (86400 * 30), "/");
    }

    public function weekView()
    {
        if (!isset($_COOKIE["user"])) {
            echo "Cookie named '" . "' is not set!";
        } else {
            $id = $_COOKIE['user'];
            $week = $_COOKIE['week'];

            $week = date('Y-m-d', strtotime($week));
            $weekend = date('Y-m-d', strtotime($week . ' +6 days'));
            $tasks = Task::where('user_id', $id)->where(function($query) use ($weekend, $week) {
                $query->where('start_date', '<=', $week)
                    ->where('end_date', '>=', $weekend);
            })->orWhere(function($query) use ($id, $weekend, $week) {
                $query->where('user_id', $id)
                    ->where('start_date', '>=', $week)
                    ->where('end_date', '<=', $weekend);
            })->orWhere(function($query) use ($id, $weekend, $week) {
                $query->where('user_id', $id)
                    ->where('start_date', '<=', $week)
                    ->where('end_date', '>', $week);
            })->orWhere(function($query) use ($id, $weekend, $week) {
                $query->where('user_id', $id)
                    ->where('start_date', '<', $weekend)
                    ->where('end_date', '>=', $weekend);
            })->paginate();

        }
        return view('weekView', compact('tasks'));
    }
}
