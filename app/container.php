<?php

use App\Errors\NotFoundHandler;
use App\Utils\DecodedJWT;
use function DI\get;
use Interop\Container\ContainerInterface;
use Noodlehaus\Config;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

return [
    "router"                       => get(Slim\Router::class),

    "notFoundHandler"              => function (ContainerInterface $c) {
        return new App\Errors\NotFoundHandler($c->get("router"));
    },

    Messages::class                => function (ContainerInterface $c) {
        return new Messages;
    },

    Twig::class                    => function (ContainerInterface $c) {
        $twig = new Twig(__DIR__ . "/../resources/views", [
            "cache" => false,
        ]);

        $twig->addExtension(new TwigExtension(
            $c->get("router"),
            $c->get("request")->getUri()
        ));

        // Add on demand
        //$twig->addExtension(new Twig_Extensions_Extension_Intl());

        $twig->getEnvironment()->addGlobal("flash", $c->get(Messages::class));

        return $twig;
    },

    Config::class                  => function (ContainerInterface $c) {
        $mode = null;

        $configs = ["local", "development", "stage", "live"];

        foreach ($configs as $key => $value) {
            if (file_exists(__DIR__ . "/config/{$value}.php")) {
                $mode = $value;
                break;
            }
        }

        if ($mode == null) {
            die("NO CONFIG FILE FOUND");
        }

        return new Config([__DIR__ . "/config/{$mode}.php"]);
    },

    Guard::class                   => function (ContainerInterface $c) {
        return new Guard();
    },

    "settings.displayErrorDetails" => function (ContainerInterface $c) {
        $appConf = $c->get(Config::class)->get("app");
        return $appConf["phpDebugMode"];
    },

    DecodedJWT::class              => function (ContainerInterface $c) {
        return new DecodedJWT();
    },

];
