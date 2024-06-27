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

//用户模块路由
$router->group(['prefix' => 'user'], function ($router) {
    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');
    $router->post('/logout', 'UserController@logout');
});


$router->group(['middleware' => 'auth:api'], function ($router) {
    $router->group(['prefix' => 'user'], function ($router) {
        $router->get('/me', 'UserController@me');
        $router->get('/{keywords}/home', 'UserController@home');
    });

    $router->group(['prefix' => 'chat'], function ($router) {
        $router->get('/list', 'ChatController@list');
        $router->get('/info', 'ChatController@info');
    });

    $router->group(['middleware' => 'message', 'prefix' => 'message'], function ($router) {
        $router->post('/send', 'MessageController@send');
        $router->put('/read', 'MessageController@read');
    });
    $router->get('message/list', 'MessageController@list');

    $router->group(['prefix' => 'group'], function ($router) {
        $router->post('/create', 'GroupController@create');
    });

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
