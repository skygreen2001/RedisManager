<?php
/**
 +---------------------------------<br/>
 * 功能:阴阳历转换。<br/>
 * （在Windows服务器上）本工具类只通用到2038年1月
 * 因为它使用比较的函数mktime只有效期到2038年1月；主要是因为32bit的Int类型支持位数只能到timestamp这个数值
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @subpackage datetime
 * @author skygreen
 */
class UtilDateLunar
{
    private static $MIN_YEAR = 1891;
    private static $MAX_YEAR = 2100;
    /**
     * 数组单元分四部分组成
     * 1:农历闰月，如果当年没有闰月，就为0；
     * 2-3:农历春节的年-月
     * 4:这个数字二进制的十进制数存储；转换成的二进制后，每个农历月的天数(每个月29,30);如果是30天就是1,29天就是0.
     * @var mixed
     */
    private static $lunarInfo = array( array(0, 2, 9, 21936), array(6, 1, 30, 9656), array(0, 2, 17, 9584), array(0, 2, 6, 21168), array(5, 1, 26, 43344), array(0, 2, 13, 59728), array(0, 2, 2, 27296), array(3, 1, 22, 44368), array(0, 2, 10, 43856), array(8, 1, 30, 19304), array(0, 2, 19, 19168), array(0, 2, 8, 42352), array(5, 1, 29, 21096), array(0, 2, 16, 53856), array(0, 2, 4, 55632), array(4, 1, 25, 27304), array(0, 2, 13, 22176), array(0, 2, 2, 39632), array(2, 1, 22, 19176), array(0, 2, 10, 19168), array(6, 1, 30, 42200), array(0, 2, 18, 42192), array(0, 2, 6, 53840), array(5, 1, 26, 54568), array(0, 2, 14, 46400), array(0, 2, 3, 54944), array(2, 1, 23, 38608), array(0, 2, 11, 38320), array(7, 2, 1, 18872), array(0, 2, 20, 18800), array(0, 2, 8, 42160), array(5, 1, 28, 45656), array(0, 2, 16, 27216), array(0, 2, 5, 27968), array(4, 1, 24, 44456), array(0, 2, 13, 11104), array(0, 2, 2, 38256), array(2, 1, 23, 18808), array(0, 2, 10, 18800), array(6, 1, 30, 25776), array(0, 2, 17, 54432), array(0, 2, 6, 59984), array(5, 1, 26, 27976), array(0, 2, 14, 23248), array(0, 2, 4, 11104), array(3, 1, 24, 37744), array(0, 2, 11, 37600), array(7, 1, 31, 51560), array(0, 2, 19, 51536), array(0, 2, 8, 54432), array(6, 1, 27, 55888), array(0, 2, 15, 46416), array(0, 2, 5, 22176), array(4, 1, 25, 43736), array(0, 2, 13, 9680), array(0, 2, 2, 37584), array(2, 1, 22, 51544), array(0, 2, 10, 43344), array(7, 1, 29, 46248), array(0, 2, 17, 27808), array(0, 2, 6, 46416), array(5, 1, 27, 21928), array(0, 2, 14, 19872), array(0, 2, 3, 42416), array(3, 1, 24, 21176), array(0, 2, 12, 21168), array(8, 1, 31, 43344), array(0, 2, 18, 59728), array(0, 2, 8, 27296), array(6, 1, 28, 44368), array(0, 2, 15, 43856), array(0, 2, 5, 19296), array(4, 1, 25, 42352), array(0, 2, 13, 42352), array(0, 2, 2, 21088), array(3, 1, 21, 59696), array(0, 2, 9, 55632), array(7, 1, 30, 23208), array(0, 2, 17, 22176), array(0, 2, 6, 38608), array(5, 1, 27, 19176), array(0, 2, 15, 19152), array(0, 2, 3, 42192), array(4, 1, 23, 53864), array(0, 2, 11, 53840), array(8, 1, 31, 54568), array(0, 2, 18, 46400), array(0, 2, 7, 46752), array(6, 1, 28, 38608), array(0, 2, 16, 38320), array(0, 2, 5, 18864), array(4, 1, 25, 42168), array(0, 2, 13, 42160), array(10, 2, 2, 45656), array(0, 2, 20, 27216), array(0, 2, 9, 27968), array(6, 1, 29, 44448), array(0, 2, 17, 43872), array(0, 2, 6, 38256), array(5, 1, 27, 18808), array(0, 2, 15, 18800), array(0, 2, 4, 25776), array(3, 1, 23, 27216), array(0, 2, 10, 59984), array(8, 1, 31, 27432), array(0, 2, 19, 23232), array(0, 2, 7, 43872), array(5, 1, 28, 37736), array(0, 2, 16, 37600), array(0, 2, 5, 51552), array(4, 1, 24, 54440), array(0, 2, 12, 54432), array(0, 2, 1, 55888), array(2, 1, 22, 23208), array(0, 2, 9, 22176), array(7, 1, 29, 43736), array(0, 2, 18, 9680), array(0, 2, 7, 37584), array(5, 1, 26, 51544), array(0, 2, 14, 43344), array(0, 2, 3, 46240), array(4, 1, 23, 46416), array(0, 2, 10, 44368), array(9, 1, 31, 21928), array(0, 2, 19, 19360), array(0, 2, 8, 42416), array(6, 1, 28, 21176), array(0, 2, 16, 21168), array(0, 2, 5, 43312), array(4, 1, 25, 29864), array(0, 2, 12, 27296), array(0, 2, 1, 44368), array(2, 1, 22, 19880), array(0, 2, 10, 19296), array(6, 1, 29, 42352), array(0, 2, 17, 42208), array(0, 2, 6, 53856), array(5, 1, 26, 59696), array(0, 2, 13, 54576), array(0, 2, 3, 23200), array(3, 1, 23, 27472), array(0, 2, 11, 38608), array(11, 1, 31, 19176), array(0, 2, 19, 19152), array(0, 2, 8, 42192), array(6, 1, 28, 53848), array(0, 2, 15, 53840), array(0, 2, 4, 54560), array(5, 1, 24, 55968), array(0, 2, 12, 46496), array(0, 2, 1, 22224), array(2, 1, 22, 19160), array(0, 2, 10, 18864), array(7, 1, 30, 42168), array(0, 2, 17, 42160), array(0, 2, 6, 43600), array(5, 1, 26, 46376), array(0, 2, 14, 27936), array(0, 2, 2, 44448), array(3, 1, 23, 21936), array(0, 2, 11, 37744), array(8, 2, 1, 18808), array(0, 2, 19, 18800), array(0, 2, 8, 25776), array(6, 1, 28, 27216), array(0, 2, 15, 59984), array(0, 2, 4, 27424), array(4, 1, 24, 43872), array(0, 2, 12, 43744), array(0, 2, 2, 37600), array(3, 1, 21, 51568), array(0, 2, 9, 51552), array(7, 1, 29, 54440), array(0, 2, 17, 54432), array(0, 2, 5, 55888), array(5, 1, 26, 23208), array(0, 2, 14, 22176), array(0, 2, 3, 42704), array(4, 1, 23, 21224), array(0, 2, 11, 21200), array(8, 1, 31, 43352), array(0, 2, 19, 43344), array(0, 2, 7, 46240), array(6, 1, 27, 46416), array(0, 2, 15, 44368), array(0, 2, 5, 21920), array(4, 1, 24, 42448), array(0, 2, 12, 42416), array(0, 2, 2, 21168), array(3, 1, 22, 43320), array(0, 2, 9, 26928), array(7, 1, 29, 29336), array(0, 2, 17, 27296), array(0, 2, 6, 44368), array(5, 1, 26, 19880), array(0, 2, 14, 19296), array(0, 2, 3, 42352), array(4, 1, 24, 21104), array(0, 2, 10, 53856), array(8, 1, 30, 59696), array(0, 2, 18, 54560), array(0, 2, 7, 55968), array(6, 1, 27, 27472), array(0, 2, 15, 22224), array(0, 2, 5, 19168), array(4, 1, 25, 42216), array(0, 2, 12, 42192), array(0, 2, 1, 53584), array(2, 1, 21, 55592), array(0, 2, 9, 54560));
    
    /**
     * 将阳历转换为阴历
     * @param year 公历-年
     * @param month 公历-月
     * @param date 公历-日
     */
    public static function convertSolarToLunar($year, $month, $date)
    {
        //debugger;
        $yearData = self::$lunarInfo[$year - self::$MIN_YEAR];
        if ($year == self::$MIN_YEAR && $month <= 2 && $date <= 9)
        {
            return array(1891, '正月', '初一', '辛卯', 1, 1, '兔');
        }
        return self::getLunarByBetween($year, self::getDaysBetweenSolar($year, $month, $date, $yearData[1], $yearData[2]));
    }

    /**
     * 将阴历转换为阳历
     * @param year 阴历-年
     * @param month 阴历-月，闰月处理：例如如果当年闰五月，那么第二个五月就传六月，相当于阴历有13个月，只是有的时候第13个月的天数为0
     * @param date 阴历-日
     */
    public static function convertLunarToSolarNormal($year, $month, $date)
    {
        $yearData = self::$lunarInfo[$year - self::$MIN_YEAR];
        $between = self::getDaysBetweenLunar($year, $month, $date);

        $res = mktime(0, 0, 0, $yearData[1], $yearData[2], $year);

        $res = date('Y-m-d', $res + $between * 24 * 60 * 60);

        //$day = split_date($res);
        $day = explode("-",$res);

        $year = $day[0];

        $month = $day[1];

        $day = $day[2];

        return array($year, $month, $day);
    }    

    /**
     * 将阴历转换为阳历:专供UtilDateFestival使用
     * @param year 阴历-年
     * @param month 阴历-月，如2012年是闰四月，端午节是阴历5月，则阴历月输入仍然是5
     * @param date 阴历-日
     */
    public static function convertLunarToSolar($year, $month, $date)
    {
        $yearData = self::$lunarInfo[$year - self::$MIN_YEAR];
        
        if  ((self::getLeapMonth($year)>0)&&($month>self::getLeapMonth($year))){
            $month+=1;
        }
        $between = self::getDaysBetweenLunar($year, $month, $date);

        $res = mktime(0, 0, 0, $yearData[1], $yearData[2], $year);

        $res = date('Y-m-d', $res + $between * 24 * 60 * 60);

        //$day = split_date($res);
        $day = explode("-",$res);

        $year = $day[0];

        $month = $day[1];

        $day = $day[2];

        return array($year, $month, $day);
    }  
    
    /**
     * 判断是否是闰年
     * @param year
     */
    public static function isLeapYear($year)
    {
        return (($year % 4 == 0 && $year % 100 != 0) || ($year % 400 == 0));
    }

    /**
     * 获取干支纪年
     * @param year
     */
    public static function getLunarYearName($year)
    {
        $sky = array('庚', '辛', '壬', '癸', '甲', '乙', '丙', '丁', '戊', '己');
        $earth = array('申', '酉', '戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午', '未');

        $year = $year . '';
        return $sky[$year{3}] . $earth[$year % 12];
    }

    /**
     * 根据阴历年获取生肖
     * @param year 阴历年
     */
    public static function getYearZodiac($year)
    {
        $zodiac = array('猴', '鸡', '狗', '猪', '鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊');
        return $zodiac[$year % 12];
    }

    /**
     * 获取阳历月份的天数
     * @param year 阳历-年
     * @param month 阳历-月
     */
    public static function getSolarMonthDays($year, $month)
    {
        $monthHash = array('1' => 31, '2' => self::isLeapYear($year) ? 29 : 28, '3' => 31, '4' => 30, '5' => 31, '6' => 30, '7' => 31, '8' => 31, '9' => 30, '10' => 31, '11' => 30, '12' => 31);
        return $monthHash["$month"];
    }

    /**
     * 获取阴历月份的天数
     * @param year 阴历-年
     * @param month 阴历-月，从一月开始
     */
    public static function getLunarMonthDays($year, $month)
    {
        $monthData = self::getLunarMonths($year);
        return $monthData[$month - 1];
    }

    /**
     * 获取阴历每月的天数的数组
     * @param year
     */
    public static function getLunarMonths($year)
    {

        $yearData = self::$lunarInfo[$year - self::$MIN_YEAR];
        $leapMonth = $yearData[0];

        $bit = decbin($yearData[3]);
        for ($i = 0; $i < strlen($bit); $i++)
        {
            $bitArray[$i] = substr($bit, $i, 1);
        }

        for ($k = 0, $klen = 16 - count($bitArray); $k < $klen; $k++)
        {
            array_unshift($bitArray, '0');
        }

        $bitArray = array_slice($bitArray, 0, ($leapMonth == 0 ? 12 : 13));
        for ($i = 0; $i < count($bitArray); $i++)
        {
            $bitArray[$i] = $bitArray[$i] + 29;
        }

        return $bitArray;
    }

    /**
     * 获取农历每年的天数
     * @param year 农历年份
     */
    public static function getLunarYearDays($year)
    {
        $yearData = self::$lunarInfo[$year - self::$MIN_YEAR];
        $monthArray = self::getLunarYearMonths($year);
        $len = count($monthArray);
        return ($monthArray[$len - 1] == 0 ? $monthArray[$len - 2] : $monthArray[$len - 1]);
    }

    public static function getLunarYearMonths($year)
    {
        //debugger;
        $monthData = self::getLunarMonths($year);
        $res = array();
        $temp = 0;
        $yearData = self::$lunarInfo[$year - self::$MIN_YEAR];
        $len = ($yearData[0] == 0 ? 12 : 13);
        for ($i = 0; $i < $len; $i++)
        {
            $temp = 0;
            for ($j = 0; $j <= $i; $j++)
            {
                $temp += $monthData[$j];
            }
            array_push($res, $temp);
        }
        return $res;
    }

    /**
     * 获取闰月
     * 规则描述如下:
     * 农历闰年闰月的推算，3年一闰，5年二闰，19年七闰；
     * 农历基本上19年为一周期对应于公历同一时间。如公历的2001年5月27日、1982年5月27日和1963年5月27日这个日子，都是闰四月初五。
     * @link http://zhidao.baidu.com/question/7800690 闰月的规则说明 
     * @param year 阴历年份
     */
    public static function getLeapMonth($year)
    {
        $yearData = self::$lunarInfo[$year - self::$MIN_YEAR];
        return $yearData[0];
    }

    /**
     * 计算阴历日期与正月初一相隔的天数
     * @param year
     * @param month
     * @param date
     */
    public static function getDaysBetweenLunar($year, $month, $date)
    {
        $yearMonth = self::getLunarMonths($year);

        $res = 0;
        for ($i = 1; $i < $month; $i++)
        {
            $res += $yearMonth[$i - 1];
        }
        $res += $date - 1;

        return $res;
    }

    /**
     * 计算2个阳历日期之间的天数
     * @param year 阳历年
     * @param cmonth
     * @param cdate
     * @param dmonth 阴历正月对应的阳历月份
     * @param ddate 阴历初一对应的阳历天数
     */
    public static function getDaysBetweenSolar($year, $cmonth, $cdate, $dmonth, $ddate)
    {        
        $a = mktime(0, 0, 0, $cmonth, $cdate, $year);
        $b = mktime(0, 0, 0, $dmonth, $ddate, $year);

        return ceil(($a - $b) / 24 / 3600);
    }

    /**
     * 根据距离正月初一的天数计算阴历日期
     * @param year 阳历年
     * @param between 天数
     */
    public static function getLunarByBetween($year, $between)
    {
        //debugger;
        $lunarArray = array();
        $yearMonth = array();
        $t = 0;
        $e = 0;
        $leapMonth = 0;
        $m = '';

        if ($between == 0)
        {
            array_push($lunarArray, $year, '正月', '初一');
            $t = 1;
            $e = 1;
        } else
        {
            $year = $between > 0 ? $year : ($year - 1);
            $yearMonth = self::getLunarYearMonths($year);
            $leapMonth = self::getLeapMonth($year);
            $between = $between > 0 ? $between : (self::getLunarYearDays($year) + $between);

            for ($i = 0; $i < 13; $i++)
            {
                if ($between == $yearMonth[$i])
                {
                    $t = $i + 2;
                    $e = 1;
                    break;
                }else if ($between < $yearMonth[$i])
                {
                    $t = $i + 1;
                    $e = $between - (empty($yearMonth[$i - 1]) ? 0 : $yearMonth[$i - 1]) + 1;
                    break;
                }
            }
            $m = ($leapMonth != 0 && $t == $leapMonth + 1) ? ('闰' . self::getCapitalNum($t - 1, true)) : self::getCapitalNum(($leapMonth != 0 && $leapMonth + 1 < $t ? ($t - 1) : $t), true);

            array_push($lunarArray, $year, $m, self::getCapitalNum($e, false));
        }
        array_push($lunarArray, self::getLunarYearName($year));
        //天干地支
        array_push($lunarArray, $t, $e);
        array_push($lunarArray, self::getYearZodiac($year));
        //12生肖
        array_push($lunarArray, $leapMonth);
        //闰几月
        return $lunarArray;
    }

    /**
     * 获取数字的阴历叫法
     * @param num 数字
     * @param isMonth 是否是月份的数字
     */
    public static function getCapitalNum($num, $isMonth)
    {
        $isMonth = $isMonth || false;
        $dateHash = array('0' => '', '1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '七', '8' => '八', '9' => '九', '10' => '十 ');
        $monthHash = array('0' => '', '1' => '正月', '2' => '二月', '3' => '三月', '4' => '四月', '5' => '五月', '6' => '六月', '7' => '七月', '8' => '八月', '9' => '九月', '10' => '十月', '11' => '冬月', '12' => '腊月');
        $res = '';

        if ($isMonth)
        {
            $res = $monthHash[$num];
        } else
        {
            if ($num <= 10)
            {
                $res = '初' . $dateHash[$num];
            }else if ($num > 10 && $num < 20)
            {
                $res = '十' . $dateHash[$num - 10];
            }else if ($num == 20)
            {
                $res = "二十";
            }else if ($num > 20 && $num < 30)
            {
                $res = "廿" . $dateHash[$num - 20];
            }else if ($num == 30)
            {
                $res = "三十";
            }
        }
        return $res;
    }

}
?>
