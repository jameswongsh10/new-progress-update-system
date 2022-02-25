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
        if (!strcmp($_COOKIE['user_role'], 'user')) {
            $settings = Setting::where('is_active', '=', '1')->get();
            $today = $_COOKIE['today'];
            return view('dailyreport', compact("today", "settings"));
        }
    }

    public function store(Request $request) {
        $save = '';
        $clickedDate = date('Y-m-d', strtotime($_COOKIE['day'] . ' + 1 days'));

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
            $newReport->report_date = $clickedDate;
            $newReport->answer = $request->input($setting->html_name);
            $save = $newReport->save();
        }
        setcookie('daily_report_done', '1', time() + (86400), "/");

        if ($save) {
            return redirect('/userdashboard')->with('success', 'Daily report has been added');
        }
    }


    public function edit() {
        if (!strcmp($_COOKIE['user_role'], 'user')) {
            $today = $_COOKIE['today'];
            $settings = Setting::where('is_active', '=', '1')->get();
            $reports = Report::where('user_id', $_COOKIE['isLoggedIn'])->whereDate('created_at', '=', $today)->get();
            return view('editDailyReport', compact('today', 'reports', 'settings'));
        }
    }


    public function update(Request $request) {
        if (!strcmp($_COOKIE['user_role'], 'user')) {
            $validateArray = array();
            $settings = Setting::where('is_active', '=', 1)->get();

            foreach ($settings as $setting) {
                $htmlName[] = $setting->html_name;
            }

            for ($i = 0; $i < count($htmlName); $i++) {
                $validateArray[$htmlName[$i]] = 'required';
            }

            $this->validate($request, $validateArray);

            foreach ($settings as $setting) {
                $existingReport = Report::where('user_id', $_COOKIE['isLoggedIn'])->where('setting_id', $setting->id)->first();
                $existingReport->answer = $request->input($setting->html_name);
                $save = $existingReport->save();
            }

            if ($save) {
                return redirect('/userdashboard')->with('success', 'Daily report has been edited');
            }
        }
    }
}
