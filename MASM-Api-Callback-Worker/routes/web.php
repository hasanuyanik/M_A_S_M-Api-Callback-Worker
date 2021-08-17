<?php


use Illuminate\Support\Facades\Route;

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

    \App\Models\Endpoints::create(["name" => "ios", "endpoint" => "ios"]);
    \App\Models\Endpoints::create(["name" => "android", "endpoint" => "android"]);
    \App\Models\Endpoints::create(["name" => "callback", "endpoint" => "callback"]);

    return view('welcome');
});


