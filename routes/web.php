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
$router->group(['prefix' => 'users'], function ($router) {
    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');
    $router->post('/logout', 'UserController@logout');
});


$router->group(['middleware' => 'auth:api'], function ($router) {
    $router->group(['prefix' => 'users'], function ($router) {
        $router->get('/info', 'UserController@info');
        $router->get('/{id}/home', 'UserController@home');
    });

    $router->group(['prefix' => 'friends'], function ($router) {
        $router->get('/list', 'FriendController@list');
        $router->get('/apply-list', 'FriendController@applyList');
        $router->delete('/delete-apply/{id}', 'FriendController@deleteApply');
        $router->get('/search/{keywords}', 'FriendController@search');
        $router->post('/apply', 'FriendController@apply');
        $router->post('/verify', 'FriendController@verify');
    });
});
