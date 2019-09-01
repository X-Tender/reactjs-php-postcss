<?php

return [
    "app"      => [
        "phpDebugMode" => true,
        "rootPath"     => __DIR__ . "/../../",
        "public"       => __DIR__ . "/../../public/",
    ],

    "db"       => [
        "enabled"   => false,
        "driver"    => "mysql",
        "charset"   => "utf8",
        "collation" => "utf8_unicode_ci",
        "prefix"    => "",
        "port"      => "3306",
        "host"      => "localhost",
        "database"  => "PLEASE INSERT",
        "username"  => "PLEASE INSERT",
        "password"  => "PLEASE INSERT",
    ],

    "twig"     => [
        "debug" => true,
    ],

    "jwt"      => [
        "secret" => "dev",
        "secure" => false,
        "cookie" => "token",
    ],

    "password" => [
        "cryptmode" => PASSWORD_DEFAULT,
        "cost"      => 10,
    ],

];
