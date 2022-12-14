<?php

use MichaelRamirezApi\Controllers\TaskController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('tasks_index', '/tasks/{task}')
        ->controller([TaskController::class, 'index'])
        ->defaults(['task' => 0])/*->methods(['GET', 'HEAD'])*/;
};