<?php
header("Content-Type:text/html; charset=UTF-8");
if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

require_once 'Gc.php';//加载全局变量文件
require_once 'core/main/Initializer.php';

/**
 * 相当于__autoload加载方式
 * 但是当第三方如Flex调用时__autoload无法通过其ZendFrameWork加载模式；
 * 需要通过spl_autoload_register的方式进行加载,方能在调用的时候进行加载
 * @param string $class_name 类名
 */
function class_autoloader($class_name)
{
    Initializer::autoload($class_name);
}
//使用composer的自动加载[必须放在spl_autoload_register的前面]
$autoload_file = file_exists(Gc::$nav_root_path . "install/vendor/autoload.php") ? Gc::$nav_root_path . "install/vendor/autoload.php" : Gc::$nav_root_path . "install/autoload.php";
include $autoload_file;

spl_autoload_register("class_autoloader");
Initializer::initialize();
