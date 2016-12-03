<?php
namespace ToDDDoList\App\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

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
		$output->writeln('Create task: ' . $input->getArgument('title'));
    }
}
