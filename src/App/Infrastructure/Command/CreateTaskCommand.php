<?php
namespace ToDDDoList\App\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use ToDDDoList\Context\Task\Domain\Create\CreateTaskCommand as CreateTask;

class CreateTaskCommand extends Command
{
    protected function configure()
    {
        $this->setName('create-task')
            ->setDescription('Create a new task.')
            ->setHelp('Create a new task.')
            ->addArgument('title', InputArgument::REQUIRED, 'The title of the task.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        global $app;

        $commandBus = $app->getService('command-bus');
        $commandBus->handle(new CreateTask($input->getArgument('title')));

        $taskRepository = $app->getService('task-repository');
        $tasks = $taskRepository->getAll();
        $output->writeln('Create task: ' . $tasks[count($tasks)-1]->getTitle());
    }
}
