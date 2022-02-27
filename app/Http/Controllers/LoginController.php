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

    /**
     *
     * Method used for checking the validity of the credentials entered by the user.
     *
     */
    public function check(Request $request)
    {
        //input validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|alpha_num',
        ]);

        $userInfo = User::where('email', '=', $request->email)->first();

        if (!$userInfo) {
            //Email check
            return back()->with('fail', 'Email or password is wrong');
        } else {
            //password check
            if (Hash::check($request->password, $userInfo->password)) {
                //set cookies
                setcookie("isLoggedIn", $userInfo->id, time() + 86400, "/");
                setcookie("user_role", $userInfo->role, time() + 86400, "/");
                //redirection
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

    /**
     *
     * Logout method. It removes all the session data and set the isLoggedIn cookie to none.
     * Redirects the user back to login page.
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();
        setcookie("isLoggedIn", "", time() + (-3600), "/");
        return redirect('login');
    }
}
