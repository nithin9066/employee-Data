<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'employee.index');
Route::get('/get-employees-details', [userController::class, 'index']);
Route::post('/add', [userController::class, 'store']);
Route::get('/edit/{id}', [userController::class, 'edit']);
Route::patch('/update', [userController::class, 'update']);
Route::delete('/delete', [userController::class, 'delete']);


