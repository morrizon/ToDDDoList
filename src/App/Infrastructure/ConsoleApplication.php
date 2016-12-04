<?php
namespace ToDDDoList\App\Infrastructure;

use Symfony\Component\Console\Application;

use ToDDDoList\App\Infrastructure\Command\CreateTaskCommand;

class ConsoleApplication extends Application
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registerCommands()
    {
        $this->add(new CreateTaskCommand());
    }
}
