<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;


class SettingController extends Controller
{
    //---------------------------Progress Update Setting
    public function progressUpdateSetting()
    {
        return view('settings.progress-update-setting');
    }

    //---------------------------Role access setting
    public function roleAccessSetting() {
        return view('settings.role-access-setting');
    }
}
