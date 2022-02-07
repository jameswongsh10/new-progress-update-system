<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;


class DashboardController extends Controller
{
    public function admindashboard() {
        $teams = Team::all();
        return view('dashboard', compact('teams'));
    }

    public function settings() {
        return view('settings');
    }

    public function teamview($id) {
        $team = Team::find($id);

        return view('teamview', compact('team'));
    }


//
//    public function adduser() {
//        return view('adduser');
//    }
//
//    public function edituser() {
//        return view('edituser');
//    }
//
//
    public function userweekview() {
        return view('userweekview');
    }

    public function addtask() {
        return view('addtask');
    }


}
