<?php
require dirname(__DIR__).'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use ToDDDoList\App\Infrastructure\Command\CreateTaskCommand;

$app = new Application();

$app->add(new CreateTaskCommand());
