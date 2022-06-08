<?php
namespace saithink\wiki\controller;

use think\facade\View;

use think\App;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use Symfony\Component\ClassLoader\ClassMapGenerator;

use saithink\wiki\annotations\WikiMenu;
use saithink\wiki\annotations\WikiItem;
use saithink\wiki\annotations\WikiRequest;
use saithink\wiki\annotations\WikiResponse;

class Index
{
    /** @var \think\App */
    protected $app;

    /** @var Reader */
    protected $reader;

    /** @var Array */
    protected $wikiList;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        // 控制器初始化
        $this->initialize();
    }

    public function initialize()
    {
        $this->reader = new AnnotationReader();
        $this->wikiList = [];
    }

    public function index()
    {
        AnnotationRegistry::registerLoader('class_exists');
        $app = config('wiki.scan_app') ?? 'admin';
        $dir = $this->app->getRootPath() . 'app'.DIRECTORY_SEPARATOR.$app.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR;

        $this->scanDir($dir);
        $result= [];
        foreach ($this->wikiList as $key => $info) {
         $result[$info['group']][] = $info;
        }
        View::assign('data',$result);
        View::assign('app', config('wiki.app_name') . ' '. config('wiki.app_ver'));
        return View::fetch('index');
    }

    protected function scanDir($dir)
    {
        foreach (ClassMapGenerator::createMap($dir) as $class => $path) {
            $refClass  = new ReflectionClass($class);
            // 使用WikeMenu注解路由的控制器类
            if ($resource = $this->reader->getClassAnnotation($refClass, WikiMenu::class)) {
                $this->parse($refClass);
            }
        }
    }

    protected function parse($reflectionClass)
    {
         // 读取类的信息
         $test = $this->reader->getClassAnnotation($reflectionClass, WikiMenu::class);
 
         // 读取反射类的所有方法
         $methods = $reflectionClass->getMethods();
         $api = [];
 
         foreach ($methods AS $method) {
            // 读取所有注解
            $all = $this->reader->getMethodAnnotations($method);

            if (count($all) === 0) {
                continue;
            }
             
            $request = [];
            $response = [];
            $temp = [];

            foreach ($all as $item) {
                if ($item instanceof WikiItem){
                    $temp['title'] = $item->name;
                    $temp['rule'] = $item->route;
                    $temp['method'] = $item->method;
                    $temp['description'] = $item->description;
                }
                if ($item instanceof WikiRequest){
                    array_push($request, (array)$item);
                }
                if ($item instanceof WikiResponse){
                    array_push($response, (array)$item);
                }
            }
            $temp['mock']['request'] = $request;
            $temp['mock']['response'] = $response;
            
            $api[$method->name] = $temp;

        }
        $temp = ['title' => $test->name, 'group' => $test->group, 'description' => $test->description, 'api'=> $api];        
        array_push($this->wikiList, ['group' => $test->group, 'data' => $temp]);
    }
}
