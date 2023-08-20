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

Route::middleware(['auth'])->group(function () {

    //manager staff ->middleware('can:viewAny,App\Models\Category');
    Route::get('manager_staff', [ManagerStaffController::class, 'index'])->name('manager_staff.index')->middleware('can:viewAny,App\Models\User');
    Route::get('manager_staff/create', [ManagerStaffController::class, 'create'])->name('manager_staff.create')->middleware('can:create,App\Models\User');
    Route::post('manager_staff', [ManagerStaffController::class, 'store'])->name('manager_staff.store');
    Route::get('manager_staff/{id}/edit', [ManagerStaffController::class, 'edit'])->name('manager_staff.edit')->middleware('can:update,App\Models\User');
    Route::put('manager_staff/{id}', [ManagerStaffController::class, 'update'])->name('manager_staff.update')->middleware('can:update,App\Models\User');
    Route::delete('manager_staff/{id}', [ManagerStaffController::class, 'destroy'])->name('manager_staff.destroy')->middleware('can:delete,App\Models\User');

    //user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/change_password/{user}/password', [UserController::class, 'password'])->name('users.password');
    Route::put('/change_password/{user}', [UserController::class, 'change_password'])->name('users.change_password');


    //in out forgets
    Route::resource('in_out_forgets', InOutForgetController::class);

    //leaves
    Route::resource('leaves', LeaveController::class);

    //setting 
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
//home
Route::get('/', [HomeController::class, 'index'])->name('home');

//password mail
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->middleware('verifyResetToken')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
