<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PagesController@index');
Route::get('unit/{id}', 'PagesController@unit');
Route::get('quest/{id}', 'PagesController@quest');
Route::get('area/{id}', 'PagesController@area');
Route::get('skill/{type}', 'PagesController@skill');
Route::get('unitlist', 'PagesController@unitlist');
Route::get('questlist', 'PagesController@questlist');
Route::get('voteresult', 'PagesController@voteResult');
Route::get('mark/{type}', 'PagesController@mark');
Route::get('clearCache', 'PagesController@clearCache');
Route::get('rank', 'PagesController@rank');
Route::get('story', 'PagesController@story');
Route::get('test', 'PagesController@test');
// Translate
Route::post('api/translate/unit/{id}', 'TranslateController@unit');
Route::post('api/translate/quest/{id}', 'TranslateController@quest');

// API
Route::get('api/area/{id}', 'ApiController@area')->middleware('cors');
Route::get('api/quest/{id}', 'ApiController@quest')->middleware('cors');
Route::get('api/questlist', 'ApiController@questlist')->middleware('cors');
Route::get('api/rank', 'ApiController@rank')->middleware('cors');
Route::get('api/skill/{type}', 'ApiController@skill')->middleware('cors');
Route::get('api/story', 'ApiController@story')->middleware('cors');
Route::get('api/unit/{id}', 'ApiController@unit')->middleware('cors');
Route::get('api/unitlist', 'ApiController@unitlist')->middleware('cors');
Route::get('api/voteresult/{id}', 'ApiController@voteResult')->middleware('cors');

// update
Route::get('update', function () { return view('update.index'); })->middleware('update');
//Route::post('update/dlData', 'UpdateController@dlData')->middleware('update');      // checksum error
Route::post('update/update', 'UpdateController@update')->middleware('update');
Route::get('update/show_asset', function () { return view('update.show_asset'); })->middleware('update');
Route::get('update/show_gacha', function () { return view('update.show_gacha'); })->middleware('update');
Route::get('update/show_toppage', function () { return view('update.show_toppage'); })->middleware('update');
Route::get('update/split_img', function () { return view('update.split_img'); })->middleware('update');
Route::post('update/split_img', 'UpdateController@splitImg' )->middleware('update');
Route::get('update/split_map', function () { return view('update.split_map'); })->middleware('update');
Route::post('update/split_map', function () { return view('update.split_map'); })->middleware('update');
