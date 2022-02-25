<?php

namespace App\Http\Controllers;

use http\Cookie;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function check(Request $request)
    {
        //validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|alpha_num',
        ]);

        $userInfo = User::where('email', '=', $request->email)->first();

        if (!$userInfo) {
            return back()->with('fail', 'Email or password is wrong');
        } else {
            if (Hash::check($request->password, $userInfo->password)) {
                setcookie("isLoggedIn", $userInfo->id, time() + 86400, "/");
                setcookie("user_role", $userInfo->role, time() + 86400, "/");
//                setcookie('filter', true, time() + 86400, "/");
                if (strcmp($userInfo->role, 'admin') == 0 || strcmp($userInfo->role, 'viewer') == 0) {
                    return redirect('dashboard');
                } elseif (strcmp($userInfo->role, 'user') == 0) {
                    return redirect('userdashboard');
                }
            } else {
                return back()->with('fail', 'Email or password is wrong');
            }
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        setcookie("isLoggedIn", "", time() + (-3600), "/");
        if(isset($_COOKIE['daily_report_done'])) {
            setcookie("daily_report_done", "", time() + (-3600), "/");
        }
        return redirect('login');
    }
}
