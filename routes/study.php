<?php

Route::group(['namespace' => 'Study', 'prefix' => 'study'],function (){

    Route::group(['middleware'=>'auth'],function (){
        Route::get('add_word', 'StudyController@create')->name('create');
        Route::post('store', 'StudyController@store')->name('store');
        Route::get('import','StudyController@import')->name('import');
        Route::post('save','StudyController@save')->name('save')->middleware('excel');
        Route::post('store_user_words','StudyController@reserve')->name('reserve');
        Route::get('notes',function (){
            return view('study.learning.notes');
        })->name('notes');
        Route::get('get_user_notes','StudyController@getUserNotes')->name('getUserNotes');
        Route::delete('destroy','StudyController@destroy')->name('destroy');
        Route::put('update','StudyController@update')->name('update');
    });

    Route::get('show','StudyController@show')->name('show');
    Route::get('list','StudyController@list')->name('list');
});
