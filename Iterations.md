### Iteration 1

* Create a task
  - A task must have a title.
  - A task cannot be created with property *Done* to true.
  - A created task must be persisted.

* Entities
  - Task: composed by a string property *Title* and a boolean property *Done*.

* Services
  - TaskRepository: permit persist and recover the tasks.

* Commands
  - CreateTask
  - ListTasks

* Ubiquitous language
  - Task: see entity *Task*. The tasks can be *created* and *list*.

* References
  - [PHP Dependency Injection Container](http://pimple.sensiolabs.org/)
  - [PHP command, bus and events](http://simplebus.github.io/MessageBus/)
  - [PHP Command Line interfaces](https://github.com/symfony/console)
  - [Domain-Driven Design by Eric Evans](http://dddcommunity.org/book/evans_2003/)

* More info
  - [DDD FAQ](http://cqrs.nu/Faq)
  - [CQRS (Command Query Responsability Segregation)](http://martinfowler.com/bliki/CQRS.html)
  - [Pattern Repository](http://martinfowler.com/eaaCatalog/repository.html)
