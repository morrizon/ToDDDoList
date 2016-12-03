<?php
namespace ToDDDoList\Context\Task\Infrastructure\Persistence;

use ToDDDoList\Context\Task\Domain\Task;
use ToDDDoList\Context\Task\Domain\TaskRepository;

class TaskRepositoryMemory implements TaskRepository
{
    private $tasks;

    public function save(Task $task)
    {
        $this->tasks[] = $task;
    }

    public function getAll()
    {
        return $this->tasks;
    }
}
