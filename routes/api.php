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

Route::middleware('apiauth')->post('login', 'Users@getContent');

Route::middleware('apiauth')->get('history', 'History@getList');

Route::middleware('apiauth')->get('coupons/list', 'Coupons@getList');
Route::middleware('apiauth')->get('coupons/{id}', 'Coupons@getItem');
Route::middleware('apiauth')->post('coupons/unlock/{id}', 'Coupons@unlockItem');

Route::middleware('apiauth')->get('tags/list', 'Tags@getList');

Route::middleware('apiauth')->get('points', 'Points@getFilteredList');
Route::middleware('apiauth')->get('points/list', 'Points@getList');
Route::middleware('apiauth')->get('points/{id}', 'Points@getItem');




