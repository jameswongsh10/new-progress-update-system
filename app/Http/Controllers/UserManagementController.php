<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(3);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teams = Team::all();
        return view('users.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'gender' => 'required',
            'position' => 'required',
            'role' => 'required',
            'team_id' => 'required',
            'password' => 'required|min:8|max:20|alpha_num',
        ]);

//        $save = DB::table('users')->insert([
//            'name' => $request->input('name'),
//            'email' => $request->input('email'),
//            'gender' => $request->input('gender'),
//            'position' => $request->input('position'),
//            'role' => $request->input('position'),
//            'password' => Hash::make($request->input('password')),
//        ]);
//
//        $save2 = DB::table('team_user')->insert([
//            'user_id' => DB::table('users')
//                            ->select('id'),
//        ]);
        $newUser = new User();
        $newUser->name = $request->input('name');
        $newUser->email = $request->input('email');
        $newUser->gender = $request->input('gender');
        $newUser->position = $request->input('position');
        $newUser->role = $request->input('role');
        $newUser->password = Hash::make($request->input('password'));
        $save = $newUser->save();
        $team = Team::find($request->input('team_id'));
        $newUser->teams()->attach($team);


        if ($save) {
            return back()->with('success', 'New user has been added.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //dont have to care about show since i do not want to show any users. Edit will do the job
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::all();
        $teams = Team::all();
        return view('users.edit', compact('users', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'gender' => 'required',
            'position' => 'required',
            'role' => 'required',
            'team_id' => 'required',
            'password' => 'required|min:8|max:20|alpha_num',
            //'oldPassword' => can have a confirmation of old and new password
            // or maybe admin shouldn't be able to edit password, but only users.
            // note to self*
        ]);



        $existingUser = User::find($id);
        $existingUser->name = $request->input('name');
        $existingUser->email = $request->input('email');
        $existingUser->gender = $request->input('gender');
        $existingUser->position = $request->input('position');
        $existingUser->role = $request->input('role');
        $existingUser->password = Hash::make($request->input('password'));
        $save = $existingUser->save();
        TeamUser::where('user_id', $id)->update([
            'team_id' => $request->input('team_id'),
        ]);

        if ($save) {
            return back()->with('success', 'User has been updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        Session::flash('deleted', 'User has been deleted');
        return Redirect::to('users');
    }
}
