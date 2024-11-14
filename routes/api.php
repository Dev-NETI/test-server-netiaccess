<?php

use App\Http\Controllers\Backend\AuthenticateTraineeController;
use App\Http\Controllers\IOS_API\CoursesController;
use App\Http\Controllers\IOS_API\EnrolledController;
use App\Http\Controllers\IOS_API\TraineeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::post('authenticate-trainee' , AuthenticateTraineeController::class);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
