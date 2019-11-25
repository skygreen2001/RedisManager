<?php
/**
 +----------------------------------------<br/>
 * 框架异常处理的父类<br/>
 +----------------------------------------<br/>
 * @category betterlife
 * @package core.exception
 * @author zhouyuepu
 */
abstract class ExceptionMe extends Exception {
    const CLASSNAME = __CLASS__;

    public static $messages = array();

    const VIEW_TYPE_TEXT       = 1;
    const VIEW_TYPE_HTML_TABLE = 2;
    const VIEW_TYPE_HTML_TREE  = 3;
    const VIEW_TYPE_HTML_XML   = 4;

    /**
     * 倒退跟踪调用
     */
    public static function backtrace() {
        echo "<pre>";
        debug_print_backtrace();
        echo "</pre>";
    }


    /**
     *记录异常信息
     * @param 错误信息 $errorInfo
     * @param 发生错误信息的自定义类 $object
     * @param string $extra  补充存在多余调试信息
     *
     */
    public static function recordException($errorInfo, $object = null, $code = 0, $extra = null) {
        //记录系统日志
        $exception = new Exception_Customize( $errorInfo, $code, $extra );
        if ( is_object($object) ) {
            self::$messages[get_class($object)] = $exception->showMessage();
        } else {
            if ( empty($object) ) {
                $object = $exception->getType();
            }
            self::$messages[$object] = $exception->showMessage();
        }
    }

    /**
     * 设置处理所有未捕获异常的用户定义函数
     * @param <type> $exception
     */
    public static function recordUncatchedException($exception) {
        $exception = new Exception_Customize($exception);
        self::$messages[$exception->getType()] = $exception->showMessage();
    }


    /**
     * 显示异常信息
     * 1：普通【文本样式】
     * 2: 表方式【HTML样式】
     * 3：树方式【HTML样式】
     * 4：XML方式
     */
    public static function showMessage($type) {
        switch ($type) {
            case self::VIEW_TYPE_TEXT:
                return self::showMessageByText();
            case self::VIEW_TYPE_HTML_TABLE:
                return self::showMessageByTable();
            case self::VIEW_TYPE_HTML_TREE:
                return self::showMessageByTree();
            case self::VIEW_TYPE_HTML_XML:
                return self::showMessageByXml();
        }
    }

    /**
     *
     * @todo 以普通文本形式显示异常信息
     */
    private static function showMessageByText() {

    }


    /**
     *
     * @todo 以XML形式显示异常信息
     */
    private static function showMessageByXml() {

    }


    /**
     *
     * @todo 以树形式显示异常信息[HTML格式]
     */
    private static function showMessageByTree() {

    }


    /**
     * 以表形式显示异常信息[HTML格式]
     */
    private static function showMessageByTable() {
        if ( !empty(self::$messages) ) {
            UtilCss::report_info();
            $errorInfo  = '<div>';
            $errorInfo .= '<table class="' . UtilCss::CSS_REPORT_TABLE . '" style="width:80%;">';
            foreach (self::$messages as $key => $value) {
                $errorInfo .= '<tr>';
                $errorInfo .= '  <td style="border-bottom:0px;">';
                $errorInfo .= '    <table class="' . UtilCss::CSS_REPORT_TABLE . '">';
                $errorInfo .= '      <tr>';
                $errorInfo .= '          <th>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <th colspan="2" align="left">' . $key . '</td>';
                $errorInfo .= '      </tr>';
                if (!empty($value['message'])) {
                    $errorInfo .= '       <tr>';
                    $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                    $errorInfo .= '          <td width="100px" border="1">' . Wl::EXCEPTION_REPORT_INFO . '</td>';
                    $errorInfo .= '          <td>&nbsp;' . $value['message'] . "</td>";
                    $errorInfo .= '       </tr>';
                }
                if (!empty ($value['extra'])) {
                    $errorInfo .= '       <tr>';
                    $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                    $errorInfo .= '          <td width="100px" border="1">' . Wl::EXCEPTION_REPORT_ADDITION . '</td>';
                    $errorInfo .= '          <td>&nbsp;' . $value['extra'] . "</td>";
                    $errorInfo .= '       </tr>';
                }
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_CLASS . '</td>';
                $errorInfo .= '          <td>&nbsp;' . $value['class'] . "</td>";
                $errorInfo .= '       </tr>';
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_FUNCTION . '</td>';
                $errorInfo .= '          <td>&nbsp;' . $value['function'] . "</td>";
                $errorInfo .= '       </tr>';
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_FILE . '</td>';
                $errorInfo .= '          <td>&nbsp;' . $value['file'] . "</td>";
                $errorInfo .= '       </tr>';
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_LINE . '</td>';
                $errorInfo .= '          <td>&nbsp;' . $value['line'] . "</td>";
                $errorInfo .= '       </tr>';
                if (!empty($value['param'])) {
                    $errorInfo .= '       <tr>';
                    $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                    $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_PARAMETER . '</td>';
                    $errorInfo .= '          <td>&nbsp;';
                    if (is_string($value['param'][0])) {
                        $errorInfo .= implode(' | ',$value['param']);
                    }else if (is_object($value['param'][0])) {
                        foreach ($value['param'] as $object) {
                            $errorInfo .= $object->classname() . " | ";
                        }
                        $errorInfo = substr($errorInfo, 0, strlen($errorInfo) - 2);
                    }
                    $errorInfo .= "</td>";
                    $errorInfo .= '       </tr>';
                }
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_DETAIL . '</td>';
                $errorInfo .= "          <td><br/>" . str_replace("\n", "<br/>", $value['detail']) . "<br/></td>";
                $errorInfo .= '       </tr>';
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_TRACKTIME . '</td>';
                $errorInfo .= '          <td>' . $value['tracktime'] . "</td>";
                $errorInfo .= '       </tr>';
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_TRACKINFO . '</td>';
                $errorInfo .= '          <td>' . "<br/>" . str_replace("\n", "<br/>",$value['trace']) . "<br/>" . "</td>";
                $errorInfo .= '       </tr>';
                $errorInfo .= '       <tr>';
                $errorInfo .= '          <td>&nbsp; &nbsp;</td>';
                $errorInfo .= '          <td>' . Wl::EXCEPTION_REPORT_TYPE . '</td>';
                $errorInfo .= '          <td>' . $value['type'] . "</td>";
                $errorInfo .= '       </tr>';
                $errorInfo .= '    </table>';
                $errorInfo .=  ' </td>';
                $errorInfo .= '</tr>';
            }
            $errorInfo .= '</table>';
            $errorInfo .= '</div>';
            $errorInfo .= '<br><br><br><br><br><br><br><br><br><br>';
            return $errorInfo;
        }
    }
}
?>
