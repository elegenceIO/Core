<?php
namespace ElegenceIO\Core\Console;

use ElegenceIO\Console\Console;
use ElegenceIO\Containers\Container;
use ElegenceIO\Contracts\Bootstrap\BootstrapKernel;
use ElegenceIO\Contracts\Console\Console as ContractsConsole;

class Kernel implements BootstrapKernel
{

    public function __construct(private Container $container,private object $configs,private mixed $request)
    {
        $this->configs = $configs;
        $this->container = $container;
        $this->request = $request;

    }
    public function boot():Container
    {
        $this->configs->withContainers(["shared","cli"]);

        \var_dump($this->request);
        $this->container->bind(ContractsConsole::class,new Console($this->request));
        return $this->container;
    }


}