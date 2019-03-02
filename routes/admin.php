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

        Route::middleware('auth:admin', 'authorization:admin')->group(function () {
            Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

            Route::get('/index', 'HomeController@showIndex')->name('index');

            Route::get('/test', 'HomeController@test')->name('test');

            // 管理员用户管理
            Route::get('/admin_users', 'AdminUserController@index')->name('adminUser.index');
            Route::get('/admin_users/list', 'AdminUserController@list')->name('adminUser.list');
            Route::get('/admin_users/create', 'AdminUserController@create')->name('adminUser.create');
            Route::post('/admin_users', 'AdminUserController@save')->name('adminUser.save');
            Route::get('/admin_users/{id}/edit', 'AdminUserController@edit')->name('adminUser.edit');
            Route::put('/admin_user/{id}', 'AdminUserController@update')->name('adminUser.update');

            Route::get('/admin_users/{id}/role', 'AdminUserController@role')->name('adminUser.role.edit');
            Route::put('/admin_user/{id}/role', 'AdminUserController@updateRole')->name('adminUser.role.update');

            // 菜单管理
            Route::get('/menus', 'MenuController@index')->name('menu.index');
            Route::get('/menus/list', 'MenuController@list')->name('menu.list');
            Route::get('/menus/create', 'MenuController@create')->name('menu.create');
            Route::post('/menus', 'MenuController@save')->name('menu.save');
            Route::get('/menus/{id}/edit', 'MenuController@edit')->name('menu.edit');
            Route::put('/menus/{id}', 'MenuController@update')->name('menu.update');
            Route::post('/menus/discovery', 'MenuController@discovery')->name('menu.discovery');

            // 角色管理
            Route::get('/roles', 'RoleController@index')->name('role.index');
            Route::get('/roles/list', 'RoleController@list')->name('role.list');
            Route::get('/roles/create', 'RoleController@create')->name('role.create');
            Route::post('/roles', 'RoleController@save')->name('role.save');
            Route::get('/roles/{id}/edit', 'RoleController@edit')->name('role.edit');
            Route::put('/roles/{id}', 'RoleController@update')->name('role.update');

            Route::get('/roles/{id}/permission', 'RoleController@permission')->name('role.permission.edit');
            Route::put('/roles/{id}/permission', 'RoleController@updatePermission')->name('role.permission.update');
        });
    }
);
