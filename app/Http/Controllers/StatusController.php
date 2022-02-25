<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $statuses = Status::all();
        return view('settings.status-setting.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            return view('settings.status-setting.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $request->validate([
                'title' => 'required',
                'colour' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
            ]);

            $newStatus = new Status();
            $newStatus->status_title = $request->input('title');
            $newStatus->is_active = $request->input('isActive');
            $newStatus->colour = $request->input('colour');
            $htmlNameLower = strtolower($request->input('title'));
            $htmlName = str_replace(' ', '_', $htmlNameLower);
            $newStatus->html_name = $htmlName;

            $save = $newStatus->save();

            if ($save) {
                return redirect('/settings/status-setting')->with('success', 'New status title has been added.');
            }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $currentStatus= Status::find($id);
            return view("settings.status-setting.edit", compact('currentStatus'));
        }
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
        //
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            $request->validate([
                'title' => 'required',
                'colour' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
            ]);

            $save = $newStatus = Status::where('id', $id)->update([
                'status_title' => $request->input('title'),
                'is_active' => $request->input('isActive'),
                'colour' => $request->input('colour')
            ]);

            if ($save) {
                return redirect('/settings/status-setting')->with('success', 'Status has been updated.');
            }
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
        //
    }
}
