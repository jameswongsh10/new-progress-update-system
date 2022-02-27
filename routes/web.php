<?php

use App\Http\Controllers\DailyReportSettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportViewController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamSettingController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserMonthlyViewController;
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

//------------------------------- Dashboard --------------------------
Route::get('/dashboard', [DashboardController::class, 'admindashboard'])->name('dashboard');
Route::get('/teamview/{id}', [DashboardController::class, 'teamview'])->name('teamview')->where('id', '[0-9]+');
Route::get('/calendar/{id}', [FullCalendarController::class, 'usercalendar'])->name('calendar')->where('id', '[0-9]+');
Route::resource('userdashboard', UserDashboardController::class);

//-------------------- Daily Report View --------------------------
Route::get('/dailyreport', [ReportController::class, 'create'])->name('dailyreport');
Route::post('/dailyreport', [ReportController::class, 'store'])->name("dailyreportstore");
Route::get('/dailyreport/edit', [ReportController::class, 'edit'])->name('dailyreportedit');
Route::put('/dailyreport/edit', [ReportController::class, 'update'])->name('dailyreportupdate');
Route::get('/reportView/{created_at}', [ReportViewController::class, 'reportView'])->name('reportView');
Route::get('/pdf', [ReportViewController::class, 'pdf'])->name('pdf');

//------------------------- Week and Month View, together with data filtration route-------------------------------
Route::get('/usermonthview', [UserMonthlyViewController::class, 'usermonthview'])->name('usermonthview');
Route::get('/filteredUserView', [UserMonthlyViewController::class, 'filteredUserView'])->name('filteredUserView');
Route::get('monthlyView', [TaskController::class, 'monthlyView'])->name('monthlyView');
Route::get('weekView', [TaskController::class, 'weekView'])->name('weekView');
Route::get('filteredView', [TaskController::class, 'filteredView'])->name('filteredView');

//------------------------------ Add and edit task -----------------------
Route::get('addTask', [TaskController::class, 'addTask'])->name('addTask');
Route::get('/editTask/{id}', [TaskController::class, 'editTask'])->name('editTask');
Route::get('/updateTask/{id}', [TaskController::class, 'updateTask'])->name('updateTask');

//------------------------------ Ajax route ----------------------------
Route::post('getTaskId', [TaskController::class, 'getTaskId'])->name('getTaskId');
Route::post('getKeyword', [TaskController::class, 'getKeyword'])->name('getKeyword');
Route::post('getWeek', [TaskController::class, 'getWeek'])->name('getWeek');
Route::post('getData', [TaskController::class, 'getData'])->name('getData');
Route::post('getDay', [TaskController::class, 'getDay'])->name('getDay');

//--------------------------------Setting-----------------------------
Route::resource('/settings/teamsetting', TeamSettingController::class);
Route::resource('/settings/daily-report-setting', DailyReportSettingController::class);
Route::resource('/settings/status-setting', StatusController::class);

//-------------------------------User management -------------------------
Route::resource('users', UserManagementController::class);
