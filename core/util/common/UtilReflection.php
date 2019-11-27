<?php
/**
 +---------------------------------<br/>
 * 功能:处理反射的工具类<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilReflection extends Util
{    
    /**
     * 获取指定类|对象指定静态变量的值。
     * @param mixed $object 对象实体|对象名称
     * @param string $staticPropertyName 静态变量的名称
     * @return mixed 指定静态变量的值。 
     */
    public static function getClassStaticPropertyValue($object,$staticPropertyName) 
    {
        $class=object_reflection($object);
        if (isset($class)){
            $result=$class->getStaticPropertyValue($staticPropertyName,null);
            return $result;
        }else{
            return null;
        }
    }
    
    /**
     * 返回指定类所有的静态属性
     * @param mixed $object 对象实体|对象名称
     * @return mixed 静态变量键值对数组
     */
    public static function getClassStaticProperties($object)
    {     
        $class=object_reflection($object);
        if (isset($class)){
            return $class->getStaticProperties();                      
        }  
        return null;
    }
    
     /**
     * 返回指定类指定静态变量值的名称。
     * @param mixed $object 对象实体|对象名称
     * @param string $propertyValue
     * @param string $prefix 指定前缀或者后缀的名称
     * @param bool $isprefix 是否前缀，true:前缀,false:后缀
     * @return string 指定类指定静态变量值的名称
     */
    public static function getClassStaticPropertyNameByValue($object,$propertyValue,$pre1sufix="",$isprefix=true)
    {
        $staticProperties=self::getClassStaticProperties($object);
        return UtilArray::array_search($staticProperties,$propertyValue,$pre1sufix,$isprefix);        
    }

    /**
     * 返回指定类所有的常量
     * @param mixed $object 对象实体|对象名称
     * @return mixed 常量键值对数组
     */
    public static function getClassConsts($object) 
    {
        $class=object_reflection($object);
        if (isset ($class)){
            $consts = $class->getConstants();
//            foreach ($consts as $constant => $value) {
//                echo "$constant = $value\n";
//            }
            return $consts;
        }
        return null;
    }
    
     /**
     * 返回指定类指定常量值的名称。
     * @param mixed $object 对象实体|对象名称
     * @param string $propertyValue
     * @param string $prefix 指定前缀或者后缀的名称
     * @param bool $isprefix 是否前缀，true:前缀,false:后缀
     * @return string 指定类指定常量值的名称
     */
    public static function getClassConstNameByValue($object,$propertyValue,$pre1sufix="",$isprefix=true)
    {
        $consts=self::getClassConsts($object);        
        return UtilArray::array_search($consts,$propertyValue,$pre1sufix,$isprefix);
    }
    
    /**
    * 获取对象所有属性信息
    * @object string 对象实体|对象名称
    * @return array 对象所有属性信息【分三列：属性名|属性值|属性访问权限】
    */
    public static function getClassPropertiesInfo($object)
    {        
        $class=object_reflection($object);
        $dataobjectProperties=$class->getProperties();
        $result=array();
                  
        foreach($dataobjectProperties as $dataobjectProperty)
        {
            $property=array();
            $propertyName=$dataobjectProperty->getName();
            $property['name']=$propertyName;
            if (property_exists($object,$propertyName)){
                @$property['value']=$object[$propertyName];   
                $property['access']="";
                if ($dataobjectProperty->isPublic()) $property['access']='public';
                if ($dataobjectProperty->isPrivate()) $property['access']='private';
                if ($dataobjectProperty->isProtected()) $property['access']='protected';
            }
            //$dataobjectProperty->isStatic()
            $test=Reflection::getModifierNames($object->getModifiers());
            $mo= $test;  
            $result[$propertyName]=$property;
        }
        return $result;
    }
    
    
    
}

?>
