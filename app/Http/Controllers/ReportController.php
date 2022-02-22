<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    public function create() {
        $settings = Setting::where('is_active', '=', '1')->get();
        $today = $_COOKIE['today'];
        return view('dailyreport', compact("today", "settings"));
    }

    public function store(Request $request) {
        $save = '';
        $settings = Setting::where('is_active', '=', 1)->get();
        $htmlName = array();
        $validateArray = array();
        foreach($settings as $setting) {
            $htmlName[] = $setting->html_name;
        }
        for($i = 0; $i < count($htmlName); $i++) {
            $validateArray[$htmlName[$i]] = 'required';
        }
        $this->validate($request, $validateArray);

        foreach ($settings as $setting) {
            $newReport = new Report();
            $newReport->user_id = $_COOKIE['isLoggedIn'];
            $newReport->setting_id = $setting->id;
            $newReport->answer = $request->input($setting->html_name);
            $save = $newReport->save();
        }
        setcookie('daily_report_done', '1', time() + (86400), "/");

        if ($save) {
            return redirect('/userdashboard/create')->with('success', 'Daily report has been added');
        }
    }
}
