<?php
/**
 * Date: 2019/2/25 Time: 9:31
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

Route::group(
    [
        'as' => 'admin::',
    ],
    function () {
        Route::get('/login', 'Auth\LoginController@showLogin')->name('login.show');
        Route::post('/login', 'Auth\LoginController@login')->name('login');

        Route::middleware('auth:admin')->group(function () {
            Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

            Route::get('/index', 'HomeController@showIndex')->name('index.show');

            // 管理员用户管理
            Route::get('/admin_user/index', 'AdminUserController@index')->name('adminUser.index');
            Route::get('/admin_user/list', 'AdminUserController@list')->name('adminUser.list');
            Route::get('/admin_user/add', 'AdminUserController@showAdd')->name('adminUser.add.show');
            Route::post('/admin_user/add', 'AdminUserController@add')->name('adminUser.add');
        });
    }
);
