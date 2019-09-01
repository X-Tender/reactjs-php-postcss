<?php

namespace App\Middleware;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Noodlehaus\Config;

class LoginCookieRefreshMiddleware
{

    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function __invoke($request, $response, $next)
    {

        $cookieName = $this->config->get('jwt.cookie');
        $cookie     = FigRequestCookies::get($request, $cookieName);

        $requestToken = $cookie->getValue();

        $response = $next($request, $response);

        $responseToken = FigResponseCookies::get($response, $cookieName)->getValue();

        if ($requestToken != null && $responseToken === null) {
            $setCookie = SetCookie::create($cookieName)
                ->withValue($requestToken)
                ->withExpires(new \DateTime('+6 hour'))
                ->withHttpOnly(false)
                ->withPath("/");

            $response = FigResponseCookies::set($response, $setCookie);
        }

        return $response;
    }
}
