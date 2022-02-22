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
        setcookie("created_at", $created_at, time() + (86400 * 30), "/");
        $id = $_COOKIE['id'];
        $user = User::find($id);
        $answer = Report::where('user_id',$id)->whereDate('created_at', '=', date($created_at))->paginate();
        $question_array = array();
        foreach ($answer as $ans){
            $question = Setting::where('id',$ans->setting_id)->first();
            $set = array($question->progress_title,$ans->answer);
            array_push($question_array,$set);
        }
        setcookie("report_user", serialize($user), time() + (86400 * 30), "/");
        setcookie("array", serialize($question_array), time() + (86400 * 30), "/");
        return view('report',compact('created_at','user','question_array'));
    }

    public function pdf()
    {
        $filename = 'Daily Report.pdf';
        $created_at = $_COOKIE['created_at'];
        $question_array = unserialize($_COOKIE["array"]);
        $user = unserialize($_COOKIE["report_user"]);
        $data = [
            'date' => $created_at,
            'user' => $user,
            'question_array' => $question_array
        ];

        $view = \View::make('ReportFile',$data);
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
