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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {
	Route::resource('categories', 'CategoryController')->names('category');
	Route::get('quotation', 'QuotationController@index');
	Route::post('get_items_from_the_category', 'QuotationController@get_items_from_the_category')->name('get_items_from_the_category');
	Route::post('add_items_from_ajax', 'QuotationController@add_items_from_ajax')->name('add_data');
	Route::get('delete_item_from_the_category', 'QuotationController@delete_item_from_the_category')->name('delete_item_from_the_category');
	Route::get('send-email-pdf', 'QuotationController@send_mail')->name('send_mail');
});