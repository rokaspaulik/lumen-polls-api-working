<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
*/

$router->get('ping', StatusCheckController::class);

$router->post('presentations', 'PresentationController@create');
$router->get('presentations/{id}', 'PresentationController@get');
$router->get('presentations/{id}/polls/current', 'PresentationController@current');
$router->put('presentations/{id}/polls/current', 'PresentationController@next');
