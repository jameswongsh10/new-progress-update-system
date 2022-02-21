<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login() {
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

        if(!$userInfo) {
            return back()->with('fail', 'Email or password is wrong');
        } else {
            if(Hash::check($request->password, $userInfo->password)) {
                $request->session()->put('isLoggedIn', $userInfo->id);
                setcookie("user_role", $userInfo->role, time() + (86400 * 30), "/");
                if(strcmp($userInfo->role, 'admin') == 0 || strcmp($userInfo->role, 'viewer') == 0) {
                    return redirect('dashboard');
                } elseif(strcmp($userInfo->role, 'user') == 0 ) {
                    return redirect('userdashboard');
                }
            } else {
                return back()->with('fail', 'Email or password is wrong');
            }
        }
    }

    public function logout() {
        if(session()->has('isLoggedIn')) {
            Auth::logout();
            Session::flush();
            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    setcookie($name, '', time()-1000);
                    setcookie($name, '', time()-1000, '/');
                }
            }
        }
        return redirect('login');
    }
}
