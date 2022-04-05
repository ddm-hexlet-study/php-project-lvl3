<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UrlController;
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

Route::get('/', function (Request $request) {
    return view('main');
});

Route::get('/urls', [UrlController::class, 'showUrls']);

Route::get('/urls/{id}', [UrlController::class, 'getUrl'])->name('url');

Route::post('/urls', [UrlController::class, 'addUrl'])->name('store');

Route::post('/url/{id}/checks', [UrlController::class, 'checkUrl'])->name('check');
