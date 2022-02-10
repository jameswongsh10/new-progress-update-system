<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamSettingController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FullCalendarController;

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
Route::get('/', [LoginController::class, 'login']);
Route::post('/', [LoginController::class, 'check'])->name('check');

//----------------- User Management -----------------

Route::resource('users', UserManagementController::class);
Route::resource('teamsetting', TeamSettingController::class);
//Route::resource('monthview', TaskController::class);
//Route::get('/users', [DashboardController::class, 'displayUsers'])->name('users');
Route::get('/users/adduser', [DashboardController::class, 'addUser'])->name('adduser');
//Route::post('/users/adduser', [DashboardController::class, 'addUserCheck'])->name('addusercheck');
//Route::get('/users/{id}/edit', [DashboardController::class, 'getUser'])->where('id', '[0-9]+');




Route::get('/dashboard', [DashboardController::class, 'admindashboard'])->name('dashboard');
//
Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
Route::get('/teamview/{id}', [DashboardController::class, 'teamview'])->name('teamview')->where('id', '[0-9]+');
Route::get('/calendar/{id}', [FullCalendarController::class, 'usercalendar'])->name('calendar')->where('id', '[0-9]+');
//Route::post('/calendar', [FullCalendarController::class, 'admincalendar'])->name('calendar');
//
////edit user
//
//
//

Route::get('/userweekview', [DashboardController::class, 'userweekview']);
Route::get('/addtask', [DashboardController::class, 'addtask']);

Route::post('getData', [TaskController::class, 'getData'])->name('getData');
Route::get('monthlyView', [TaskController::class, 'monthlyView'])->name('monthlyView');

Route::get('/weekview/{id}', [TaskController::class, 'weekview'])->name('weekview')->where('id', '[0-9]+');


/*
//do routing later for this
Route::get('/edituser', [MainController::class, 'edituser']);
*/
