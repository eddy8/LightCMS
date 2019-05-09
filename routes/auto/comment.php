<?php

Route::get('/comments', 'CommentController@index')->name('comment.index');
Route::get('/comments/list', 'CommentController@list')->name('comment.list');
//Route::get('/comments/create', 'CommentController@create')->name('comment.create');
//Route::post('/comments', 'CommentController@save')->name('comment.save');
Route::get('/comments/{id}/edit', 'CommentController@edit')->name('comment.edit');
Route::put('/comments/{id}', 'CommentController@update')->name('comment.update');
Route::delete('/comments/{id}', 'CommentController@delete')->name('comment.delete');
