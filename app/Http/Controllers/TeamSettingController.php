<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TeamSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();
        return view('settings.teamsetting.index', compact("teams"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            return view('settings.teamsetting.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $request->validate([
                'teamname' => 'required',
            ]);

            $newTeam = new Team();
            $newTeam->team_name = $request->input('teamname');
            $save = $newTeam->save();

            if ($save) {
                return redirect('/settings/teamsetting')->with('success', 'New team has been added');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $teams = Team::all();
            return view('settings.teamsetting.edit', compact('teams'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $request->validate([
                'teamname' => 'required',
            ]);

            $save = Team::where('id', $id)->update([
                'team_name' => $request->input('teamname')
            ]);

            if ($save) {
                return redirect('/settings/teamsetting')->with('success', 'The name of the team has been updated');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $team = Team::find($id);
            $team->delete();

            return back()->with('success', 'Team has been deleted');
        }
    }
}
