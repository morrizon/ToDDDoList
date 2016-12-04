<?php
namespace ToDDDoList\Context\Task\Domain;

class Task
{
    private $title;
    private $done;

    public function __construct($title, $done)
    {
        $this->title = $title;
        $this->done = $done;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function done()
    {
        return $this->done;
    }
}
