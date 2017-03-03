<?php
namespace ToDDDoList\Context\Task\Domain\Create;

use ToDDDoList\Context\Task\Domain\Task;
use ToDDDoList\Context\Task\Domain\TaskRepository;

class TaskFactory
{
    private $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createTask($title)
    {
        $done = false;
        $task = new Task($title, $done);
        $this->repository->save($task);

		global $app;
        $app->getService('event-bus')->handle(new TaskWasCreatedEvent);
    }
}
