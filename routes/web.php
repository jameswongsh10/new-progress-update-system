<?php

use App\Http\Controllers\ProgressUpdateSettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportViewController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamSettingController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FullCalendarController;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//----------------- Login -----------------
Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('check', [LoginController::class, 'check'])->name('check');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

//----------------- User Management -----------------

Route::resource('users', UserManagementController::class);
Route::resource('teamsetting', TeamSettingController::class);
Route::resource('userdashboard', UserDashboardController::class);

Route::get('/dailyreport', [ReportController::class, 'create'])->name('dailyreport');
Route::post('/dailyreport', [ReportController::class, 'store'])->name("dailyreportstore");

//Route::get('/users', [DashboardController::class, 'displayUsers'])->name('users');
Route::get('/users/adduser', [DashboardController::class, 'addUser'])->name('adduser');


Route::get('/dashboard', [DashboardController::class, 'admindashboard'])->name('dashboard');

//
Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
Route::get('/teamview/{id}', [DashboardController::class, 'teamview'])->name('teamview')->where('id', '[0-9]+');
Route::get('/calendar/{id}', [FullCalendarController::class, 'usercalendar'])->name('calendar')->where('id', '[0-9]+');


Route::get('/userweekview', [DashboardController::class, 'userweekview']);
Route::post('getDay', [TaskController::class, 'getDay'])->name('getDay');
Route::get('addTask', [TaskController::class, 'addTask'])->name('addTask');

Route::post('getData', [TaskController::class, 'getData'])->name('getData');
Route::get('monthlyView', [TaskController::class, 'monthlyView'])->name('monthlyView');

Route::post('getWeek', [TaskController::class, 'getWeek'])->name('getWeek');
Route::get('weekView', [TaskController::class, 'weekView'])->name('weekView');

Route::post('getTaskId', [TaskController::class, 'getTaskId'])->name('getTaskId');

Route::post('getKeyword', [TaskController::class, 'getKeyword'])->name('getKeyword');
Route::get('filteredView', [TaskController::class, 'filteredView'])->name('filteredView');

Route::get('/editTask/{id}', [TaskController::class, 'editTask'])->name('editTask');
Route::get('/updateTask/{id}', [TaskController::class, 'updateTask'])->name('updateTask');

Route::get('/reportView/{created_at}', [ReportViewController::class, 'reportView'])->name('reportView');
Route::get('/pdf', [ReportViewController::class, 'pdf'])->name('pdf');

//--------------------------------Setting-----------------------------
Route::resource('/settings/teamsetting', TeamSettingController::class);
Route::resource('/settings/progress-update-setting', ProgressUpdateSettingController::class);
//Route::get('/setting/progress-update-setting', [SettingController::class, 'progressUpdateSetting'])->name('progress-update-setting');
//Route::get('/setting/role-access-setting', [SettingController::class, 'roleAccessSetting'])->name('role-access-setting');

