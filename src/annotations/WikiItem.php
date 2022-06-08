<?php
namespace saithink\wiki\annotations;

/**
 * 接口信息注释路由
 * @Annotation
 */
class WikiItem
{
    // 接口名称
    public $name;

    // 路由
    public $route;

    // Http请求方式
    public $method;

    // 描述
    public $description;
}
