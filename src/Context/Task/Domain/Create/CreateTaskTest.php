<?php
namespace ToDDDoList\Context\Task\Domain\Create;

use PHPUnit\Framework\TestCase;
use ToDDDoList\Context\Task\Infrastructure\Persistence\TaskRepositoryMemory;
use ToDDDoList\Context\Task\Domain\Exception\InvalidTaskArgumentException;

class CreateTaskTest extends TestCase
{
    private $taskRepository;
    private $handledEvents;

    public function setUp()
    {
        $this->taskRepository = new TaskRepositoryMemory();
        $this->handledEvents = [];
    }

    /**
     * @test
     * @expectedException ToDDDoList\Context\Task\Domain\Exception\InvalidTaskArgumentException
     */
    public function shouldFailWhenCreateNewTaskWithoutTitle()
    {
        $expectedTitle = '';

        $command = new CreateTaskCommand($expectedTitle);
        $taskFactory = new TaskFactory($this->taskRepository, $this);
        $commandHandler = new CreateTaskCommandHandler($taskFactory);
        $commandHandler->__invoke($command);
    }

    /**
     * @test
     */
    public function shouldCreateNewTaskAndPersistIt()
    {
        $expectedTitle = 'My first task!';

        $command = new CreateTaskCommand($expectedTitle);
        $taskFactory = new TaskFactory($this->taskRepository, $this);
        $commandHandler = new CreateTaskCommandHandler($taskFactory);
        $commandHandler->__invoke($command);

        $tasks = $this->taskRepository->getAll();
        $this->assertEquals(1, count($tasks));
        $this->assertEquals($expectedTitle, $tasks[0]->getTitle());
        $this->assertFalse($tasks[0]->done());
        $this->assertEquals([new TaskWasCreatedEvent], $this->handledEvents);
    }

    /**
     * Self Shunt patter to verify handled events
     */
    public function handle($event)
    {
        $this->handledEvents[] = $event;
    }
}
