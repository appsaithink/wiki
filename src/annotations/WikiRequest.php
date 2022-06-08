<?php
namespace saithink\wiki\annotations;

/**
 * 接口请求参数注释路由
 * @Annotation
 */
class WikiRequest
{
    // 参数
    public $name;

    // 名称
    public $title;

    // 类型
    public $type;

    // 是否必填
    public $require;

    // 示例
    public $example;

    // 描述
    public $description;
}
