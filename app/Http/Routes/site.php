<?php

Route::resource('test', 'TestController');
// Route::get('mixdb/{typeId}', 'TestController@mixdb');

Route::post('bookpaging', 'SiteController@bookpaging');
Route::post('errorreporting', 'SiteController@errorreporting');
Route::post('contact', 'SiteController@contact');
Route::get('sitemap.xml', 'SiteController@sitemap');
Route::get('livesearch', 'SiteController@livesearch');
Route::get('tim-kiem', ['uses' => 'SiteController@search', 'as' => 'site.search']);
Route::get('/', 'SiteController@index');
Route::get('the-loai/{slug}', 'SiteController@type');
Route::get('seri/{slug}', 'SiteController@seri');
Route::get('tac-gia', 'SiteController@author');
Route::get('tac-gia/{slug}', 'SiteController@tag');
Route::get('doc-truyen-{slug}', 'SiteController@nation');
Route::get('danh-sach-truyen-{slug}', 'SiteController@kind');
Route::get('{slug1}/{slug2}', 'SiteController@page2');
Route::get('{slug}', 'SiteController@page');
