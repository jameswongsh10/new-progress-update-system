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


class ReportViewController extends Controller
{
    public function reportView($created_at)
    {
        $id = $_COOKIE['id'];
        $user = User::find($id);
        $answer = Report::where('user_id',$id)->whereDate('created_at', '=', date($created_at))->paginate();
        $question_array = array();
        foreach ($answer as $ans){
            $question = Setting::where('id',$ans->setting_id)->first();
            $set = array($question->progress_title,$ans->answer);
            array_push($question_array,$set);
        }
        return view('report',compact('created_at','user','answer','question_array'));
    }

    public function getReportDate(){

    }

}
