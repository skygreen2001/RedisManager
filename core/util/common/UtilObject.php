<?php
/**
 +---------------------------------<br/>
 * 功能:处理对象相关的方法类<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilObject extends Util
{

    //<editor-fold defaultstate="collapsed" desc="object and xml">
    /**
     * 将对象转换成xml
     * @param object 数据对象
     * @param $filterArray 需要过滤不生成的对象的field<br/>
     * 示例：$filterArray=array("id","commitTime");
     * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
     * @return xml内容
     */
    public static function object_to_xml($object,$filterArray=null,$isAll=false)
    {
        $dom       = new DOMDocument("1.0", "utf-8");
        $root      = $dom->createElement(get_class($object));
        $objectArr = self::object_to_array( $object, $isAll );
        foreach($objectArr as $key => $value) {
            $node = self::createNode( $dom, $key, $value, $filterArray, $isAll );
            if ( $node != NULL ) {
                $root->appendChild($node);
            }
        }
        $dom->appendChild($root);
        return $dom->saveXML();
    }

    private static function createNode($dom, $key, $value, $filterArray = null, $isAll = true)
    {
        $node = NULL;
        if ( is_string($value) || is_numeric($value) || is_bool($value) || $value == NULL ) {
            if ( $value == NULL ) {
                if ( $isAll ) {
                    if ( isset($filterArray) && in_array($key, $filterArray) ) {
                    }else{
                        $node = $dom->createElement($key);
                    }
                }
            } else {
                if ( isset($filterArray) && in_array($key, $filterArray) ) {
                } else {
                     $node = $dom->createElement($key, (string)$value);
                }
            }
        } else {
            $node = $dom->createElement($key);
            if($value != NULL) {
                foreach($value as $key => $value) {
                    $sub = self::createNode( $dom, $key, $value );
                    if($sub != NULL){
                        $node->appendChild($sub);
                    }
                }
            }
        }
        return $node;
    }
    //</editor-fold>

    /**
     * 将数组转为对象
     * @param array 原数组
     * @param datobject 需转换的对象
     * @param boolean $isAll 是否包含所有的属性【包括没有赋值的属性】
     */
    public static function array_to_object($array = array() , $object = null, $isAll = true)
    {
        if ( !empty($array) ) {
            if ( is_string($object) ) {
                if ( class_exists($object) ) {
                    $class = new ReflectionClass($object);
                    $data  = $class->newInstance();
                } else {
                    return null;
                }
            } else if ( is_object($object) ) {
                if ( ($object instanceof DataObject) || ($object instanceof RemoteObject) ) {
                    $data = $object;
                } else {
                    return null;
                }
            } else {
                return null;
            }

            foreach ($array as $akey => $aval) {
                if (is_string($akey)) {//&&property_exists($data, $akey)
                    if ( method_exists($data, 'set' . ucfirst($akey)) ) {
//            $data->{'set'.ucfirst($akey)}($aval);
                        if ( $isAll ) {
                            $data->{$akey} = $aval;
                        } else {
                            if ( !empty($aval) ) {
                                 $data->{$akey} = $aval;
                            }
                        }
                    } else {
                        if ( isset($data) && array_key_exists($akey, get_class_vars($data->classname())) ) {
                            if ( $isAll ) {
                                $data->$akey = $aval;
                            } else {
                                if ( !empty($aval) ) {
                                     $data->$akey = $aval;
                                }
                            }
                        }
                    }
                }
            }
            if ( $data ) {
                //去除数据对象规格定义
                if ( ($data instanceof DataObject) || ($data instanceof RemoteObject) ) {
                    unset($data->field_spec);
                    unset($data->real_fieldspec);
                }
            }
            return $data;
        }
        return null;
    }

    /**
     * 将对象转为数组
     * @param DataObject $obj
     * @param boolean $isAll 是否包含所有的属性【包括没有赋值的属性】
     * @param array $charset 需要转换字符集进行设置；$key->原字符集，$value->新字符集
     *       示例 $charset=array('utf8'=>'gbk');
     * @return array  返回数组
     */
    public static function object_to_array($obj, $isAll = false, $charset = null)
    {
        if ( is_object($obj) ) {
            $clone = (array) $obj;
            $rtn = array ();
            $rtn['source_keys'] = $clone;

            while ( list ($key, $value) = each ($clone) ) {
                $aux    = explode ("\0", $key);
                $newkey = $aux[count($aux)-1];
                if ( $isAll ) {
                    $rtn[$newkey] = $rtn['source_keys'][$key];
                }else {
                    if ( isset($rtn['source_keys'][$key]) || is_bool($rtn['source_keys'][$key]) ) {
                        $rtn[$newkey] = $rtn['source_keys'][$key];
                        if ( $rtn[$newkey] === false ) $rtn[$newkey] = 0;
                    }
                }
            }
            unset($rtn['source_keys']);
            if ( !empty($rtn) ) {
                foreach ($rtn as $element) {
                    if ( !empty($charset) ) {
                        if ( is_array($charset) ) {
                             $element = iconv(key($charset), current($charset), $element);
                        }
                    }
                }
            }
            if ( $obj instanceof DataObject ) {
                $rtn = DataObjectSpec::removeNotObjectDataField( $rtn, $obj );
            }
            return $rtn;
        }else {
            return null;
        }
    }

    /**
     * 从源对象|数组复制属性到目标对象|数组
     * @param array|object $dest 目标对象|数组
     * @param array|object $source 源对象|数组
     */
    public static function copyProperties($dest, $source)
    {
        if ( $dest ) {
            if ( is_array($source) || is_object($source) ) {
                foreach ($source as $key => $value) {
                    if ( is_array($dest) ) {
                        if ( array_key_exists($key,$dest) )
                        {
                            $dest[$key] = $value;
                        }
                    } else if ( is_object($dest) ) {
                        if ( property_exists($dest,$key) )
                        {
                            $dest->$key = $value;
                        }
                    }
                }
            }
            return $dest;
        }
        return $source;
    }
}
?>
