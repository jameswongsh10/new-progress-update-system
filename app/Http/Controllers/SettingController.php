<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;


class SettingController extends Controller
{
    public function progressUpdateSetting()
    {

    }

    //-----------------------------------Team Setting----------------------------
    public function teamSetting()
    {
        $team = Team::all();

    }
}
