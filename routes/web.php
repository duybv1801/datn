<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerStaffController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InOutForgetController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\SettingController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

//home
Route::get('/', [HomeController::class, 'index'])->name('home');

//user
Route::resource('users', UserController::class)->middleware('auth');

//manager staff
Route::resource('manager_staff',ManagerStaffController::class)->middleware('auth');

//in out forgets
Route::resource('in_out_forgets', InOutForgetController::class)->middleware('auth');

//leaves
Route::resource('leaves', LeaveController::class)->middleware('auth');

//password mail
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

//setting 
Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});