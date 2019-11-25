<?php
/**
 * 加载指定模块下的模块文件
 * @var string $moduleName 模块名
 * @var string $module_dir 模块目录
 * @var string|array $excludes 排除在外要加载的子文件夹[只有一个文件夹就是字符串，超过一个就是数组]
 */
function load_module($moduleName, $module_dir, $excludes = null)
{
    $require_dirs = UtilFileSystem::getSubDirsInDirectory( $module_dir );
    ///需要包含本目录下的文件。

    $tmps = UtilFileSystem::getFilesInDirectory( $module_dir );
    foreach ($tmps as $tmp) {
        Initializer::$moduleFiles[$moduleName][basename($tmp, ".php")] = $tmp;
    }

    if ( !empty($excludes) ) {
        if ( is_array($excludes) ) {
            foreach ($excludes as $exclude) {
                if ( array_key_exists($exclude, $require_dirs) ) {
                    unset ($require_dirs[$exclude]);
                }
            }
        } else if ( is_string($excludes) ) {
            unset ($require_dirs[$excludes]);
        }
    }
    foreach ($require_dirs as $dir) {
        $tmps = UtilFileSystem::getAllFilesInDirectory( $dir );
        foreach ($tmps as $tmp) {
            Initializer::$moduleFiles[$moduleName][basename($tmp, ".php")] = $tmp;
        }
    }
}


/**
 * 获取对象实体|对象名称的反射类。
 * @param mixed $object 对象实体|对象名称
 * @return 对象实体|对象名称的反射类
 */
function object_reflection($object)
{
    $class = null;
    if ( is_object($object) ) {
        $class = new ReflectionClass($object);
    } else {
        if ( is_string($object) ) {
            if ( class_exists($object) ) {
                $class = new ReflectionClass($object);
            }
        }
    }
    return $class;
}

/**
 * 测试指定URL的地址是否正常工作
 * @param mixed $url
 * @param mixed $data
 */
function ping_url($url, $data = null)
{
    $url = parse_url($url);
    if ( array_key_exists('query', $url) ) {
        parse_str($url['query'], $out);
    }
    if ( ($data != null) && ( is_array($data) ) ) {
        $out = array_merge($out, $data);
    }
    if ( isset($out) ) {
        $url['query'] = '?' . http_build_query($out);
    }
    $host = gethostbyname($url['host']);
    $fp   = fsockopen($host, isset($url['port'])?$url['port']:80, $errno, $errstr, 2);
    if ( !$fp ) {
        return false;
    } else {
        if ( array_key_exists('query', $url) ) {
            $fullUrl = "{$url['path']}{$url['query']}";
        } else {
            $fullUrl = "{$url['path']}";
        }
        $out  = "GET $fullUrl HTTP/1.1\r\n";
        $out .= "Host: {$url['host']}\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        $content = "";
        while (!feof($fp)) {
            $content .= fgets($fp, 128);
        }
        return $content;
    }
}

/**
 +----------------------------------------------------------
 * 字符串命名风格转换
 * type
 * =0 将Java风格转换为C的风格
 * =1 将C风格转换为Java的风格
 +----------------------------------------------------------
 * @access protected
 +----------------------------------------------------------
 * @param string $name 字符串
 * @param integer $type 转换类型
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function parse_name($name, $type = 0)
{
    if( $type ) {
        return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
    }else {
        $name = preg_replace("/[A-Z]/", "_\\0", $name);
        return strtolower(trim($name, "_"));
    }
}

/**
 * 字母转换成数字【无视大小写，如a和A都返回1】
 * 字母是26个,因此就是二十六进制。如果是AA就是AA=1*26+1=27,ZZ就是ZZ=26*26+26=702
 * 字母a-z【A-Z】转换成数字就是1-26,如果是AA-ZZ转换成数字就是【27-702】
 * @param string $alphabet 字母字符串
 * @return int 数字
 */
function alphatonumber($alphabet)
{
    if ( !empty($alphabet) ) {
        if ( !preg_match("/^[a-zA-Z]*$/", $alphabet) ) {
            return 0;
        }
        $count  = strlen($alphabet);
        $result = 0;$base = 26;
        for ($j = 1; $j <= $count; $j++) {
            $number = 1;
            $alphabet[$j-1] = strtoupper($alphabet[$j-1]);
            for($i = 'A'; $i <= 'Z'; $i++){
                if ( $alphabet[$j-1] == $i ) {
                    break;
                }
                ++$number;
            }
            $result += pow($base, $count - $j) * $number;
        }
        return $result;
    } else {
        return 0;
    }
}

/**
 * 判断服务器是否Linux
 */
function is_server_windows()
{
    if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
        return true;
    } else {
        return false;
    }
}

/**
 * 提示系统目录需要创建的信息
 */
function system_dir_info($dir_name, $info_dir_name = "" )
{
    if ( !$info_dir_name ) $info_dir_name = $dir_name;
    if ( !is_dir($dir_name) ) {
        $isMac   = ( contain( strtolower(php_uname()), "darwin" ) ) ? true : false;
        $os      = $isMac ? "MacOS" : "Linux";
        $info    = "<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>因为安全原因，需要手动在操作系统中创建的目录:" . $info_dir_name . "<br/>" .
                   "$os 系统需要执行指令:<br/>" . str_repeat("&nbsp;",40) .
                   "sudo mkdir -p " . $info_dir_name . "<br/>" . str_repeat("&nbsp;",40);
        $info   .= "sudo chmod -R 0777 " . $info_dir_name . "</p>";
        // if (!$isMac){
        //     $info .=
        //         "sudo chown -R www-data:www-data " . self::$save_dir . "<br/>" . str_repeat("&nbsp;",8) .
        //         "sudo chmod -R 0755 " . self::$save_dir . "</p>";
        // }
        die($info);
    }
}

/**
 * 取得阅读器名称和版本
 * @access public
 * @return string
 */
function getbrowser()
{
    global $_SERVER;

    $agent   = $_SERVER['HTTP_USER_AGENT'];
    $browser = '';
    $browser_ver = '';

    if ( preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs) )
    {
        $browser     = 'OmniWeb';
        $browser_ver = $regs[2];
    }

    if ( preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs) )
    {
        $browser     = 'Netscape';
        $browser_ver = $regs[2];
    }

    if ( preg_match('/safari\/([^\s]+)/i', $agent, $regs) )
    {
        $browser     = 'Safari';
        $browser_ver = $regs[1];
    }

    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs))
    {
        $browser     = 'Internet Explorer';
        $browser_ver = $regs[1];
    }

    if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs))
    {
        $browser     = 'Opera';
        $browser_ver = $regs[1];
    }

    if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs))
    {
        $browser     = '(Internet Explorer ' .$browser_ver. ') NetCaptor';
        $browser_ver = $regs[1];
    }

    if (preg_match('/Maxthon/i', $agent, $regs))
    {
        $browser     = '(Internet Explorer ' .$browser_ver. ') Maxthon';
        $browser_ver = '';
    }

    if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs))
    {
        $browser     = 'FireFox';
        $browser_ver = $regs[1];
    }

    if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs))
    {
        $browser     = 'Lynx';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs))
    {
        $browser     = 'Chrome';
        $browser_ver = $regs[1];
    }
    //echo $agent."<br/>";
    if ($browser != '')
    {
        return $browser . ' ' . $browser_ver;
    }
    else
    {
        return 'Unknow browser';
    }
}

/**
 * 取得客户端使用的操作系统体系
 *
 * @access private
 * @return void
 */
function client_os()
{
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os    = false;

    if ( eregi('win', $agent) && strpos($agent, '95'))
    {
        $os = 'Windows 95';
    }
    else if ( eregi('win 9x', $agent) && strpos($agent, '4.90'))
    {
        $os = 'Windows ME';
    }
    else if ( eregi('win', $agent) && ereg('98', $agent) )
    {
        $os = 'Windows 98';
    }
    else if ( eregi('win', $agent) && eregi('nt 6.0', $agent) )
    {
        $os = 'Windows Vista';
    }
    else if ( eregi('win', $agent) && eregi('nt 6.1', $agent) )
    {
        $os = 'Windows 7';
    }
    else if ( eregi('win', $agent) && eregi('nt 5.1', $agent) )
    {
        $os = 'Windows XP';
    }
    else if ( eregi('win', $agent) && eregi('nt 5', $agent) )
    {
        $os = 'Windows 2000';
    }
    else if ( eregi('win', $agent) && eregi('nt', $agent) )
    {
        $os = 'Windows NT';
    }
    else if ( eregi('win', $agent) && ereg('32', $agent) )
    {
        $os = 'Windows 32';
    }
    else if ( eregi('linux', $agent) )
    {
        $os = 'Linux';
    }
    else if ( eregi('unix', $agent) )
    {
        $os = 'Unix';
    }
    else if ( eregi('sun', $agent) && eregi('os', $agent) )
    {
        $os = 'SunOS';
    }
    else if ( eregi('ibm', $agent) && eregi('os', $agent) )
    {
        $os = 'IBM OS/2';
    }
    else if ( eregi('Mac', $agent) && eregi('PC', $agent) )
    {
        $os = 'Macintosh';
    }
    else if ( eregi('PowerPC', $agent) )
    {
        $os = 'PowerPC';
    }
    else if ( eregi('AIX', $agent) )
    {
        $os = 'AIX';
    }
    else if ( eregi('HPUX', $agent) )
    {
        $os = 'HPUX';
    }
    else if ( eregi('NetBSD', $agent) )
    {
        $os = 'NetBSD';
    }
    else if ( eregi('BSD', $agent) )
    {
        $os = 'BSD';
    }
    else if (ereg('OSF1', $agent) )
    {
        $os = 'OSF1';
    }
    else if (ereg('IRIX', $agent) )
    {
        $os = 'IRIX';
    }
    else if ( eregi('FreeBSD', $agent) )
    {
        $os = 'FreeBSD';
    }
    else if ( eregi('teleport', $agent) )
    {
        $os = 'teleport';
    }
    else if ( eregi('flashget', $agent) )
    {
        $os = 'flashget';
    }
    else if ( eregi('webzip', $agent) )
    {
        $os = 'webzip';
    }
    else if ( eregi('offline', $agent) )
    {
        $os = 'offline';
    }
    else
    {
        $os = 'Unknown';
    }
    return $os;
}
?>
