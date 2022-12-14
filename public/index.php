<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use MichaelRamirezApi\App;


$app = App::create();
$app->run();