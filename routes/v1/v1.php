<?php

use App\Http\Controllers\v1\Auth\LoginController;
use App\Http\Controllers\v1\Auth\LogOutController;
use App\Http\Controllers\v1\DashBoard\DashboardController;
use App\Http\Controllers\v1\Employee\RegisterEmployeeController;
use App\Http\Controllers\v1\Employee\ShowEmployeeController;
use App\Http\Controllers\v1\Employee\UpdateEmployeeController;
use App\Http\Controllers\v1\HourSession\DeleteHourSession\DeleteHourSessionController;
use App\Http\Controllers\v1\HourSession\RegisterHourSession\RegitsterHourSessionController;
use App\Http\Controllers\v1\HourSession\ShowHourSession\ShowHourSessionController;
use App\Http\Controllers\v1\HourSession\UpdateHourSession\UpdateHourSessionController;
use App\Http\Controllers\v1\Salary\ShowSalaryByMonthController;
use App\Http\Controllers\v1\User\ChangePasswordController;
use App\Http\Controllers\v1\User\DeleteUserController;
use App\Http\Controllers\v1\User\ShowUserController;
use App\Http\Controllers\v1\User\UpdateEmailController;
use App\Http\Controllers\v1\User\UpdateUserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterEmployeeController::class)->middleware('throttle:60,1');
Route::post('/login', LoginController::class)->middleware('throttle:60,1');

Route::middleware(['throttle:60,1', 'jwt.auth', 'token_redis', 'role:employee', 'is_active'])->group(function () {
    //User Routes
    Route::put('/user/update', UpdateEmailController::class);
    Route::get('/user/show', ShowUserController::class);
    Route::post('user/delete', DeleteUserController::class);
    Route::put('/user/change_password', ChangePasswordController::class);
    //Employee Routes
    Route::get('/employee', ShowEmployeeController::class);
    Route::put('/employee', UpdateEmployeeController::class);

    //HourSession Routes
    Route::post('/hour_session', RegitsterHourSessionController::class);  // Crear
    Route::get('/hour_session', ShowHourSessionController::class);       // Leer
    Route::put('/hour_session', UpdateHourSessionController::class);     // Actualizar
    Route::delete('/hour_session', DeleteHourSessionController::class);  // Eliminar

    Route::get('/dashboard', DashboardController::class);

    //Salary Routes
    Route::get('/salary', ShowSalaryByMonthController::class);
    //Logout
    Route::post('/logout', LogOutController::class);

});
