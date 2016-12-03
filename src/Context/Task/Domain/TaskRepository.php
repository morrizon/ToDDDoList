<?php
namespace ToDDDoList\Context\Task\Domain;

interface TaskRepository
{
    public function save(Task $task);
    public function getAll();
}
