<?php

namespace App\Http\Controllers;

use Acaronlex\LaravelCalendar\Calendar;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Elibyy\TCPDF\Facades\TCPDF;

/**
 * This controller class is used to create the pdf view for the report. It is using a library created by Eliby and uses the TCPDF (https://github.com/elibyy/tcpdf-laravel)
 */

class ReportViewController extends Controller
{
    /**
     *
     * @param $created_at
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function reportView($created_at)
    {
        setcookie("created_at", $created_at, time() + 86400, "/");

        $lastDay = date('Y-m-d', strtotime($created_at . ' + 6 days'));
        $id = $_COOKIE['id'];
        $user = User::find($id);

        //group by day
        $groupByDay = Report::where('user_id', $id)->whereDate('report_date', '>=', date('Y-m-d', strtotime($created_at)))->whereDate('report_date', '<=', date($lastDay))->orderBy('report_date')->get()->groupBy(function ($data) {
            return $data->report_date;
        });

        $question_array = array();
        $report_question_array = array();

        foreach ($groupByDay as $day) {
            foreach ($day as $newDay) {
                $question = Setting::where('id', $newDay->setting_id)->first();
                $set = array($question, $newDay);
                $printReportSet = array($newDay->report_date,$question->progress_title, $newDay->answer);
                array_push($question_array, $set);
                array_push($report_question_array, $printReportSet);
            }
        }

        setcookie("report_user", serialize($user), time() + 86400, "/");
        setcookie("array", serialize($report_question_array), time() + 86400, "/");
        setcookie("reportArray", serialize($report_question_array), time() + 86400, "/");
        return view('report', compact('created_at', 'user', 'question_array', 'lastDay','groupByDay'));
    }

    public function pdf()
    {
        $filename = 'Daily Report.pdf';
        $created_at = date('Y-m-d', strtotime($_COOKIE['created_at']));
        $question_array = unserialize($_COOKIE["array"]);
        $user = unserialize($_COOKIE["report_user"]);

        //group by day
        $groupByDay = Report::where('user_id', $_COOKIE['id'])->whereDate('report_date', '>=', date($created_at))->whereDate('report_date', '<=', date(date('Y-m-d', strtotime($created_at . ' + 6 days'))))->orderBy('report_date')->get()->groupBy(function ($data) {
            return $data->report_date;
        });

        $data = [
            'date' => $created_at,
            'user' => $user,
            'question_array' => $question_array,
            'groupByDay' => $groupByDay
        ];

        $view = \View::make('ReportFile', $data);
        $html = $view->render();

        $pdf = new TCPDF;

        $pdf::SetTitle('Daily Report');
        $pdf::SetMargins(8, 5, 10, true);
        $pdf::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 006', PDF_HEADER_STRING);

        $pdf::AddPage();

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output($filename,'D');
    }


}
