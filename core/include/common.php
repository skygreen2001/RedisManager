<?php

/**
 * 设置处理所有未捕获异常的用户定义函数
 */
function e_me($exception)
{
    ExceptionMe::recordUncatchedException( $exception );
    e_view();
}

/**
 * 显示异常处理缩写表示
 */
function e_view()
{
    if ( Gc::$dev_debug_on ) {
        echo ExceptionMe::showMessage( ExceptionMe::VIEW_TYPE_HTML_TABLE );
    }
}

/**
 * 查看字符串里是否包含指定字符串
 * @param mixed $subject
 * @param mixed $needle
 */
function contain($subject, $needle)
{
    if ( empty($subject) ) return false;
    if ( strpos(strtolower($subject), strtolower($needle))!== false ) {
        return true;
    } else {
        return false;
    }
}

/**
 * 查看字符串里是否包含数组中任意一个指定字符串
 * @param mixed $subject
 * @param mixed $array
 */
function contains($subject, $array)
{
    $result = false;
    if ( !empty($array) && is_array($array) ) {
        foreach ($array as $element) {
            if (contain($subject, $element)) {
                return true;
            }
        }
    }
    return $result;
}

/**
 * 需要的字符是否在目标字符串的开始
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function startWith($haystack, $needle, $strict=true)
{
    if ( !$strict ) {
        $haystack = strtoupper($haystack);
        $needle   = strtoupper($needle);
    }
    return strpos($haystack, $needle) === 0;
}

/**
 * 需要的字符是否在目标字符串的结尾
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function endWith($haystack, $needle, $strict = true)
{
    if ( !$strict ) {
        $haystack = strtoupper($haystack);
        $needle   = strtoupper($needle);
    }
    return (strpos(strrev($haystack), strrev($needle)) === 0);
}

/**
 * 将Url param字符串转换成Json字符串
 * @link http://php.net/manual/en/function.parse-str.php
 * @example
 *  示例如下：<br/>
 *  $url_parms = 'title=hello&custLength=200&custWidth=300'
 * @return Json字符串
 */
function urlparamToJsonString($url_parms)
{
    parse_str($url_parms, $parsed);
    $result = json_encode($parsed);
    return $result;
}

/**
 * js escape php 实现
 * 参考：PHP实现javascript的escape和unescape函数
 * @param $string the sting want to be escaped
 * @param $in_encoding
 * @param $out_encoding
 */
function escape($string, $in_encoding = 'UTF-8', $out_encoding = 'UCS-2')
{
    $return = '';
    if ( function_exists('mb_get_info') ) {
        for ($x = 0; $x < mb_strlen($string, $in_encoding); $x++) {
            $str = mb_substr($string, $x, 1, $in_encoding);
            if (strlen($str) > 1) { // 多字节字符
                $return .= '%u' . strtoupper(bin2hex(mb_convert_encoding($str, $out_encoding, $in_encoding)));
            } else {
                $return .= '%' . strtoupper(bin2hex($str));
            }
        }
    }
    return $return;
}

/**
 * js unescape php 实现
 * 参考：PHP实现javascript的escape和unescape函数
 * @param $string the sting want to be escaped
 * @param $in_encoding
 * @param $out_encoding
 */
function unescape($str)
{
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++)
    {
        if ($str[$i] == '%' && $str[$i + 1] == 'u')
        {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f)
                $ret .= chr($val);
            else
                if ($val < 0x800)
                    $ret .= chr(0xc0|($val>>6)).chr(0x80|($val & 0x3f));
                else
                    $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val >> 6) & 0x3f)).chr(0x80|($val&0x3f));
            $i += 5;
        } else
            if ($str[$i] == '%')
            {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else
                $ret .= $str[$i];
    }
    return $ret;
}

/**
 * 专供Flex调试使用的Debug工具
 * @link http://www.adobe.com/cn/devnet/flex/articles/flex_php_05.html
 * @param mixed $var
 */
function logMe($var)
{
    $filename = dirname(__FILE__) . '/__log.txt';
    if (!$handle = fopen($filename, 'a')) {
        echo "Cannot open file ($filename)";
        return;
    }

    $toSave = var_export($var, true);
    fwrite($handle, "[" . date("y-m-d H:i:s") . "]");
    fwrite($handle, "\n");
    fwrite($handle, $toSave);
    fwrite($handle, "\n");
    fclose($handle);
}

/**
 * 是否直接显示出来
 * @param @mixed $s 复合类型
 * @param boolean $isEcho 是否直接显示打印出来 
 * @param string $title 标题
 */
function print_pre($s, $isEcho = false, $title="")
{
    if ( !empty($title) && $isEcho ) {
        echo $title . "<br/>";
    }
    if ( $isEcho ) {
        print "<pre>"; print_r($s); print "</pre>";
    } else {
        return "<pre>" . var_export($s,true) . "</pre>";
    }
}

/**
 * 将字符串从unicode转为utf-8
 * @param string $str 原内容
 * @return string 新内容
 */
function unicode2utf8($str)
{
    if ( !$str ) return $str;
    $decode = json_decode($str);
    if ( $decode ) return $decode;
    $str    = '["' . $str . '"]';
    $decode = json_decode($str);
    if( count($decode) == 1 ) {
        return $decode[0];
    }
    return $str;
}

?>
