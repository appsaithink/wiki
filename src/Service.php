<?php

namespace saithink\wiki;

use think\facade\Route;

class Service extends \think\Service
{
    $this->registerRoutes(function () {

        Route::get('/wiki/docs', '\saithink\wiki\Index@docs');
    });

    Factory::configure(config('wiki'));
}
