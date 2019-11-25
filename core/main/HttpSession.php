<?php
/**
 +--------------------------------------------------<br/>
 * Http Session 会话管理类<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class HttpSession
{
    /**
     * 启动Session会话
     */
    public static function init()
    {
        // session_save_path("/tmp");
        if ( !isset($_SESSION) ) {
            session_start() or
            die("<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>需要手动修改php.ini文件以下配置,并重启:<br/>".str_repeat("&nbsp;",8).
                "session.save_path = \"/tmp\"<br/>".str_repeat("&nbsp;",8)) .
                "请注意save_path路径，网站拥有者是否有权限可以访问！</p>";
        }
    }

    /**
     * 判断Session中是否存在$key的值
     *
     * @param mixed $key
     * @return mixed
     */
    public static function isHave($key)
    {
        // die(sys_get_temp_dir());
        if ( !isset($_SESSION) ){
            self::init();
        }
        return isset($_SESSION[$key]);
    }

    /**
     * 在Session会话中添加指定$key的值
     *
     * @param mixed $key
     * @param mixed $value
     */
    public static function set($key,$value)
    {
        if ( !isset($_SESSION) ) {
            self::init();
        }
        $_SESSION[$key]= $value;
    }

    /**
     * 一次在Session中添加多个指定$key的值
     * @param array $key_values 键值代表Session里的名称值
     */
    public static function sets($key_values)
    {
        if ( $key_values && is_array($key_values) && ( count($key_values) > 0 ) )
        {
            foreach ($key_values as $key=>$value) {
                self::set( $key, $value );
            }
        }
    }

    /**
     * 在Session会话中获取$key的值
     *
     * @param mixed $key
     * @return mixed
     */
    public static function get($key)
    {
        if ( !isset($_SESSION) ) {
            self::init();
        }
        if ( isset($_SESSION[$key]) ) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }

    /**
     * 在Session会话中获取多个指定$key的值
     * @param mixed $keys 多个键值
     * @return mixed
     */
    public static function gets($keys)
    {
        $result = array();
        if ( $keys && is_array($keys) && ( count($keys) > 0 ) )
        {
            foreach ($keys as $key) {
                $result[$key] = self::get($key);
            }
        }
        return $result;
    }

    /**
     * 从Session会话中移除指定$key的值
     *
     * @param string $key
     */
    public static function remove($key)
    {
        if ( !isset($_SESSION) ) {
            self::init();
        }
        if ( isset($_SESSION[$key]) ) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * 从Session会话中移除指定$keys的值
     *
     * @param array $keys 多个键值
     */
    public static function removes($keys)
    {
        if ( !isset($_SESSION) ) {
            self::init();
        }
        if ( $keys && is_array($keys) && ( count($keys) > 0 ) )
        {
            foreach ($keys as $key) {
                unset($_SESSION[$key]);
            }
        }
    }

    /**
     * 清除所有的Session
     */
    public static function removeAll()
    {
        session_unset();
    }
}
?>
