<?php
/**
* 输出日期时间的格式
*/
class EnumDateTimeFormat extends Enum
{
    /**
    * Timestamp
    */
    const TIMESTAMP = 0;
    /**
    * 日期时间
    */
    const DATE = 1;
    /**
    * 日期字符串时间形式
    */
    const STRING = 2;
}

/**
* 输出日期时间的格式
*/
class EnumDateTimeShow extends Enum
{
    /**
    * 显示日期时间
    */
    const DATETIME = 0;
    /**
    * 只显示日期
    */
    const DATE = 1;
    /**
    * 只显示时间
    */
    const TIME = 2;
}

/**
 +---------------------------------<br/>
 * 功能:处理通用的日期时间方法。<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @subpackage datetime
 * @author skygreen
 */
class UtilDateTime extends Util
{
    /**
     * 标准日期时间格式：年-月-日 时:分:秒
     */
    const TIMEFORMAT_YMDHIS = "Y-m-d H:i:s";
    /**
     * 标准日期时间格式：年-月-日 时:分:秒
     */
    const TIMEFORMAT_YMD    = "Y-m-d";
    /**
     * 标准日期时间格式：时:分:秒
     */
    const TIMEFORMAT_HIS    = "H:i:s";
    /**
     * 设置当前为中国时区的时间。
     */
    public static function ChinaTime()
    {
       date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * 昨天
     */
    public static function yesterday() {
      return date("Y-m-d",strtotime("-1 day"));
    }

    /**
     * 明天
     */
    public static function tomorrow() {
      return date("Y-m-d",strtotime("+1 day"));
    }

    /**
     +----------------------------------------------------------<br/>
     * 获取现在的日期时间显示<br/>
     * 输出不同数据格式的日期｜时间｜日期时间
     * @param int $type 输出数据类型
     * 0:输出数据类型：Timestamp<br/>
     * 1:输出数据类型：日期时间<br/>
     * 2:输出数据类型：日期时间字符串形式<br/>
     *
     * @param int $timeformat 显示日期时间类型
     * 0:格式：年-月-日 小时:分钟:秒<br/>
     * 1:格式：年-月-日<br/>
     * 2:格式：小时:分钟:秒<br/>
     +----------------------------------------------------------<br/>
     */
    public static function now($type = EnumDateTimeFormat::DATE, $timeformat = EnumDateTimeShow::DATETIME)
    {
        self::ChinaTime();
        switch ($timeformat) {
            case EnumDateTimeShow::DATE:
                $now = date(self::TIMEFORMAT_YMD);
                break;
            case EnumDateTimeShow::TIME:
                $now = date(self::TIMEFORMAT_HIS);
                break;
            default:
                $now = date(self::TIMEFORMAT_YMDHIS);
                break;
        }

        switch ($type){
            case EnumDateTimeFormat::TIMESTAMP:
                return UtilDateTime::dateToTimestamp( $now );
            case EnumDateTimeFormat::DATE:
                return $now;
            case EnumDateTimeFormat::STRING:
                return $now . "";
        }
        return $now;
    }

    /**
     * 将timestamp转换成DataTime时间格式。
     * @param int $timestamp 时间戳
     * @return string 日期时间格式年-月-日 时:分:秒
     */
    public static function timestampToDateTime($timestamp, $format = self::TIMEFORMAT_YMDHIS)
    {
        self::ChinaTime();
        return date($format, $timestamp);
    }

    /**
     * 将日期时间格式年-月-日 时:分:秒转成时间戳
     * @param string $str 日期时间格式年-月-日 时:分:秒
     * @return 时间戳
     */
    public static function dateToTimestamp($str = '')
    {
        if ( empty($str) ) {
            $str = self::now();
        }
        @list($date, $time)       = explode(' ', $str);
        list($year, $month, $day) = explode('-', $date);
        if ( empty($time) ) {
            $timestamp = mktime(0, 0, 0, $month, $day, $year);
        } else {
            list($hour, $minute, $second) = explode(':', $time);
            $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        }
        return $timestamp;
    }

    /**
     +----------------------------------------------------------<br/>
     * 是否为闰年<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param int $year  年数
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public static function isLeapYear($year='')
    {
        if ( empty($year) ) {
            return false;
        }
        return ( ( ( $year % 4 ) == 0 ) && ( ( $year % 100 ) != 0 ) || ( ( $year % 400 ) == 0 ) );
    }


    /**
     +----------------------------------------------------------<br/>
     *  判断日期 所属 干支 生肖 星座<br/>
     *  type 参数：XZ 星座 GZ 干支 SX 生肖<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $type  获取信息类型
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public static function magicInfo($year, $month, $day, $type = "SX")
    {
        $result = '';
        $m      = $month;
        $y      = $year;
        $d      = $day;
        switch ($type) {
            case 'XZ'://星座
                $XZDict = array('摩羯', '宝瓶', '双鱼', '白羊', '金牛', '双子', '巨蟹', '狮子', '处女', '天秤', '天蝎', '射手');
                $Zone   = array(1222, 122, 222, 321, 421, 522, 622, 722, 822, 922, 1022, 1122, 1222);
                if ( ( 100 * $m + $d ) >= $Zone[0] || ( 100 * $m + $d ) < $Zone[1] ) {
                    $i = 0;
                } else {
                    for ($i = 1; $i < 12; $i++) {
                        if ( ( 100 * $m + $d ) >= $Zone[$i] && ( 100 * $m + $d ) < $Zone[$i + 1]){
                            break;
                        }
                    }
                }
                $result = $XZDict[$i] . '座';
                break;
            case 'GZ'://干支
                $GZDict = array(
                    array('甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸'),
                    array('子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥')
                );
                $i      = $y - 1900 + 36 ;
                $result = $GZDict[0][$i % 10] . $GZDict[1][$i % 12];
                break;
            case 'SX'://生肖
                $SXDict = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
                $result = $SXDict[($y - 4) % 12];
                break;
        }
        return $result;
    }

    /**
     * 将出生年月日转换成岁数
     * @param mixed $birthday 出生年月日。格式如："1979-03-10"
     */
    public static function birthdayToAge($birthday)
    {
        list($year, $month, $day) = explode("-", $birthday);
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if ( $month_diff < 0 ) {
            $year_diff--;
        } elseif ( ( $month_diff == 0 ) && ( $day_diff < 0 ) ) {
            $year_diff--;
        }
        return $year_diff;
    }

    /**
     * 日期遍历: 获取开始日期与结束日期之间所有日期
     * @param mixed $startDate 开始日期。格式如："2018-01-01"
     * @param mixed $endDate 结束日期。格式如："2019-01-01"
     * @return array 开始日期与结束日期之间所有日期
     */
    public static function getDates($startDate, $endDate) {
        $result    = array();
        $startDate = new DateTime($startDate);
        $endDate   = new DateTime($endDate);
        foreach (new DatePeriod($startDate, new DateInterval('P1D'), $endDate) as $d) {
            $result[] = $d->format('Y-m-d');
        }
        return $result;
    }

}
//echo UtilDateTime::magicInfo("1979", "3", "10","XZ")
?>
