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
        Route::get('/register', 'UserController@showRegistrationForm')->name('register.show');
        Route::post('/register', 'UserController@register')->name('register');

        Route::get('/weibo/callback', 'UserController@weiboCallback')->name('weibo.callback');
        Route::get('/weibo/auth', 'UserController@weiboAuth')->name('weibo.auth');
        Route::get('/qq/callback', 'UserController@qqCallback')->name('qq.callback');
        Route::get('/qq/auth', 'UserController@qqAuth')->name('qq.auth');
        Route::get('/wechat/callback', 'UserController@wechatCallback')->name('wechat.callback');
        Route::get('/wechat/auth', 'UserController@wechatAuth')->name('wechat.auth');

        Route::middleware('auth:member')->group(function () {
            Route::get('/logout', 'UserController@logout')->name('logout');
        });
    }
);
