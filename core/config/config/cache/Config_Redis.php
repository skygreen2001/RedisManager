<?php
  /**
 +---------------------------------<br/>
 * 分布式缓存:Redis的配置类<br/>
 * 应根据项目的需要修改相应的配置<br/>
 +---------------------------------<br/>
 * 帮助:https://github.com/nicolasff/phpredis
 * PHP-redis中文说明:http://hi.baidu.com/%B4%AB%CB%B5%D6%D0%B5%C4%C8%CC%D5%DF%C3%A8/blog/item/c9ff4ac1898afa4fb219a8c7.html
 * Reference Methods:http://code.google.com/p/phpredis/wiki/referencemethods
 * Redis:http://wenhui.ncu.me/category/webserver/redis
 * Rediska:http://rediska.geometria-lab.net/
 +---------------------------------<br/>
 * @category betterlife
 * @package core.config
 * @subpackage cache
 * @author skygreen
 */
class Config_Redis extends ConfigBB
{
    /**
     * 主机地址
     * @var mixed
     */
    public static $host = "127.0.0.1";
    /**
     * 端口
     * @var mixed
     */
    public static $port = 6379;
    /**
     * 密码
     * @var mixed
     */
    public static $password = "";
    /**
     * @var boolean 是否持久化Redis通道的链接
     * @static
     */
    public static $is_persistent = false;
    /**
     * 所有键的前缀名，默认为无
     */
    public static $prefix_key = "";

}
?>
