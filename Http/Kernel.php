<?php
namespace ElegenceIO\Core\Http;

use ElegenceIO\Containers\Container;
use ElegenceIO\Contracts\Bootstrap\BootstrapKernel;
use ElegenceIO\Http\Requests\Requests;

class Kernel implements BootstrapKernel
{

    public function __construct(private ?Container $container, private object $configs,private mixed $requests)
    {
        $this->configs = $configs;
        $this->container = $container;
        $this->requests =  $requests;
    }

    public function boot():Container
    {
        $this->configs->withContainers(["shared","http"]);
        $this->container->bind("request",new Requests($this->requests));
        return $this->container;
    }

}