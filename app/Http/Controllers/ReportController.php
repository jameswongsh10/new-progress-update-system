<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    /**
     * A method used to display the view which allows the creation of a new report
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function create() {
        if (!strcmp($_COOKIE['user_role'], 'user')) {
            $settings = Setting::where('is_active', '=', '1')->get();
            $today = $_COOKIE['today'];

            return view('dailyreport', compact("today", "settings"));
        }
    }

    /**
     * Used to store a newly created daily report
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $save = '';
        $clickedDate = date('Y-m-d', strtotime($_COOKIE['day'] . ' + 1 days'));
        //Dynamic setting
        $settings = Setting::where('is_active', '=', 1)->get();
        $htmlName = array();
        $validateArray = array();
        //for looping the html name which is used to identify the setting in the view page.
        foreach($settings as $setting) {
            $htmlName[] = $setting->html_name;
        }
        //storing the html name in an array for validation.
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

    /**
     * Used to display the view to edit the existing report.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function edit() {
        if (!strcmp($_COOKIE['user_role'], 'user')) {
            $today = $_COOKIE['today'];
            $settings = Setting::where('is_active', '=', '1')->get();
            $reports = Report::where('user_id', $_COOKIE['isLoggedIn'])->whereDate('report_date', '=', $today)->get();
            return view('editDailyReport', compact('today', 'reports', 'settings'));
        }
    }

    /**
     * A method to store the updated daily report.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Illuminate\Validation\ValidationException
     */
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
