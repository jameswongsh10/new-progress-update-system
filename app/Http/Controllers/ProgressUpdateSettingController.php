<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ProgressUpdateSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $settings = Setting::all();
        return view('settings.progress-update-setting.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!strcmp($_COOKIE['user_role'], 'admin')) {
            return view('settings.progress-update-setting.create');
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
                'title' => 'required',
            ]);

            $newSetting = new Setting();
            $newSetting->progress_title = $request->input('title');
            $newSetting->is_active = $request->input('isActive');
            $htmlNameLower = strtolower($request->input('title'));
            $htmlName = str_replace(' ', '_', $htmlNameLower);
            $newSetting->html_name = $htmlName;
            $save = $newSetting->save();

            if ($save) {
                return back()->with('success', 'New progress title has been added.');
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
            $currentSetting = Setting::find($id);
            return view("settings.progress-update-setting.edit", compact('currentSetting'));
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
                'title' => 'required',
            ]);

            $save = $newSetting = Setting::where('id', $id)->update([
                'progress_title' => $request->input('title'),
                'is_active' => $request->input('isActive')
            ]);


            if ($save) {
                return back()->with('success', 'Progress title has been updated.');
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
        //
    }
}
