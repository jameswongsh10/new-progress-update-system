<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
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
                return redirect('dashboard');
            } else {
                return back()->with('fail', 'Email or password is wrong');
            }
        }
    }

    public function logout() {
        if(session()->has('isLoggedIn')) {
            session()->pull('isLoggedIn');
            return redirect('/');
        }
    }
}
