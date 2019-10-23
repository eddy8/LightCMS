<?php

Route::get('/tags', 'TagController@index')->name('tag.index');
Route::get('/tags/list', 'TagController@list')->name('tag.list');
Route::get('/tags/create', 'TagController@create')->name('tag.create');
Route::post('/tags', 'TagController@save')->name('tag.save');
Route::get('/tags/{id}/edit', 'TagController@edit')->name('tag.edit');
Route::put('/tags/{id}', 'TagController@update')->name('tag.update');
Route::delete('/tags/{id}', 'TagController@delete')->name('tag.delete');
