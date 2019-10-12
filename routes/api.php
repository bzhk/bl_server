<?php

use Illuminate\Http\Request;

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

Route::middleware('apiauth')->get('coupons/list', 'Coupons@getList');
Route::middleware('apiauth')->get('coupons/{id}', 'Coupons@getItem');

Route::middleware('apiauth')->get('tags/list', 'Tags@getList');

Route::middleware('apiauth')->get('points/list', 'Points@getList');
Route::middleware('apiauth')->get('points/{id}', 'Points@getItem');



