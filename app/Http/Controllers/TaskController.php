<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;


class TaskController extends Controller
{
    public function monthview($id) {
        $tasks = Task::where('user_id', $id)->paginate(3);

        return view('monthview', compact('tasks'));
    }

    public function weekview($id) {
        return view('weekview');
    }




}
