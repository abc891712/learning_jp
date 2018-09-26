<?php

Route::group(['namespace' => 'Study', 'prefix' => 'study'],function (){

    Route::get('/', 'StudyController@index');


});
