<?php

Route::get('/entityFields', 'EntityFieldController@index')->name('entityField.index');
Route::get('/entityFields/list', 'EntityFieldController@list')->name('entityField.list');
Route::get('/entityFields/create', 'EntityFieldController@create')->name('entityField.create');
Route::post('/entityFields', 'EntityFieldController@save')->name('entityField.save');
Route::get('/entityFields/{id}/edit', 'EntityFieldController@edit')->name('entityField.edit');
Route::put('/entityFields/{id}', 'EntityFieldController@update')->name('entityField.update');
Route::delete('/entityFields/{id}', 'EntityFieldController@delete')->name('entityField.delete');
