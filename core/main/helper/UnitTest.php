<?php
/**
 +-----------------------------------<br/>
 * 运行期对Action的单元测试<br/>
 * 暂时未应用<br/>
 +-----------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @subpackage helper
 * @author skygreen <skygreen2001@gmail.com>
 */
class UnitTest {
    /**
     * 是否需要单元测试
     * @var boolean
     * @static
     */
    const UNIT_TEST_ENABLED=false;

    private static $results = array();
    private static $testmode = false;
    public static function setUp() {
        if (self::UNIT_TEST_ENABLED) {
            self::$results = array();
            self::$testmode = true;
        }
    }
    public static function tearDown() {
        if (self::$testmode) {
            self::printTestResult();
            self::$results = array();
            self::$testmode = false;
            die();
        }
    }
    public static function printTestResult() {
        foreach (self::$results as $result) {
            echo $result."<hr/>";
        }
    }
    public static function assertTrue($object) {
        if (!self::$testmode) return 0;
        if (true==$object) $result = "passed";
        self::saveResult(true, $object, $result);
    }
    public static function assertEqual($object, $constant) {
        if (!self::$testmode) return 0;
        if ($object==$constant) {
            $result = 1;
        }
        self::saveResult($constant, $object, $result);
    }
    private static function getTrace() {
        $result = debug_backtrace();
        $cnt = count($result);
        $callerfile = $result[2]['file'];
        $callermethod = $result[3]['function'];
        $callerline = $result[2]['line'];
        return array($callermethod, $callerline, $callerfile);
    }
    
    private static function saveResult($expected, $actual,$result=false) {
        if (empty($actual)) $actual = "null/false";
        if ("failed"==$result || empty($result)) {
            $result = "<font color='red'><strong>failed</strong></font>";
        }else {
            $result = "<font color='green'><strong>passed</strong></font>";
        }
        $trace = self::getTrace();
        $finalresult = "Test {$result} in Method:
                        <strong>{$trace[0]}</strong>. Line:
                        <strong>{$trace[1]}</strong>. File:
                        <strong>{$trace[2]}</strong>. <br/> Expected:
                        <strong>{$expected}</strong>, Actual:
                        <strong>{$actual}</strong>. ";
        self::$results[] = $finalresult;
    }
    public static function assertArrayHasKey($key, array $array,$message = '') {
        if (!self::$testmode) {
            return 0;
        }
        if (array_key_exists($key, $array)) {
            $result = 1;
            self::saveResult("Array has a key named '{$key}'",
                    "Array has a key named '{$key}'", $result);
            return ;
        }
        self::saveResult("Array has a key named '{$key}'",
                "Array has not a key named '{$key}'", $result);
    }
    public static function assertArrayNotHasKey($key, array $array,$message = '') {
        if (!self::$testmode) {
            return 0;
        }
        if (!array_key_exists($key, $array)) {
            $result = 1;
            self::saveResult("Array has not a key named '{$key}'",
                    "Array has not a key named '{$key}'", $result);
            return ;
        }
        self::saveResult("Array has not a key named '{$key}'",
                "Array has a key named '{$key}'", $result);
    }

    public static function assertContains($needle, $haystack,$message = '') {
        if (!self::$testmode) return 0;
        if (in_array($needle,$haystack)) {
            $result = 1;
            self::saveResult("Array has a needle named '{$needle}'",
                    "Array has a needle named '{$needle}'", $result);
            return ;
        }
        self::saveResult("Array has a needle named '{$needle}'",
                "Array has not a needle named '{$needle}'", $result);
    }
}
?>
