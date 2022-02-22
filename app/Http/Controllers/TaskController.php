<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Calendar;
use App\Models\Setting;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    public $month, $user, $week, $day, $keyword;

    public function getData()
    {
        $this->month = $_POST['myMonth'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + 86400, "/");
        setcookie("month", $this->month, time() + 86400, "/");
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
        setcookie("user", $this->user, time() + 86400, "/");
        setcookie("week", $this->week, time() + 86400, "/");
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
            $tasks = Task::where('user_id', $id)->where(function ($query) use ($weekend, $week) {
                $query->where('start_date', '<=', $week)
                    ->where('end_date', '>=', $weekend);
            })->orWhere(function ($query) use ($id, $weekend, $week) {
                $query->where('user_id', $id)
                    ->where('start_date', '>=', $week)
                    ->where('end_date', '<=', $weekend);
            })->orWhere(function ($query) use ($id, $weekend, $week) {
                $query->where('user_id', $id)
                    ->where('start_date', '<=', $week)
                    ->where('end_date', '>', $week);
            })->orWhere(function ($query) use ($id, $weekend, $week) {
                $query->where('user_id', $id)
                    ->where('start_date', '<', $weekend)
                    ->where('end_date', '>=', $weekend);
            })->paginate();

        }
        return view('weekView', compact('tasks'));
    }

    public function getDay()
    {
        $this->day = $_POST['myDay'];
        setcookie("day", $this->day, time() + 86400, "/");
    }

    public function addTask()
    {
        $today = date('Y-m-d', strtotime($_COOKIE['day'] . ' +1 days'));
        $settings = Setting::where('is_active', '=', 1)->get();
        return view('addtask', compact('settings', 'today'));
    }

    public function getKeyword()
    {
        $this->keyword = $_POST['myKeyword'];
        setcookie('myKeyword', $this->keyword, time() + 86400, "/");
    }

    public function filteredView()
    {
        $id = $_COOKIE['user'];
        $date = $_COOKIE['month'];

        if (!isset($_COOKIE["myKeyword"])) {
            $tasks = Task::where('user_id', $id)->whereMonth('start_date', $date)->get();
            return view('monthview', compact('tasks'));
        } else {
            $keyword = $_COOKIE['myKeyword'];
            $tasks = Task::where('user_id', $id)->whereMonth('start_date', $date)->where('task_title', 'like', array('%' . $keyword . '%'))->get();
            return view('monthview', compact('tasks'));
        }
    }

    public function editTask($id)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $task = Task::where('id', $id)->first();
            return view('editTask', compact('task'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function updateTask(Request $request, int $id)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {

            $request->validate([
                'status' => 'required',
                'remark' => 'required',
            ]);

            try {
                $existingTask = Task::find($id);
                $existingTask->status = $request->input('status');
                $existingTask->remark = $request->input('remark');
                $save = $existingTask->save();
                if ($save) {
                    return back()->with('success', 'User has been updated.');
                }
            } catch (\Exception $e) {
                return back()->with('failed', 'Please check your information and try again.');
            }
        }
    }
}
