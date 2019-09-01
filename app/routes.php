<?php

$app->get("/", ["App\Controllers\IndexController", "index"])->setName("index");

$app->post("/", ["App\Controllers\IndexController", "post"]);
