<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Calendar;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    public $month, $user;

    public function getData()
    {
        $this->month = $_POST['myMonth'];
        $this->user = $_POST['myID'];
        setcookie("user", $this->user, time() + (86400 * 30), "/");
        setcookie("month", $this->month, time() + (86400 * 30), "/");
    }

    public function monthlyView()
    {
        if(!isset($_COOKIE["user"])) {
            echo "Cookie named '" . "' is not set!";
        } else {
            $id = $_COOKIE['user'];
            $date = $_COOKIE['month'];
        }
        $tasks = Task::where('user_id', $id)->whereMonth('start_date', $date)->paginate();
        return view('monthview', compact('tasks'));
    }

    public function weekview($id)
    {
        return view('weekview');
    }


}
