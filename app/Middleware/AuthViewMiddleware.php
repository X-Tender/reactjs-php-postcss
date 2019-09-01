<?php

namespace App\Middleware;

use Dflydev\FigCookies\FigRequestCookies;
use Firebase\JWT\JWT;
use Noodlehaus\Config;
use Slim\Views\Twig;

class AuthViewMiddleware
{
    protected $view;

    public function __construct(Twig $view, Config $config)
    {
        $this->view   = $view;
        $this->config = $config;
    }

    public function __invoke($request, $response, $next)
    {

        $cookieName = $this->config->get("jwt.cookie");
        $token      = FigRequestCookies::get($request, $cookieName)->getValue();

        $authAdmin = [
            "loggedIn"   => false,
            "first_name" => null,
            "last_name"  => null,
            "id"         => null,
        ];

        if ($token != "") {
            $key = $this->config->get("jwt.secret");
            try {
                $jwt = JWT::decode($token, $key, ["HS256"]);

                if (isset($jwt->admin)) {
                    $authAdmin = [
                        "loggedIn"   => true,
                        "first_name" => $jwt->admin->first_name,
                        "last_name"  => $jwt->admin->last_name,
                        "id"         => $jwt->admin->id,
                    ];
                }
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                echo "Token Decode failed. Clearch Cookies or change cookie name.";
            }
        };

        $this->view->getEnvironment()->addGlobal("authAdmin", $authAdmin);

        $response = $next($request, $response);

        return $response;
    }
}
