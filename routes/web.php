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
use App\Http\Controllers\RemoteController;
use App\Http\Controllers\ManagerRemoteController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\OvertimeController;
use App\Models\Overtime;

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

    //home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('timesheet/home', [HomeController::class, 'index'])->name('timesheet.home');
    Route::get('timesheet/manage', [HomeController::class, 'manage'])->name('timesheet.manage')->middleware('can:viewAny,App\Models\Timesheet');
    Route::post('timesheet/exporter', [HomeController::class, 'export'])->name('timesheet.export')->middleware('can:viewAny,App\Models\Timesheet');
    Route::post('timesheet/import', [HomeController::class, 'import'])->name('timesheet.import')->middleware('can:viewAny,App\Models\Timesheet');

    //Check-in check-out
    Route::post('/inout/checkin', [InOutController::class, 'checkIn'])->name('inout.checkin');
    Route::post('/inout/checkout', [InOutController::class, 'checkOut'])->name('inout.checkout');

    //manager staff 
    Route::get('manager_staff', [ManagerStaffController::class, 'index'])->name('manager_staff.index')->middleware('can:viewAny,App\Models\User');
    Route::get('manager_staff/create', [ManagerStaffController::class, 'create'])->name('manager_staff.create')->middleware('can:create,App\Models\User');
    Route::post('manager_staff', [ManagerStaffController::class, 'store'])->name('manager_staff.store');
    Route::get('manager_staff/{id}/edit', [ManagerStaffController::class, 'edit'])->name('manager_staff.edit')->middleware('can:update,App\Models\User');
    Route::put('manager_staff/{id}', [ManagerStaffController::class, 'update'])->name('manager_staff.update')->middleware('can:update,App\Models\User');
    Route::delete('manager_staff/{id}', [ManagerStaffController::class, 'destroy'])->name('manager_staff.destroy')->middleware('can:delete,App\Models\User');

    //registration_form
    //remote
    Route::get('remote', [RemoteController::class, 'index'])->name('remote.index');
    Route::get('remote/create', [RemoteController::class, 'create'])->name('remote.create');
    Route::post('remote', [RemoteController::class, 'store'])->name('remote.store');
    Route::get('remote/{id}/edit', [RemoteController::class, 'edit'])->name('remote.edit');
    Route::put('remote/{id}', [RemoteController::class, 'update'])->name('remote.update');
    Route::put('remote/cancle/{id}', [RemoteController::class, 'cancel'])->name('remote.cancel');
    //manager remote
    Route::get('manager_remote', [ManagerRemoteController::class, 'index'])->name('manager_remote.index')->middleware('can:viewAny,App\Models\Remote');
    Route::get('manager_remote/{id}/edit', [ManagerRemoteController::class, 'edit'])->name('manager_remote.edit')->middleware('can:update,App\Models\Remote');
    Route::put('manager_remote/approve/{id}', [ManagerRemoteController::class, 'approve'])->name('manager_remote.approve')->middleware('can:update,App\Models\Remote');

    //user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/change_password/{user}/password', [UserController::class, 'password'])->name('users.password');
    Route::put('/change_password/{user}', [UserController::class, 'change_password'])->name('users.change_password');

    //in out forgets
    Route::get('/in_out_forgets', [InOutForgetController::class, 'index'])->name('in_out_forgets.index');
    Route::get('/in_out_forgets/create', [InOutForgetController::class, 'create'])->name('in_out_forgets.create');
    Route::post('/in_out_forgets', [InOutForgetController::class, 'store'])->name('in_out_forgets.store');
    Route::get('/in_out_forgets/{in_out_forget}', [InOutForgetController::class, 'show'])->name('in_out_forgets.show');
    Route::get('/in_out_forgets/{in_out_forget}/edit', [InOutForgetController::class, 'edit'])->name('in_out_forgets.edit');
    Route::put('/in_out_forgets/{in_out_forget}', [InOutForgetController::class, 'update'])->name('in_out_forgets.update');
    Route::delete('/in_out_forgets/{in_out_forget}', [InOutForgetController::class, 'destroy'])->name('in_out_forgets.destroy');
    //leaves
    Route::resource('leaves', LeaveController::class);

    //setting 
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('can:update,App\Models\Setting');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('can:update,App\Models\Setting');

    //holidays
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index')->middleware('can:update,App\Models\Holiday');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store')->middleware('can:create,App\Models\Holiday');
    Route::get('/holidays/{id}/edit', [HolidayController::class, 'edit'])->name('holidays.edit')->middleware('can:update,App\Models\Holiday');
    Route::put('/holidays/{id}', [HolidayController::class, 'update'])->name('holidays.update')->middleware('can:update,App\Models\Holiday');
    Route::post('/holidays/import', [HolidayController::class, 'import'])->name('holidays.import')->middleware('can:update,App\Models\Holiday');
    Route::delete('/holidays/{id}', [HolidayController::class, 'destroy'])->name('holidays.destroy')->middleware('can:delete,App\Models\Holiday');
    Route::post('/holidays/multi_delete', [HolidayController::class, 'delete'])->name('holidays.multi_delete')->middleware('can:delete,App\Models\Holiday');
    Route::post('/holidays/export', [HolidayController::class, 'export'])->name('holidays.export')->middleware('can:update,App\Models\Holiday');
    Route::get('/holidays/calendar', [HolidayController::class, 'calendar'])->name('holidays.calendar')->middleware('can:view,App\Models\Holiday');

    //overtimes
    Route::get('/overtimes', [OvertimeController::class, 'index'])->name('overtimes.index');
    Route::get('/overtimes/create', [OvertimeController::class, 'create'])->name('overtimes.create');
    Route::post('/overtimes', [OvertimeController::class, 'store'])->name('overtimes.store');
    Route::put('/overtimes/cancel/{id}', [OvertimeController::class, 'cancel'])->name('overtimes.cancel')->middleware('can:delete,App\Models\Overtime,id');
    Route::get('/overtimes/edit/{id}', [OvertimeController::class, 'edit'])->name('overtimes.edit')->middleware('can:update,App\Models\Overtime,id');
    Route::put('/overtimes/update/{id}', [OvertimeController::class, 'update'])->name('overtimes.update')->middleware('can:update,App\Models\Overtime,id');
    Route::get('/overtimes/manage', [OvertimeController::class, 'manage'])->name('overtimes.manage')->middleware('can:viewAny,App\Models\Overtime');
    Route::get('/overtimes/approve/{id}', [OvertimeController::class, 'approve'])->name('overtimes.approve')->middleware('can:approve,App\Models\Overtime');
    Route::put('/overtimes/approve/{id}', [OvertimeController::class, 'approveAction'])->name('overtimes.approveAction')->middleware('can:approve,App\Models\Overtime');
    Route::get('/overtimes/details/{id}', [OvertimeController::class, 'details'])->name('overtimes.details')->middleware('can:details,App\Models\Overtime,id');
});

//password mail
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->middleware('verifyResetToken')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
