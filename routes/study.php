<?php

Route::group(['namespace' => 'Study', 'prefix' => 'study'/*, 'middleware' => 'auth'*/],function (){

    Route::get('add_word', 'StudyController@create')->name('create');
    Route::post('store', 'StudyController@store')->name('store');
    Route::get('import','StudyController@import')->name('import');
    Route::post('save','StudyController@save')->name('save')->middleware('excel');
    Route::get('show','StudyController@show')->name('show');
    Route::get('list','StudyController@list')->name('list');
    Route::post('store_user_words','StudyController@reserve')->name('reserve');
});
