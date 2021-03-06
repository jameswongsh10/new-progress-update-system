<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Acaronlex\LaravelCalendar\Facades\Calendar;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    /**
     * Admin dashboard view.
     */
    public function admindashboard() {
        $teams = Team::all();
        return view('dashboard.admindashboard', compact('teams'));
    }

    /**
     * The team view on the dashboard.
     */
    public function teamview($id) {
        $team = Team::find($id);
        return view('teamview', compact('team'));
    }
}
