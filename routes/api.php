<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmployeePageDataController;
use App\Http\Controllers\EmployeeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/incentives', [EmployeePageDataController::class, 'incentives'])->name('incentivesOnly');
Route::post('/transactions', [EmployeePageDataController::class, 'transactions'])->name('transactionsOnly');

Route::post('/employee-status', [EmployeePageDataController::class, 'employeeStatus'])->name('employeeStatus');
// Route::middleware('auth')->group(function () {    
// });