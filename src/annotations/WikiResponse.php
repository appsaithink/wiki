<?php
namespace saithink\wiki\annotations;

/**
 * 接口响应参数注释路由
 * @Annotation
 */
class WikiResponse
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
