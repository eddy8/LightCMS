<?php

Route::get('/SensitiveWords', 'SensitiveWordController@index')->name('SensitiveWord.index');
Route::get('/SensitiveWords/list', 'SensitiveWordController@list')->name('SensitiveWord.list');
Route::get('/SensitiveWords/create', 'SensitiveWordController@create')->name('SensitiveWord.create');
Route::post('/SensitiveWords', 'SensitiveWordController@save')->name('SensitiveWord.save');
Route::get('/SensitiveWords/{id}/edit', 'SensitiveWordController@edit')->name('SensitiveWord.edit');
Route::put('/SensitiveWords/{id}', 'SensitiveWordController@update')->name('SensitiveWord.update');
Route::delete('/SensitiveWords/{id}', 'SensitiveWordController@delete')->name('SensitiveWord.delete');
