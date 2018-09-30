<?php

Route::group(['namespace' => 'Study', 'prefix' => 'study'/*, 'middleware' => 'auth'*/],function (){

    Route::get('add_word', 'StudyController@create')->name('create');
    Route::post('store', 'StudyController@store')->name('store');
});
