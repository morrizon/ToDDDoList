<?php
namespace ToDDDoList\App\Infrastructure;

use Pimple\Container;

class InjectionContainer extends Container
{
    /**
     * Gets a service.
     *
     * @param $id
     * @return mixed
     */
    public function getService($id)
    {
        return $this->offsetGet("service-$id");
    }

    /**
     * Sets a service.
     *
     * @param $id
     * @param $service
     * @return mixed
     */
    public function setService($id, $service)
    {
        $this["service-$id"] = $service;
    }
}
