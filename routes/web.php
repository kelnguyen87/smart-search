<?php

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


Route::group(['middleware' => 'auth.shop'], function () {

    Route::get('/', 'ReportController@getDashboard')->name('home');
    Route::get('report/chartData','ReportController@getProductChartData');
    Route::get('setting','SettingController@index');
    Route::post('setting/save','SettingController@save');
    Route::get('help','SettingController@getHelp');

});
