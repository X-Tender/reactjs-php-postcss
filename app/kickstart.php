<?php

use App\App;
use App\Middleware\AuthViewMiddleware;
use App\Middleware\CsrfViewMiddleware;
use App\Middleware\LoginCookieRefreshMiddleware;
use App\Utils\DecodedJWT;
use Aurmil\Slim\CsrfTokenToHeaders;
use Illuminate\Database\Capsule\Manager as Capsule;
use Noodlehaus\Config;
use Slim\Csrf\Guard;
use Slim\HttpCache\Cache;
use Slim\Views\Twig;
use Tuupola\Middleware\JwtAuthentication;
use Tuupola\Middleware\JwtAuthentication\RequestMethodRule;
use Tuupola\Middleware\JwtAuthentication\RequestPathRule;

session_start();

date_default_timezone_set("Europe/Berlin");
setlocale(LC_ALL, "de_DE.UTF-8");

require __DIR__ . "/../vendor/autoload.php";

$app = new App;

$container = $app->getContainer();
$twig      = $container->get(Twig::class);
$guard     = $container->get(Guard::class);

$config  = $container->get(Config::class);
$appConf = $config->get("app");
$dbConf  = $config->get("db");
$jwtConf = $config->get("jwt");

// Set PHP error levels
if ($appConf["phpDebugMode"]) {
    error_reporting(-1);
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
} else {
    error_reporting(0);
    ini_set("display_errors", 0);
    ini_set("display_startup_errors", 0);
}

// Setup Eloquent DB drivers (Capsule)
if ($dbConf["enabled"]) {
    $capsule = new Capsule;
    $capsule->addConnection([
        "driver"    => $dbConf["driver"],
        "host"      => $dbConf["host"],
        "port"      => $dbConf["port"],
        "database"  => $dbConf["database"],
        "username"  => $dbConf["username"],
        "password"  => $dbConf["password"],
        "charset"   => $dbConf["charset"],
        "collation" => $dbConf["collation"],
        "prefix"    => $dbConf["prefix"],
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
}

// Require routes
require __DIR__ . "/../app/routes.php";

// Add Middlewares
$app->add(new CsrfViewMiddleware($twig, $guard));
$app->add(new AuthViewMiddleware($twig, $config));
$app->add(new LoginCookieRefreshMiddleware($config));
$app->add(new Cache("public", 86400));
$app->add(new CsrfTokenToHeaders($guard));
$app->add($guard);

// Configure JWT Middleware
$app->add(new JwtAuthentication(
    [
        "secret" => $jwtConf["secret"],
        "secure" => $jwtConf["secure"],
        "cookie" => $jwtConf["cookie"],
        "before" => function (Request $request, $arguments) use ($container) {
            $decoded    = $arguments["decoded"];
            $decodedJWT = $container->get(DecodedJWT::class)->set($decoded);
            return $request;
        },
        "error"  => function ($request, $response, $arguments) use ($container) {
            return $response->withRedirect($container->get("router")->pathFor("index"), 301);
        },
        "rules"  => [
            new RequestPathRule([
                "path"        => ["/backend"],
                "passthrough" => ["/backend/login"],
            ]),
            new RequestMethodRule([
                "passthrough" => ["OPTIONS"],
            ]),
        ],
    ]
));

$app->run();
