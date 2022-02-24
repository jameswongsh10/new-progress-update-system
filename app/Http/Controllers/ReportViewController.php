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


class ReportViewController extends Controller
{
    public function reportView($created_at)
    {
        setcookie("created_at", $created_at, time() + 86400, "/");
        $lastDay = date('Y-m-d', strtotime($created_at . ' + 6 days'));
        $id = $_COOKIE['id'];
        $user = User::find($id);


        //group by day
        $groupByDay = Report::where('user_id', $id)->whereDate('created_at', '>=', date($created_at))->whereDate('created_at', '<=', date($lastDay))->orderBy('created_at')->get()->groupBy(function ($data) {
            return $data->created_at->format('Y-m-d');
        });

        $question_array = array();
        $report_question_array = array();

        foreach ($groupByDay as $day) {
            foreach ($day as $newDay) {
                $question = Setting::where('id', $newDay->setting_id)->first();
                $set = array($question, $newDay);
                $printReportSet = array($newDay->created_at,$question->progress_title, $newDay->answer);
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
        $created_at = $_COOKIE['created_at'];
        $question_array = unserialize($_COOKIE["array"]);
        $user = unserialize($_COOKIE["report_user"]);

        //group by day
        $groupByDay = Report::where('user_id', $_COOKIE['id'])->whereDate('created_at', '>=', date($created_at))->whereDate('created_at', '<=', date(date('Y-m-d', strtotime($created_at . ' + 6 days'))))->orderBy('created_at')->get()->groupBy(function ($data) {
            return $data->created_at->format('Y-m-d');
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
        $pdf::Output($filename);
    }


}
