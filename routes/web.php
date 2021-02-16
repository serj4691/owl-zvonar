<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');

Route::get('/bot', 'TelegramController@bot');
Route::post('/bot', 'TelegramController@bot');

Route::get('/vox', 'VoxController@vox');
Route::post('/api/user_id', 'ApiController@userIdLead');

Route::post('/api/callcenter/{id}/lead', 'ApiController@createLead');

Route::post('/test', 'TelegramController@test');

Route::get('/api/lead_ids/{id}', 'HomeController@leadIds');
Route::get('/api/complexes', 'HomeController@complexes');

Route::get('/test/excel', function (Request $request) {
    Log::debug($request->all());
});

//Route::post('/customer_phone/import', 'CustomerPhoneController@import');
