<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\StatusTask;
use App\Models\Task;
use Illuminate\Http\Request;

class UserMonthlyViewController extends Controller
{
    /**
     * Show specific user's task for the selected month.
     *
     * @return \Illuminate\Http\Response
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

        //search for task name and push the task name into the array
        foreach ($groupByTaskID as $task => $value){
            $taskTitle = Task::where('id', $task)->first();
            array_push($taskTitleArray,$taskTitle);
        }

        setcookie('filter', false, time() + (-3600), "/");
        return view('usermonthview', compact('statusTask','date','groupByTaskID','taskTitleArray', 'statuses'));
    }

    /**
     * Display filtered result which searched by the Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function filteredUserView()
    {
        $id = $_COOKIE['user'];
        $date = $_COOKIE['month'];

        if (!isset($_COOKIE["myKeyword"])) {
            return $this->usermonthview();
        } else {
            $keyword = $_COOKIE['myKeyword'];

            $statuses = Status::where('is_active', '=', '1')->get();

            //Get tasks which its title is alike to the keyword and group them by Task id.
            $groupByTaskID = Task::where('user_id',$id)->where('task_title', 'like', array('%' . $keyword . '%'))->get()->groupBy(function ($data) {
                return $data->id;
            });

            $taskTitleArray = array();

            //Push the title of the task to the array.
            foreach ($groupByTaskID as $task => $value){
                $taskTitle = Task::where('id', $task)->first();
                array_push($taskTitleArray,$taskTitle);
            }

            $statusTask = StatusTask::where('user_id', $id)->whereMonth('created_at', $date)->get();

            return view('usermonthview', compact( 'statusTask','date','groupByTaskID','taskTitleArray', 'statuses'));

        }

    }
}
