<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\StatusTask;
use App\Models\Task;
use Illuminate\Http\Request;

class UserMonthlyViewController extends Controller
{
    /**
     * User's monthly view.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function usermonthview() {
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
        foreach ($groupByTaskID as $task => $value){
            $taskTitle = Task::where('id', $task)->first();
            array_push($taskTitleArray,$taskTitle);
        }
        setcookie('filter', false, time() + (-3600), "/");
        return view('usermonthview', compact('statusTask','date','groupByTaskID','taskTitleArray', 'statuses'));
    }

    public function filteredUserView()
    {
        $id = $_COOKIE['user'];
        $date = $_COOKIE['month'];

        if (!isset($_COOKIE["myKeyword"])) {
            return $this->usermonthview();
        } else {
            $keyword = $_COOKIE['myKeyword'];

            $statuses = Status::where('is_active', '=', '1')->get();

            $groupByTaskID = Task::where('user_id',$id)->where('task_title', 'like', array('%' . $keyword . '%'))->get()->groupBy(function ($data) {
                return $data->id;
            });

            $taskTitleArray = array();

            foreach ($groupByTaskID as $task => $value){
                $taskTitle = Task::where('id', $task)->first();
                array_push($taskTitleArray,$taskTitle);
            }

            $statusTask = StatusTask::where('user_id', $id)->whereMonth('created_at', $date)->get();

            return view('usermonthview', compact( 'statusTask','date','groupByTaskID','taskTitleArray', 'statuses'));

        }

    }
}
