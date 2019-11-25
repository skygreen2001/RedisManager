<?php
/**
 +----------------------------------------<br/>
 * 自定义打印异常对象<br/>
 +----------------------------------------<br/>
 * @category betterlife
 * @package core.exception.base
 * @author zhouyuepu
 */
class Exception_Customize extends Exception {
    /**
     +----------------------------------------------------------
     * 异常类型
     +----------------------------------------------------------
     * @var string
     * @access private
     +----------------------------------------------------------
     */
    private $type;
    /**
     * @var array 跟踪信息
     */
    private $trace;
    /**
     *
     * @var string 存在多余调试信息
     */
    private $extra;

    /**
     * 异常错误信息
     * @var array
     */
    private $errorInfo;

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string|Exception $exception  异常信息|异常类
     * @param int $code 异常编码
     * @param string $extra 存在多余调试信息
     +----------------------------------------------------------
     *
     */
    public function __construct($exception, $code = 0, $extra = null) {
        if ( $exception instanceof Exception || $exception instanceof Error ) {
            parent::__construct($exception->getMessage(), $exception->getCode());
            $this->type  = get_class($exception);
            $this->trace = $exception->getTrace();
            @array_unshift($this->trace, array("file" => $exception->getFile(), "line" => $exception->getLine(),
                    "function" => $this->trace[0]["function"], "class" => $this->trace[0]["class"],
                    "args" => "", "type" => $this->trace[0]["type"]));
        } else if ( is_string($exception) ) {
            parent::__construct($exception, $code);
            $this->type  = get_class($this);
            $this->extra = $extra;
            $this->trace = parent::getTrace();
        }
        echo $this;
    }

    /**
     +----------------------------------------------------------
     * 异常输出 所有异常处理类均通过__toString方法输出错误
     * 每次异常都会写入系统日志
     * 该方法可以被子类重载
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    public function __toString() {
        $trace = $this->trace;
        if ( $this->extra ) {
            $this->errorInfo['extra'] = $this->extra;
        }
        $current = 0;// 当前异常
        if ( $trace ) {
            foreach ($trace as $track) {
                if ( !empty($track['class']) ) {
                    if ( strpos($track['class'], ExceptionMe::CLASSNAME) === false ) {
                        break;
                    } else {
                        $current += 1;
                    }
                } else {
                    $current += 1;
                }
            }
            $this->class    = $trace[$current]['class'];
            $this->function = $trace[$current]['function'];
            $this->file = $trace[$current]['file'];
            $this->line = $trace[$current]['line'];
            $file       = file($this->file);
            $traceInfo  ='';
            $time = date("y-m-d H:i:m");
            $this->errorInfo['tracktime'] = '['.$time.'] ';
            $max_comments = 80;//跟踪异常之间用等号注释行间隔开；因此设定等长便于排版
            foreach ($trace as $t) {
                if ( isset($t['class']) ) {
                    $traceInfo .= $t['class'];
                }
                if ( isset($t['type']) ) {
                    $traceInfo .= $t['type'];
                }
                $traceInfo .= $t['function'] . '(';
                $args = array();
                if ( !empty($t['args']) ) {
                    foreach ($t['args'] as $arg) {
                        if ( is_object($arg) ) {
                            $args = get_class($arg);
                        } else {
                            $args = $arg;
                        }
                    }
                }
                if ( is_array($args) && ( count($args) > 0) ) {
                    if ( count($args) == 1 ) {
                        $args = $args[0];
                        if ( is_array($args) ) {
                            $traceInfo .= implode(',', $args);
                        }
                    } else {
                        $traceInfo .= implode(',', $args);
                    }
                }
                $traceInfo .= ")\n";
                if ( isset($t['file']) ) {
                    $traceInfo .= str_pad($t['file'] . "(" . $t['line'] . ")", $max_comments, "=", STR_PAD_BOTH);
                    $traceInfo .= "\n\n";
                }
            }
            $this->errorInfo['param']    = $trace[$current]['args'];
            $this->errorInfo['message']  = $this->message;
            $this->errorInfo['type']     = $this->type;
            $this->errorInfo['detail']   = '';
            $this->errorInfo['detail']  .= ($this->line - 2) . ': ' . $file[$this->line - 3] . "\n";
            $this->errorInfo['detail']  .= ($this->line - 1) . ': ' . $file[$this->line - 2] . "\n";
            $this->errorInfo['detail']  .= ($this->line) . ':<font color="#FF6600" ><b>' . $file[$this->line - 1] . '</b></font>' . "\n";
            $this->errorInfo['detail']  .= ($this->line + 1) . ': ' . $file[$this->line] . "\n";
            $this->errorInfo['detail']  .= ($this->line + 2) . ': ' . $file[$this->line + 1];
            $this->errorInfo['class']    = $this->class;
            $this->errorInfo['function'] = $this->function;
            $this->errorInfo['file']     = $this->file;
            $this->errorInfo['line']     = $this->line;
            $this->errorInfo['trace']    = $traceInfo;
        }
        return "";
    }

    /**
     * MysqlI 报异常：Myql的异常信息
     */
    public function showMessage() {
        return $this->errorInfo;
    }

    public function getType() {
        return $this->type;
    }

}
?>
