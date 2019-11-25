<?php
/**
 +---------------------------------------<br/>
 * 本网站框架需调用定义的文件夹名<br/>
 * 原定义为Config_File，因为与Smarty框架里面的Config_File.class.php有冲突，故更名为此<br/>
 +---------------------------------------
 * @category betterlife
 * @package core.config.common
 * @author skygreen
 */
class Config_F extends ConfigBB
{
    //************************** 显示层文件夹定义**************************/
    const VIEW_THEME="theme";
    const VIEW_TEMPLATE="template";
    const VIEW_VIEW="view";
    const VIEW_CORE="core";

    //************************** 框架核心文件夹定义**************************/
    /**
     * 框架核心所有文件所在文件夹的名称
     */
    const ROOT_CORE="core";
    /**
     * 框架函数和功能库所在文件夹的名称
     */
    const ROOT_LIBRARY="library";
    /**
    * 与数据处理相关的操作所在文件夹的名称
    */
    const ROOT_DATA="data";
    /**
     * 框架模块Module所在文件夹的名称
     */
    const ROOT_MODULE="module";
    /**
     * 框架函数和功能库所在文件夹的名称
     */
    const ROOT_INCLUDE_FUNCTION="include";
    /**
     * 框架运行主文件所在文件夹的名称
     */
    const CORE_MAIN="main";
    /**
     * 工具类文件所在文件夹名称
     */
    const CORE_UTIL="util";
    /**
     * 自定义函数库文件夹名称
     */
    const CORE_FUNCTION="function";
    /**
     * 语言文件存放的目录文件夹的名称
     */
    const CORE_LANG="lang";
    /**
     * Log日志文件所在的根目录
     */
    const LOG_ROOT="log";
    /**
     * 数据对象实体类所在的根目录
     */
    const DOMAIN_ROOT="domain";
    //***********************文件名后缀************************************/
    /**
     * PHP文件名后缀
     */
    const SUFFIX_FILE_PHP=".php";
    /**
     * JS文件名后缀
     */
    const SUFFIX_FILE_JS=".js";
    /**
     * Smarty模板文件名后缀
     */
    const SUFFIX_FILE_TPL=".tpl";
    /**
     * SmartyTemplate模板文件名后缀
     */
    const SUFFIX_FILE_HTML=".html";
    /**
     * EaseTemplate模板文件名后缀
     */
    const SUFFIX_FILE_HTM=".htm";
    /**
     * JSON文件名后缀
     */
    const SUFFIX_FILE_JSON=".json";
    /**
     * 日志文件名后缀
     */
    const SUFFIX_FILE_LOG=".log";

    /**
     * XML配置文件名后缀
     */
    const SUFFIX_FILE_XML=".xml";
}
?>
