<?php

Route::get('/categories', 'CategoryController@index')->name('category.index');
Route::get('/categories/list', 'CategoryController@list')->name('category.list');
Route::get('/categories/create', 'CategoryController@create')->name('category.create');
Route::post('/categories', 'CategoryController@save')->name('category.save');
Route::get('/categories/{id}/edit', 'CategoryController@edit')->name('category.edit');
Route::put('/categories/{id}', 'CategoryController@update')->name('category.update');
