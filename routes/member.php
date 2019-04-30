<?php
/**
 * Date: 2019/2/25 Time: 9:31
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

use App\Foundation\Regexp;

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

        // 评论列表
        Route::get('/entity/{entityId}/content/{contentId}/comment', 'CommentController@list')
            ->name('comment.list')->where(['entityId' => Regexp::RESOURCE_ID, 'contentId' => Regexp::RESOURCE_ID]);
        Route::middleware('auth:member')->group(function () {
            Route::get('/logout', 'UserController@logout')->name('logout');

            // 发表评论
            Route::post('/entity/{entityId}/content/{contentId}/comment', 'CommentController@save')
                ->name('comment.save')->where(['entityId' => Regexp::RESOURCE_ID, 'contentId' => Regexp::RESOURCE_ID]);
            // 评论操作
            Route::post('/comment/{id}/operate/{action}', 'CommentController@operate')
                ->name('comment.operate')->where('action', 'like|dislike|neutral');
            Route::get('/comment/operate/logs', 'CommentController@operateLogs')
                ->name('comment.operateLogs');
        });
    }
);
