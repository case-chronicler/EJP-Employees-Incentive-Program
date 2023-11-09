<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\InviteController;

use App\Http\Controllers\IncentiveGiftController;
use App\Http\Controllers\WithdrawalsController;
use App\Http\Controllers\WithdrawalRequestsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'appVersion' => "0.0.1",
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('employee', EmployeeController::class)
    ->only(['index', 'store', 'update'])
    ->middleware(['auth', 'verified']);
    
Route::resource('role', RoleController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('position', PositionController::class)
    ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

Route::resource('invite', InviteController::class)
    ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

Route::resource('Incentive_gift', IncentiveGiftController::class)
    ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

Route::resource('withdrawals', WithdrawalsController::class)
    ->only(['index', 'store', 'update'])
    ->middleware(['auth', 'verified']);    

Route::middleware('auth')->group(function () {
    Route::post('/withdrawal_requests', [WithdrawalRequestsController::class, 'store'])->name('withdrawal_requests.store');

    Route::get('/withdrawal_requests/{withdrawal_request_id}', [WithdrawalRequestsController::class, 'show'])->name('withdrawal_requests.show');
    Route::patch('/withdrawal_requests/{withdrawal_request_id}', [WithdrawalRequestsController::class, 'update'])->name('withdrawal_requests.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
