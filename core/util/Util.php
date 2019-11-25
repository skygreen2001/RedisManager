<?php
/**
 +-----------------------------------<br/>
 * 所有工具类的父类<br/>
 +-----------------------------------<br/>
 * @category betterlife
 * @package util
 * @author zhouyuepu
 */
class Util extends BBObject {
    /**
     * xml单个element的属性值们。
     */
    const XML_ELEMENT_ATTRIBUTES="attributes";
    /**
     * xml单个element的内容。
     */
    const XML_ELEMENT_TEXT="text";

    /**
     * 垃圾回收，全称为Garbage Collection
     * @param mixed $value
     */
    public static function gc(&$value){
        $value = null;
        unset($value);
    }
}
?>
