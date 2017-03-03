<?php
namespace ToDDDoList\Context\Task\Domain\Create;

use PHPUnit\Framework\TestCase;
use ToDDDoList\Context\Task\Infrastructure\Persistence\TaskRepositoryMemory;
use ToDDDoList\Context\Task\Domain\Exception\InvalidTaskArgumentException;

class CreateTaskTest extends TestCase
{
    private $taskRepository;

    public function setUp()
    {
        $this->taskRepository = new TaskRepositoryMemory();
    }

    /**
     * @test
     * @expectedException ToDDDoList\Context\Task\Domain\Exception\InvalidTaskArgumentException
     */
    public function shouldFailWhenCreateNewTaskWithoutTitle()
    {
        global $app;

        $expectedTitle = '';

        $command = new CreateTaskCommand($expectedTitle);
        $taskFactory = new TaskFactory($this->taskRepository);
        $commandHandler = new CreateTaskCommandHandler($taskFactory);
        $commandHandler->__invoke($command);
    }

    /**
     * @test
     */
    public function shouldCreateNewTaskAndPersistIt()
    {
        global $app;

        $expectedTitle = 'My first task!';

        $command = new CreateTaskCommand($expectedTitle);
        $taskFactory = new TaskFactory($this->taskRepository);
        $commandHandler = new CreateTaskCommandHandler($taskFactory);
        $commandHandler->__invoke($command);

        $tasks = $this->taskRepository->getAll();
        $this->assertEquals(1, count($tasks));
        $this->assertEquals($expectedTitle, $tasks[0]->getTitle());
        $this->assertFalse($tasks[0]->done());
    }
}
