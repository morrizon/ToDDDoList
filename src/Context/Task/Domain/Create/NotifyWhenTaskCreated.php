<?php
namespace ToDDDoList\Context\Task\Domain\Create;

class NotifyWhenTaskCreated
{
    public function __invoke($event)
    {
        echo "Task was created!!!\n";
    }
}
