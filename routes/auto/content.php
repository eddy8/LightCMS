<?php

Route::get('/entity/{entity}/contents', 'ContentController@index')->name('content.index');
Route::get('/entity/{entity}/contents/list', 'ContentController@list')->name('content.list');
Route::get('/entity/{entity}/contents/create', 'ContentController@create')->name('content.create');
Route::post('/entity/{entity}/contents', 'ContentController@save')->name('content.save');
Route::get('/entity/{entity}/contents/{id}/edit', 'ContentController@edit')->name('content.edit');
Route::put('/entity/{entity}/contents/{id}', 'ContentController@update')->name('content.update');
Route::delete('/entity/{entity}/contents/{id}', 'ContentController@delete')->name('content.delete');
