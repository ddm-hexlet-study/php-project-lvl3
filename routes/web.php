<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UrlCheckController;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

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
    return view('main');
})->name('index');

Route::post('/urls/{id}/checks', UrlCheckController::class)->name('urls.check');

Route::resource('urls', UrlController::class);
