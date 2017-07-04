<?php
Route::group(['middleware' => ['auth:admin']], function ($router) {
    // Route::get('/', ['uses' => 'AdminController@index', 'as' => 'admin.index']);
    Route::get('/', 'PostController@index');
    //account
    Route::post('account/updateStatus', 'AccountController@updateStatus');
    Route::get('account/{id}/password', ['uses' => 'AccountController@password', 'as' => 'admin.account.password']);
    Route::post('account/{id}/password', ['uses' => 'AccountController@doPassword', 'as' => 'admin.account.password']);
    Route::resource('account', 'AccountController');
    //menu
    Route::post('menu/updateParentIdSelectBox', 'MenuController@updateParentIdSelectBox');
    Route::post('menu/updateStatus', 'MenuController@updateStatus');
    Route::post('menu/callupdate', 'MenuController@callupdate');
    Route::resource('menu', 'MenuController');
    //post tag
    Route::post('posttag/updateStatus', 'PostTagController@updateStatus');
    Route::resource('posttag', 'PostTagController');
    //post type
    Route::post('posttype/updateStatus', 'PostTypeController@updateStatus');
    Route::post('posttype/callupdate', 'PostTypeController@callupdate');
    Route::resource('posttype', 'PostTypeController');
    //post seri
    Route::post('postseri/updateStatus', 'PostSeriController@updateStatus');
    Route::post('postseri/callupdate', 'PostSeriController@callupdate');
    Route::resource('postseri', 'PostSeriController');
    //post ep chap
    Route::post('postep/calldelete', 'PostEpController@calldelete');
    Route::post('postep/callupdatestatus', 'PostEpController@callupdatestatus');
    Route::post('postep/updateStatus', 'PostEpController@updateStatus');
    Route::post('postep/callupdate', 'PostEpController@callupdate');
    Route::get('postep/search', ['uses' => 'PostEpController@search', 'as' => 'admin.postep.search']);
    Route::resource('postep', 'PostEpController');
    //post
    Route::post('post/callupdatetype', 'PostController@callupdatetype');
    Route::post('post/calldelete', 'PostController@calldelete');
    Route::post('post/callupdatestatus', 'PostController@callupdatestatus');
    Route::post('post/updateStatus', 'PostController@updateStatus');
    Route::get('post/search', ['uses' => 'PostController@search', 'as' => 'admin.post.search']);
    Route::resource('post', 'PostController');
    //ads
    Route::post('ad/updateStatus', 'AdController@updateStatus');
    Route::resource('ad', 'AdController');
    //config
    Route::resource('config', 'ConfigController');
    //clear all cache & views
    Route::get('clearallstorage', 'AdminController@clearallstorage');
    //page
    Route::post('page/updateStatus', 'PageController@updateStatus');
    Route::resource('page', 'PageController');
    //contact
    Route::post('contact/calldelete', 'ContactController@calldelete');
    Route::post('contact/callupdatestatus', 'ContactController@callupdatestatus');
    Route::resource('contact', 'ContactController');
    //slider
    Route::post('slider/updateStatus', 'SliderController@updateStatus');
    Route::post('slider/callupdate', 'SliderController@callupdate');
    Route::resource('slider', 'SliderController');
    //crawler
    Route::get('genwatermark', 'CrawlerController@genwatermark');
    Route::post('genwatermarkAction', 'CrawlerController@genwatermarkAction');
    Route::get('genthumb', 'CrawlerController@genthumb');
    Route::post('crawler/save', 'CrawlerController@save');
    Route::post('crawler/steal', 'CrawlerController@steal');
    Route::resource('crawler', 'CrawlerController');
    // crawler 2 for novel
    Route::resource('crawler2', 'Crawler2Controller');
});
Route::get('login', ['uses' => 'AuthController@index', 'as' => 'admin.auth.index']);
Route::post('login', ['uses' => 'AuthController@login', 'as' => 'admin.auth.login']);
Route::get('logout', ['uses' => 'AuthController@logout', 'as' => 'admin.auth.logout']);
// Route::get('password/reset/{token?}', ['uses' => 'PasswordController@showResetForm', 'as' => 'admin.password.reset']);
// Route::post('password/reset', ['uses' => 'PasswordController@reset', 'as' => 'admin.password.reset']);
// Route::post('password/email', ['uses' => 'PasswordController@sendResetLinkEmail', 'as' => 'admin.password.email']);
