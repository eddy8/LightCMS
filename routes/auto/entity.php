<?php

Route::get('/entities', 'EntityController@index')->name('entity.index');
Route::get('/entities/list', 'EntityController@list')->name('entity.list');
Route::get('/entities/create', 'EntityController@create')->name('entity.create');
Route::post('/entities', 'EntityController@save')->name('entity.save');
Route::get('/entities/{id}/edit', 'EntityController@edit')->name('entity.edit');
Route::put('/entities/{id}', 'EntityController@update')->name('entity.update');
Route::delete('/entities/{id}', 'EntityController@delete')->name('entity.delete');
