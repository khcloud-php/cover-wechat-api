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
    // $router->group(['prefix' => 'groups'], function ($router) {
    //     $router->get('/', 'GroupController@index');
    //     $router->post('/', 'GroupController@store');
    //     $router->get('/{id}', 'GroupController@show');
    //     $router->put('/{id}', 'GroupController@update');
    //     $router->delete('/{id}', 'GroupController@destroy');
    // });

    // $router->group(['prefix' => 'messages'], function ($router) {
    //     $router->get('/', 'MessageController@index');
    //     $router->post('/', 'MessageController@store');
    //     $router->get('/{id}', 'MessageController@show');
    //     $router->put('/{id}', 'MessageController@update');
    //     $router->delete('/{id}', 'MessageController@destroy');
    // });
});
