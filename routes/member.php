<?php
/**
 * Date: 2019/2/25 Time: 9:31
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

use Illuminate\Support\Str;

Route::group(
    [
        'as' => 'member::',
    ],
    function () {
        Route::get('/login', 'UserController@showLogin')->name('login.show');
        Route::post('/login', 'UserController@login')->name('login');

        Route::middleware('auth:member')->group(function () {
            Route::get('/logout', 'UserController@logout')->name('logout');
        });
    }
);
