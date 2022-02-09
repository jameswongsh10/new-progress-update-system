<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    public function getRequestPost() {
        $data = $_POST['myData'];
        $tasks = Task::where('user_id', 2)->whereMonth('start_date',$data)->paginate();
        return view('monthview', compact('tasks'));
    }

    public function monthview($id) {
        $tasks = Task::where('user_id', $id);//->whereMonth('start_date',$data)->paginate();
        return view('monthview', compact('tasks'));
    }
//
//    public function monthviewPost() {
//        $data = $_POST['myData'];
//        $tasks = Task::where('user_id', 2)->whereMonth('start_date',$data)->paginate();
//        return view('monthview', compact('tasks'));
//    }

//    public function monthview() {
//        $tasks = Task::where('user_id', 1)->paginate();
//        return view('monthview', compact('tasks'));
//    }

    public function weekview($id) {
        return view('weekview');
    }

}
