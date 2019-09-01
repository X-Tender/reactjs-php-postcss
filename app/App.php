<?php

namespace App;

use DI\Bridge\Slim\App as DiBridge;
use DI\ContainerBuilder;

class App extends DIBridge
{
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions(__DIR__ . "/container.php");
    }
}
