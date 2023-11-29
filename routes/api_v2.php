<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| API v2 Routes
|--------------------------------------------------------------------------
*/

$router->get('ping', StatusCheckController::class);

$router->post('presentations', 'PresentationController@create'); // proxy route
$router->get('presentations/{id}', 'PresentationController@get'); // proxy route
$router->get('presentations/{id}/polls/current', 'PresentationController@current');
$router->put('presentations/{id}/polls/current', 'PresentationController@next');
