<?php

/**
  +---------------------------------<br/>
 * 所有管理类的父类<br/>
  +---------------------------------
 * @category betterlife
 * @package core
 * @author skygreen
 */
class Manager extends BBObject{     
    public function __clone()
    {
        trigger_error('不允许Clone本管理类.', E_USER_ERROR);
    }

}

?>
