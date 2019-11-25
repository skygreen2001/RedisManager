<?php
/**
 +------------------------------------------------<br/>
 * 在这里实现第三方库的加载<br/>
 +------------------------------------------------
 * @category betterlife
 * @package library
 * @author skygreen
 */
class Library_Loader
{
    /**
     * 加载第三方库:Smarty,PHPExcel
     */
    public static function load_run()
    {
        self::load_phpexcel();
    }

    /**
     * PHPExcel自动加载对象
     */
    public static function load_phpexcel_autoload($pObjectName)
    {
        if ( ( class_exists($pObjectName) ) || ( strpos($pObjectName, 'PHPExcel') === False ) ) {
            return false;
        }
        $pObjectFilePath = PHPEXCEL_ROOT.str_replace('_', DS, $pObjectName). '.php';
        if ( ( file_exists($pObjectFilePath) === false ) || ( is_readable($pObjectFilePath) === false ) ) {
            return false;
        }
        require($pObjectFilePath);
        return true;
    }
}
Library_Loader::load_run();
