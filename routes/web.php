<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//系统模块
$router->group(['prefix' => 'system'], function ($router) {
    $router->post('/captcha/get', 'SystemController@get');
    $router->post('/captcha/check', 'SystemController@check');
    $router->post('/captcha/verification', 'SystemController@verification');
});

//用户模块-需校验验证码
$router->group(['prefix' => 'user', 'middleware' => 'captcha'], function ($router) {
    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');
});

//需要鉴权
$router->group(['middleware' => 'auth:api'], function ($router) {
    //用户模块
    $router->group(['prefix' => 'user'], function ($router) {
        $router->post('/logout', 'UserController@logout');
        $router->get('/me', 'UserController@me');
        $router->get('/{keywords}/home', 'UserController@home');
    });

    // 消息模块
    $router->group(['prefix' => 'chat'], function ($router) {
        $router->get('/list', 'ChatController@list');
        $router->get('/info', 'ChatController@info');
    });
    $router->group(['middleware' => 'message', 'prefix' => 'chat'], function ($router) {
        $router->put('/top', 'ChatController@top');
        $router->put('/hide', 'ChatController@hide');
        $router->put('/delete', 'ChatController@delete');
    });
    $router->group(['middleware' => 'message', 'prefix' => 'message'], function ($router) {
        $router->post('/send', 'MessageController@send');
        $router->put('/read', 'MessageController@read');
    });
    $router->get('message/list', 'MessageController@list');
    $router->get('message/unread', 'MessageController@unread');
    $router->put('message/undo', 'MessageController@undo');

    //群组模块
    $router->group(['prefix' => 'group'], function ($router) {
        $router->post('/create', 'GroupController@create');
    });

    //好友模块
    $router->group(['prefix' => 'friend'], function ($router) {
        $router->get('/list', 'FriendController@list');
        $router->get('/apply-list', 'FriendController@applyList');
        $router->delete('/delete-apply/{id}', 'FriendController@deleteApply');
        $router->get('/search/{keywords}', 'FriendController@search');
        $router->post('/show-confirm', 'FriendController@showConfirm');
        $router->post('/apply', 'FriendController@apply');
        $router->post('/verify', 'FriendController@verify');
        $router->put('/update', 'FriendController@update');
    });
});
