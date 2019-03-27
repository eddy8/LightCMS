<?php

Route::get('/templates', 'TemplateController@index')->name('template.index');
Route::get('/templates/list', 'TemplateController@list')->name('template.list');
Route::get('/templates/create', 'TemplateController@create')->name('template.create');
Route::post('/templates', 'TemplateController@save')->name('template.save');
Route::get('/templates/{id}/edit', 'TemplateController@edit')->name('template.edit');
Route::put('/templates/{id}', 'TemplateController@update')->name('template.update');
Route::delete('/templates/{id}', 'TemplateController@delete')->name('template.delete');
