<?php
/**
 +---------------------------------------<br/>
 * 所有枚举类型的父类<br/>
 +---------------------------------------
 * @category betterlife
 * @package core
 * @author skygreen
 */
class Enum {
    /**
     * 获取所有的枚举值
     */
    public static function allEnums() {
        $class = new ReflectionClass(get_called_class());
        $consts = $class->getConstants();
        return $consts;
    }

    /**
     * 查看指定的枚举值是否存在
     * @param string $value 指定的枚举值
     * @return bool 指定的枚举值是否存在
     */
    public static function isEnumValue($value){
       if (!empty($value)){
           $class = new ReflectionClass(get_called_class());
           $consts = $class->getConstants();
           //$consts= self::allEnums();
           if (isset ($consts)){
               if (in_array($value,$consts)){
                   return true;
               }
           }
       }
       return false;
    }

    /**
     * 查看指定的枚举键是否存在
     * @param string $value 指定的枚举键
     * @return bool 指定的枚举键是否存在
     */
    public static function isEnumKey($key){
       if (!empty($key)){
           $consts= self::allEnums();
           if (isset ($consts)){
               if (array_key_exists($key,$consts)){
                   return true;
               }
           }
       }
       return false;
    }


    /**
    * 根据数据对象的属性名获取属性名的显示。
    * @param mixed $data 数据对象数组|数据对象。如:array(user,user)
    * @param mixed $property_name  属性名【可以一次指定多个属性名】
    */
    public static function propertyShow($data,$property_name){
        $class_name=get_called_class();
        if (!empty($class_name))
        {
            $class_property_name=array($class_name);
            if (is_string($property_name))
            {
                $class_property_name[]=$property_name;
            }
            else if (is_array($property_name))
            {
                $class_property_name=array_merge($class_property_name,$property_name);
            }
            if (is_array($data)&&(count($data)>0)){
                foreach ($data as $record){
                    array_walk($record, array("Enum",'property_alter'),$class_property_name);
                }
            }else if (is_object($data)){
                unset($class_property_name[0]);
                foreach ($class_property_name as $property_name)
                {
                    $data->$property_name=call_user_func($class_name."::".$property_name."show",$property_name);
                }
            }
        }
    }

    /**
    * 替换值为描述
    * @param mixed $item
    * @param mixed $key
    * @param mixed $class_property_name
    */
    private static function property_alter(&$item,$key,$class_property_name)
    {
        $enum_name= $class_property_name[0];
        unset($class_property_name[0]);
        foreach ($class_property_name as $property_name)
        {
            if (is_string($key)&&($key===$property_name)){
                $static_method_name= $property_name."Show";
                $item=call_user_func($enum_name."::".$static_method_name,$item);
            }
        }
    }
}

/**
 * 日志记录方式
 */
class EnumLogType extends Enum{
    /**
     * 默认。根据在 php.ini 文件中的 error_log 配置，错误被发送到服务器日志系统或文件。
     */
    const SYSTEM    = 0;
    /**
     * 日志通过邮件发送
     */
    const MAIL      = 1;
    /**
     * 通过 PHP debugging 连接来发送错误,在PHP3以后就不再使用了
     */
    const DEGUG     = 2;
    /**
     * 错误发送到文件目标字符串
     */
    const FILE      = 3;
    /**
     * SAPI:Server Application Programming Interface 服务端应用编程端口.
     */
    const SAPI      = 4;
    /**
     * 浏览器显示。
     */
    const BROWSER    = 11;
    /**
     * 默认记录在数据库中
     */
    const DB        = 100;
    /**
     * 通过Firebug Console 输出。
     */
    const FIREBUG   = 101;
}
