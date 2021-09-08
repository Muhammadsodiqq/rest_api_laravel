<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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
    return view('welcome');
});

Route::get('add-to-log/{admin}', [HomeController::class,"myTestAddToLog"]);
Route::get("logActivity",[HomeController::class,'logActivity']);

Route::post('sendMessage', [HomeController::class,"run"]);

Route::get("form", [HomeController::class,"index"]);


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
