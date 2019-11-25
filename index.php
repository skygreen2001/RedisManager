<?php
require_once ("init.php");
date_default_timezone_set('Asia/Shanghai');
/**
 * 这个文件是BetterLife CMS 的Bootstrap
 */
require_once("core/main/Application.php");
/**
 * 具体环境变量设置参考
 * 1.环境变量设置:Gc.php里的变量名及注释说明
 */
$application_env=array();
// $application_env=array(
//     "dev_debug_on"=>false,
//     "dev_php_debug_on"=>false,
// );

$application = new Application($application_env);
$application->run();
