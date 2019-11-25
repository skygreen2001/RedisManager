<?php
/**
 +---------------------------------<br/>
 * 功能:处理数字计算的工具类<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilNumber extends Util{
    /**
     +----------------------------------------------------------<br/>
     * 获取一定范围内的随机数字 位数不足补零<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param integer $min 最小值
     * @param integer $max 最大值
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public static function randNumber ($min, $max) {
        return sprintf("%".strlen($max)."d", mt_rand($min,$max));
    }


    /**
     * 判断字符串是否数字
     * @param type $num
     * @return type 
     */
    public static function isNum ($num) {
        return ereg('^[0-9]+$',$num)?true:false;
    }    
    
    /**
    * 解析数字字符串为数字
    * @param string $str
    * @return int 返回数字。
    */
    function parseInt($str){
        return (int)preg_replace('/[^0-9\.]+/','',$str);
    }    
}
//echo UtilNumber::rand_number(3, 10);
?>
