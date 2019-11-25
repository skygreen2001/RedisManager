<?php
/**
 +--------------------------------------------------<br/>
 * 初始化工作<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen <skygreen2001@gmail.com>
 */
class Initializer
{
    public static $IS_CGI = false;
    public static $IS_WIN = true;
    public static $IS_CLI = false;
    public static $NAV_CORE_PATH;
    /**
     * PHP文件名后缀
     */
    const SUFFIX_FILE_PHP = ".php";
    /**
     * 框架核心所有的对象类对象文件
     * @var array 二维数组
     * 一维：模块名称
     * 二维：对象类名称
     */
    public static $coreFiles;
    /**
     * 开发者自定义所有类对象文件
     * @var array 二维数组
     * 一维：模块名称
     * 二维：对象类名称
     * @static
     */
    public static $moduleFiles;
    /**
     * 框架核心类之外可直接加载加载类的路径
     *［在core之外的其他根路径下的路径需autoload自动认知的］
     */
    public static $core_include_paths = array(
    );
    /**
    * 初始化错误，网站应用的设置路径有误提示信息。
    */
    const ERROR_INFO_INIT_DIRECTORY = "<table><tr><td>网站应用放置的目录路径设置不正确！</td></tr><tr><td>请查看全局变量设置文件Gc.php的\$nav_root_path和\$nav_framework_path配置！</td></tr></table>";

    /**
     * 自动加载指定的类对象
     * @param <type> $class_name
     */
    public static function autoload($class_name)
    {
        if ( !empty (self::$coreFiles) ) {
            foreach (self::$coreFiles as $coreFile) {
                if ( array_key_exists($class_name,  $coreFile) ) {
                    class_exists($class_name) || require($coreFile[$class_name]);
                    return;
                }
            }
        } else {
            class_exists($class_name) || require($class_name.self::SUFFIX_FILE_PHP);
            return;
        }

        if ( !empty(self::$moduleFiles) ) {
            foreach (self::$moduleFiles as $moduleFile) {
                if ( array_key_exists($class_name, $moduleFile) ) {
                    //该语句比require_once快4倍
                    class_exists($class_name) || require($moduleFile[$class_name]);
//                    require_once($moduleFile[$class_name]);
                    return;
                }
            }
        }
        // 加载拥有命名空间路径的类名
        if ( strpos($class_name, "\\") > 0 ) {
            $parts = explode('\\', $class_name);
            $path  = implode('/', $parts) . '.php';
            require_once($path);
        }
    }

    /**
     * 初始化
     * @param array $moduleNames 模块名
     */
    public static function initialize()
    {
        if ( !file_exists(Gc::$nav_root_path) || !file_exists(Gc::$nav_framework_path) ) {
            die(self::ERROR_INFO_INIT_DIRECTORY);
        }

        /**
         * 初始检验闸门
         */
        self::init();
        /**
         * 加载include_path路径
         */
        self::set_include_path();
        /**
         * 加载通用函数库
         */
        self::loadCommonFunctionLibrarys();
        /**
         * 记录框架核心所有的对象类加载进来
         */
        self::recordCoreClasses();
    }

    /**
     * 判断是否框架运行所需的模块和配置是否按要求设置
     */
    private static function is_can_run()
    {
        $is_not_run_betterlife = false;
        $phpver = strtolower(phpversion());
        $pi     = '7.0';
        if ($phpver >= 7) {
            $pos1 = strpos($phpver, ".");
            $pos2 = strpos($phpver, ".", $pos1 + strlen("."));
            $pi= substr($phpver, 0, $pos2);
        }
        if ( !function_exists("imagecreate") ) { echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装GD模块支持,名称:php_gd2,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-gd && sudo apt-get install php5-gd php$pi-gd<br/></p>";$is_not_run_betterlife = true; }
        if ( !function_exists("curl_init") ) { echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装Curl模块支持,名称:php_curl,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-curl && sudo apt-get install php5-curl php$pi-curl<br/></p>";$is_not_run_betterlife = true; }
        if ( !function_exists("mb_check_encoding") ) { echo "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>没有安装mbstring模块支持,名称:php_mbstring,请加载<br/>Ubuntu服务器下执行: sudo apt-get install php-mbstring php$pi-mbstring<br/></p>";$is_not_run_betterlife = true; }
        if ( $is_not_run_betterlife ) die();
    }

    /**
     *  初始化PHP版本校验
     */
    private static function init()
    {
        $root_core           = "core";
        self::$NAV_CORE_PATH = Gc::$nav_framework_path.$root_core.DS;
        //设置时区为中国时区
        date_default_timezone_set('PRC');
        //初始化PHP版本校验
        if ( version_compare(phpversion(), 5, '<') ) {
            header("HTTP/1.1 500 Server Error");
            echo "<h1>需要PHP 5</h1><h2>才能运行Betterlife框架, 请安装PHP 5.0或者更高的版本.</h2><p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>我们已经探测到您正在运行 PHP 版本号: <b>".phpversion()."</b>.  为了能正常运行 BetterLife,您的电脑上需要安装PHP 版本 5.1 或者更高的版本, 并且如果可能的话，我们推荐安装 PHP 5.2 或者更高的版本.</p>";
            die();
        }
        /**
         * 判断是否框架运行所需的模块和配置是否按要求设置
         */
        self::is_can_run();
        //定义异常报错信息
        if ( Gc::$dev_debug_on ) {
            ini_set('display_errors', 1);
            if ( defined('E_DEPRECATED') ) {
                if ( Gc::$dev_php_debug_on ) {
                    error_reporting( E_ALL ^ E_DEPRECATED);
                }else{
                    error_reporting( E_ALL ^ E_DEPRECATED ^ E_WARNING ^ E_NOTICE);
                }
            }
            else error_reporting(E_ALL ^ E_WARNING);
            ini_set('display_errors', 1);
        }else{
            error_reporting(0);
        }
        self::$IS_CGI = substr(PHP_SAPI, 0,3) == 'cgi' ? 1 : 0;
        self::$IS_WIN = strstr(PHP_OS, 'WIN')?1 : 0;
        self::$IS_CLI = PHP_SAPI == 'cli' ? 1 : 0;

        /**
         * class_alias需要PHP 版本>=5.3低于5.3需要以下方法方可以使用
         */
        if ( !function_exists('class_alias') ) {
            function class_alias($original, $alias) {
                eval('class ' . $alias . ' extends ' . $original . ' {}');
            }
        }

        if ( function_exists('mb_http_output') ) {
            mb_http_output(Gc::$encoding);
            mb_internal_encoding(Gc::$encoding);
        }

    }

    /**
    * 加载通用函数库
    */
    public static function loadCommonFunctionLibrarys()
    {
        $dir_include_function = self::$NAV_CORE_PATH . Config_F::ROOT_INCLUDE_FUNCTION . DS;
        $files = UtilFileSystem::getAllFilesInDirectory( $dir_include_function );
        if ( !class_exists("PEAR") ) {
            require_once("helper/PEAR.php");
        }
        if ( ini_get('allow_call_time_pass_reference') === 1 ) require_once("helper/PEAR5.php");
        foreach ($files as $file) {
            require_once($file);
        }
    }

    /**
     * 将所有需要加载类和文件的路径放置在set_include_path内
     */
    public static function set_include_path()
    {
        $core_util = "util";
        $include_paths = array(
                self::$NAV_CORE_PATH,
                self::$NAV_CORE_PATH . $core_util,
                self::$NAV_CORE_PATH . "log",
                self::$NAV_CORE_PATH . $core_util . DS . "common",
        );
        set_include_path(get_include_path() . PATH_SEPARATOR . join(PATH_SEPARATOR, $include_paths));
        $dirs_root     = UtilFileSystem::getAllDirsInDriectory( self::$NAV_CORE_PATH );
        $include_paths = $dirs_root;
        set_include_path(get_include_path() . PATH_SEPARATOR . join(PATH_SEPARATOR, $include_paths));
    }

    /**
     * 记录框架核心所有的对象类
     */
    private static function recordCoreClasses()
    {
        $dirs_root = array(
            self::$NAV_CORE_PATH
        );

        foreach (self::$core_include_paths as $core_include_path) {
            $dirs_root[] = Gc::$nav_root_path . $core_include_path;
        }

        $files = new AppendIterator();
        foreach ($dirs_root as $dir) {
            $tmp = new ArrayObject(UtilFileSystem::getAllFilesInDirectory( $dir ));
            if ( isset($tmp) ) $files->append($tmp->getIterator());
        }

        foreach ($files as $file) {
            self::$coreFiles[Config_F::ROOT_CORE][basename($file, self::SUFFIX_FILE_PHP)] = $file;
        }
    }
}
