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
        // 用户登录、注册
        Route::get('/login', 'UserController@showLogin')->name('login.show');
        Route::post('/login', 'UserController@login')->name('login');
        Route::get('/register', 'UserController@showRegistrationForm')->name('register.show');
        Route::post('/register', 'UserController@register')->name('register');

        // 三方登录
        Route::get('/weibo/callback', 'UserController@weiboCallback')->name('weibo.callback');
        Route::get('/weibo/auth', 'UserController@weiboAuth')->name('weibo.auth');
        Route::get('/qq/callback', 'UserController@qqCallback')->name('qq.callback');
        Route::get('/qq/auth', 'UserController@qqAuth')->name('qq.auth');
        Route::get('/wechat/callback', 'UserController@wechatCallback')->name('wechat.callback');
        Route::get('/wechat/auth', 'UserController@wechatAuth')->name('wechat.auth');

        // 评论
        Route::get('/comment', 'CommentController@list')->name('comment.list');
        Route::middleware('auth:member')->group(function () {
            Route::get('/logout', 'UserController@logout')->name('logout');

            // 发表评论
            Route::post('/comment', 'CommentController@save')->name('comment.save');
            Route::post('/comment/{id}/operate/{action}', 'CommentController@operate')
                ->name('comment.operate')->where('action', 'like|dislike|neutral');
        });
    }
);
