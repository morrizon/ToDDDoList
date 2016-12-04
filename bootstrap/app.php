<?php
require dirname(__DIR__).'/vendor/autoload.php';

use ToDDDoList\App\Application;


$app = new Application();
$app->registerServices();
$app->registerCommands();
