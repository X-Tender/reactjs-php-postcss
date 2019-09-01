<?php

namespace App\Middleware;

use Slim\Csrf\Guard;
use Slim\Views\Twig;

class CsrfViewMiddleware
{
    protected $view;
    protected $csrf;

    public function __construct(Twig $view, Guard $csrf)
    {
        $this->view = $view;
        $this->csrf = $csrf;
    }

    public function __invoke($request, $response, $next)
    {
        $data = [
            "name"  => [
                "key"   => $this->csrf->getTokenNameKey(),
                "value" => $this->csrf->getTokenName(),
            ],
            "value" => [
                "key"   => $this->csrf->getTokenValueKey(),
                "value" => $this->csrf->getTokenValue(),
            ],
        ];

        $this->view->getEnvironment()->addGlobal("csrf", [
            "data"  => $data,
            "meta"  => '
                <meta name="tokenNameKey" value="' . $data["name"]["key"] . '">
                <meta name="tokenNameValue" value="' . $data["name"]["value"] . '">
                <meta name="tokenValueKey" value="' . $data["value"]["key"] . '">
                <meta name="tokenValueValue" value="' . $data["value"]["value"] . '">
            ',
            "input" => '
                <input type="hidden" name="' . $data["name"]["key"] . '" value="' . $data["name"]["value"] . '">
                <input type="hidden" name="' . $data["value"]["key"] . '" value="' . $data["value"]["value"] . '">
            ',
        ]);

        $response = $next($request, $response);
        return $response;
    }
}
