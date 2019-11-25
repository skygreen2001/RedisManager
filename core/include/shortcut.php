<?php
/**
 * 定义一些缩写函数快速调用的方法
 *
 */

/**
 * 自定义异常处理缩写表示
 */
function e($errorInfo, $object = null, $code = 0, $extra = null) {
    ExceptionMe::recordException( $errorInfo, $object, $code, $extra );
}

?>
