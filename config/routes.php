<?php

use MichaelRamirezApi\Controllers\TaskController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    
    $routes->add('tasks_index', '/tasks/{task}')
        ->controller([TaskController::class, 'index'])
        ->defaults(['task' => 0])
        ->methods(['GET', 'HEAD']);
    $routes->add('tasks_post', '/tasks')
        ->controller([TaskController::class, 'store'])
        ->methods(['POST']);
    $routes->add('tasks_update', '/tasks/{task}')
        ->controller([TaskController::class, 'update'])
        ->defaults(['task' => 0])
        ->methods(['PUT']);
    $routes->add('tasks_delete', '/tasks/{task}')
        ->controller([TaskController::class, 'delete'])
        ->defaults(['task' => 0])
        ->methods(['DELETE']);
};