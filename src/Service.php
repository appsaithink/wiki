<?php

namespace saithink\wiki;

use think\Route;
use think\Service as TpService;

class Service extends TpService
{
    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            $route->get('wiki/docs', "\\saithink\\wiki\\controller\\Index@index");
        });
    }
}