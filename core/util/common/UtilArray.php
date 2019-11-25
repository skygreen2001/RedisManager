<?php
/**
 +---------------------------------<br/>
 * 工具类：数组<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilArray extends Util
{
    //<editor-fold defaultstate="collapsed" desc="array and xml">
    /**
     * 将数组类型转换成xml<br/>
     * 参考:Array2XML:http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes/<br/>
     * 在数组里添加@attributes,@value,@cdata;可以添加Xml中Node的属性，值和CDATA<br/>
     * The main function for converting to an XML document.<br/>
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.<br/>
     * @example
     * 示例：<br/>
     *     $data = array("id" => "8", "member_id" => "5", "app_name" => "mall", "username" => "pass", "relation" => array("Role" => "roleId", "Function" => "functionId"));<br/>
     *     $data = array("a", "b", "c", "d", "e" => array("a", "b", "c"));<br/>
     *     echo UtilArray::array_to_xml( $data, 'Member' );<br/>
     * 完整的示例[包括@attributes,@value,@cdata]:<br/>
     *         $classes = array(
     *             "class" => array(
     *                "conditions" => array(
     *                    "condition" => array(
     *                       array('@cdata' => 'Stranger in a Strange Land'),
     *                       array(
     *                            '@attributes' => array(
     *                                "relation_class" => "Blog",
     *                                 "show_name" => "title"
     *                            ),
     *                            '@value' => "blog_id"
     *                        ),
     *                        array(
     *                            "@value" => "comment_name"
     *                        )
     *                    )
     *                )
     *            )
     *        );
     * 生成xml如下：<br/>
     * <?xml version="1.0" encoding="utf-8"?>
     * <classes>
     *     <class>
     *         <conditions>
     *             <condition>
     *                 <comment><![CDATA[Stranger in a Strange Land]]></comment>
     *                 <condition relation_class="Blog" show_name="title">blog_id</condition>
     *                 <condition>comment_name</condition>
     *             </condition>
     *         </conditions>
     *     </class>
     * </classes>
     * @link http://snipplr.com/view/3491/convert-php-array-to-xml-or-simple-xml-object-if-you-wish/
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML xml内容
     */
    public static function array_to_xml($data, $rootNodeName = 'data', &$xml = null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if ( ini_get('zend.ze1_compatibility_mode') == 1 ) ini_set ( 'zend.ze1_compatibility_mode', 0 );
        if ( is_null( $xml ) ) $xml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");

        // loop through the data passed in.
        foreach( $data as $key => $value ) {
            // no numeric keys in our xml please!
            if ( is_numeric( $key ) ) {
                $key = $rootNodeName;
            }

            if ( $key == '@attributes' ) {
                foreach ($value as $key_attr => $value_attr) {
                    $xml->addAttribute($key_attr, $value_attr);
                }
                continue;
            }
            if ( $key == "@value" ) {
                $xml[0] = $value;
                continue;
            }

            if ( $key == "@cdata" ) {
                $node = dom_import_simplexml($xml);
                $no   = $node->ownerDocument;
                $node->appendChild($no->createCDATASection($value));
                continue;
            }
            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            if ( is_object($value) ) {
                $value = get_object_vars($value);
            }

            // if there is another array found recrusively call this function
            if ( is_array($value) ) {
                $node = self::is_assoc( $value ) || is_numeric($value) ? $xml->addChild($key) : $xml;
                self::array_to_xml( $value, $key, $node );
            } else {
                // add single node.
                $value = htmlentities($value, ENT_COMPAT, "UTF-8");
                $xml->addChild($key, $value);
            }
        }

        // pass back as XML
        //return $xml->asXML();

        // if you want the XML to be formatted, use the below instead to return the XML
        $doc = new DOMDocument('1.0', "UTF-8");
        $doc->preserveWhiteSpace = false;
        $doc->loadXML( $xml->asXML() );
        $doc->formatOutput = true;
        return $doc->saveXML();
    }

    /**
     * 转换数组保存符合规范的XML到指定的文件
     * @param array $filename 文件名
     * @param array $data 符合cml格式的数据
     * @example
     * 示例：<br/>
     *     $data=array("id"=>"8","member_id"=>"5","app_name"=>"mall","username"=>"pass","relation"=>array("Role"=>"roleId","Function"=>"functionId"));<br/>
     *     $data=array("a","b","c","d","e"=>array("a","b","c"));<br/>
     *     echo UtilArray::array_to_xml($data, 'Member');<br/>
     * 完整的示例[包括@attributes,@value,@cdata]:<br/>
     *         $classes = array(
     *             "class" => array(
     *                "conditions" => array(
     *                    "condition" => array(
     *                       array('@cdata' => 'Stranger in a Strange Land'),
     *                       array(
     *                            '@attributes' => array(
     *                                "relation_class" => "Blog",
     *                                 "show_name" => "title"
     *                            ),
     *                            '@value' => "blog_id"
     *                        ),
     *                        array(
     *                            "@value" => "comment_name"
     *                        )
     *                    )
     *                )
     *            )
     *        );
     * 生成xml如下：<br/>
     * <?xml version="1.0" encoding="utf-8"?>
     * <classes>
     *     <class>
     *         <conditions>
     *             <condition>
     *                 <comment><![CDATA[Stranger in a Strange Land]]></comment>
     *                 <condition relation_class="Blog" show_name="title">blog_id</condition>
     *                 <condition>comment_name</condition>
     *             </condition>
     *         </conditions>
     *     </class>
     * </classes>
     * @param string $rootNodeName - 根节点的名称 - 默认:data.
     */
    public static function saveXML($filename, $data, $rootNodeName = 'data')
    {
        $result = UtilArray::array_to_xml( $data, $rootNodeName );
        $result = str_replace("  ", "    ", $result);
        $result = str_replace("    ", "    ", $result);
        file_put_contents($filename, $result);
    }

    /**
     * Convert an XML document to a multi dimensional array<br/>
     * Pass in an XML document (or SimpleXMLElement object) and this recrusively loops through and builds a representative array<br/>
     * 示例：<br/>
     *     $data = array("id" => "8", "member_id" => "5", "app_name" => "mall", "username" => "pass", "relation" => array("Role" => "roleId", "Function" => "functionId"));<br/>
     *     $data = array("a", "b", "c", "d", "e" => array("a", "b", "c"));<br/>
     *     $xml = UtilArray::array_to_xml( $data, 'Member' );<br/>
     *     print_r(UtilArray::xml_to_array( $xml, 'Member' ))<br/>
     * @link http://snipplr.com/view/3491/convert-php-array-to-xml-or-simple-xml-object-if-you-wish/
     * @param string $xml - XML document - can optionally be a SimpleXMLElement object
     * @return array ARRAY
     */
    public static function xml_to_array($xml, $rootNodeName = 'data')
    {
        if ( is_string($xml) ){
            $xmlSxe = new SimpleXMLElement($xml);
        } else {
            $xmlSxe = $xml;
        }
        $children = $xmlSxe->children();
        if ( !$children ) {
            return (string) $xml;
        }
        $arr = array();
        // $attr_key = "@".self::XML_ELEMENT_ATTRIBUTES;
        // unset($children[$attr_key]);
        foreach ($children as $key => $node) {
            $node = self::xml_to_array( $node, $rootNodeName );

            // support for 'anon' non-associative arrays
            if ( UtilString::contain( $key, $rootNodeName . "_" ) ) {
               $key = count($arr);
            }
            // if ( $key == 'anon' ) $key = count( $arr );

            // if the node is already set, put it into an array
            if ( isset($arr[$key]) ) {
                if ( !is_array($arr[$key]) || $arr[$key][0] == null ) {
                    $arr[$key] = array( $arr[$key] );
                }
                $arr[$key][] = $node;
            } else {
                $arr[$key] = $node;
            }
        }
        return $arr;
    }

    // determine if a variable is an associative array
    public static function is_assoc($array)
    {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
    //</editor-fold>

    /**
    * 获取多重数组指定key的值<br/>
    * 当数据为多重时，可以通过点隔开的key获取指定key的值  <br/>
    * @param $array_key 中间以小数点隔开
    * @return unknown
    * @example:
    * 如$row=array("db" => array("table" => array("row" => 15)))
    *   可通过$array_multi_key = "db.table.row"获得
    */
    public static function array_multi_direct_get($array_multi, $array_multi_key)
    {
         $var = explode('.', $array_multi_key);
         $result = $array_multi;
         foreach ($var as $key) {
            if ( !isset($result[$key]) ) { return false; }
            $result = $result[$key];
         }
         return $result;
    }

    /**
     * 获取数组中指定键数组的数组
     * @param array $array 数组,如 array("key1" => 1, "key2" => 2, "key3" => 3, "key4" => 4);
     * @param string $keys 键字符串，如 "key1,key3"
     * @return array  数组中包含键数组的数组,如 array("key1" => 1, "key3" => 3);
     */
    public static function array_key_filter($array, $keys)
    {
        $return = array();
        foreach (explode(',',$keys) as $k) {
            if ( isset($array[$k]) ) {
                $return[$k] = $array[$k];
            }
        }
        return $return;
    }

    /**
     * 多维数组转为一维数组
     * @param array $array 数组
     */
    public static function array_multi2single($array)
    {
        static $result_array = array();
        foreach ($array as $value) {
            if ( is_array($value) ) {
                UtilArray::array_multi2single( $value );
            } else {
                $result_array[] = $value;
            }
        }
        return $result_array;
    }

     /**
     * 返回数组中指定值的键名称。
     * @param array $arr 数组
     * @param string $propertyValue
     * @param string $prefix 指定前缀或者后缀的名称
     * @param bool $isprefix 是否前缀，true:前缀,false:后缀
     * @return string 数组中指定值的键名称
     */
    public static function array_search($arr, $propertyValue, $pre1sufix = "", $isprefix = true)
    {
        $result = null;
        if ( isset($propertyValue) && isset($arr) && in_array($propertyValue, $arr) ) {
            if ( !empty($pre1sufix) ) {
                if ( $isprefix ) {
                    foreach ($arr as $key => $value) {
                        if ( $propertyValue == $value ) {
                            if ( startWith($key, $pre1sufix) ) {
                                return $key;
                            }
                        }
                    }
                } else {
                    foreach ($arr as $key => $value) {
                        if ( $propertyValue == $value) {
                            if ( endWith( $key, $pre1sufix ) ) {
                                return $key;
                            }
                        }
                    }
                }
            } else {
                $result = array_search($propertyValue, $arr);
            }
        }
        return $result;
    }

    /**
     * convert a multidimensional array to url save and encoded string
     *  usage: string Array2String( array Array )
     *  @link http://php.net/manual/en/ref.array.php
     */
    public static function Array2String($Array)
    {
        $Return    = '';
        $NullValue = "^^^";
        foreach ($Array as $Key => $Value) {
            if ( is_object($Value) ) {
                $Value = UtilObject::object_to_array( $Value );
            }
            if ( is_array($Value) ) {
                $ReturnValue = '^^array^'.self::Array2String($Value);
            } else {
                $ReturnValue = ( strlen($Value) > 0 ) ? $Value : $NullValue;
            }
            $Return .= urlencode(base64_encode($Key)) . '|' . urlencode(base64_encode($ReturnValue)) . '||';
        }
        return urlencode(substr($Return, 0, -2));
    }

    /**
     * convert a string generated with Array2String() back to the original (multidimensional) array
     * usage: array String2Array( string String )
     */
    public static function String2Array($String)
    {
        $Return = array();
        $String = urldecode($String);
        $TempArray = explode('||', $String);
        $NullValue = urlencode(base64_encode("^^^"));
        foreach ($TempArray as $TempValue) {
            list($Key,$Value) = explode('|', $TempValue);
            $DecodedKey = base64_decode(urldecode($Key));
            if ( $Value != $NullValue ) {
                $ReturnValue = base64_decode(urldecode($Value));
                if ( substr($ReturnValue, 0, 8) == '^^array^' ) {
                    $ReturnValue = self::String2Array( substr($ReturnValue,8) );
                }
                $Return[$DecodedKey] = $ReturnValue;
            } else {
              $Return[$DecodedKey] = NULL;
            }
        }
        return $Return;
    }

    /**
     *  return depth of given array
     * if Array is a string ArrayDepth() will return 0
     * usage: int ArrayDepth(array Array)
     */
    public static function ArrayDepth($Array, $DepthCount = -1, $DepthArray = array())
    {
        $DepthCount++;
        if ( is_array($Array) ) {
            foreach ($Array as $Key => $Value){
                $DepthArray[] = ArrayDepth($Value, $DepthCount);
            }
        } else {
            return $DepthCount;
        }
        foreach ($DepthArray as $Value){
            $Depth = $Value > $Depth ? $Value : $Depth;
        }
        return $Depth;
    }

    /**
     * 查询数组中key所在的位置
     * @param mixed $array 查询数组
     * @param mixed $key
     * @return 数组中$key所在的位置【从1开始】
     */
    public static function keyPosition($array, $key)
    {
        if ( array_key_exists($key, $array) ) {
            return array_search($key, array_keys($array)) + 1;
        } else {
            return -1;
        }
    }

    /**
     * 在数组指定位置插入值
     * @param mixed $index 指定位置【从1开始】
     * @param mixed $value
     */
    public static function insert(&$arrays, $index, $value)
    {
        $arrays = array_merge(array_slice($arrays, 0, max(0, $index - 1)), $value, array_slice($arrays, max(0, $index - 1)));
        return $arrays;
    }

    /**
     * 对类似表结构的二维数组获取指定键关键词的值
     * @param array &$array 源数组
     * @param string $key 指定键关键词
     * @example
     *
     *  $employ_table_data = array(
     *      array(
     *          "employee_id" => "1",
     *          "name" => "Wang",
     *          "month_salary" => "8000"
     *      ),
     *      array(
     *          "employee_id" => "2",
     *          "name" => "Zhang",
     *          "month_salary" => "10000"
     *      )
     *  );
     * $employee = UtilArray::get( $employ_table_data, "employee_id", "2" );
     */
    public static function get($array, $key, $needle)
    {
        foreach ($array as $key => $val) {
            if ($val[$key] === $needle) {
                return $array[$key];
            }
        }
        return null;
    }

    /**
     * 对类似表结构的二维数组进行排序
     * @param array &$two_dimension_array 源数组
     * @param string $key 指定键关键词
     * @param string $order
     *               asc:升序,desc:降序
     * @example

     * $data=array(
     *      array(
     *           'name' => 'Julie',
     *           'age' => 20
     *      ),
     *      array(
     *           'name' => 'Martin',
     *           'age' => 18
     *      ),
     *      array(
     *           'name' => 'Lucy',
     *           'age' => 100
     *      )
     * );
     * UtilArray::sort( $data, "age", "asc" );
     * UtilArray::sort( $data, "name", "desc" );
     */
    public static function sort(&$two_dimension_array, $key, $order)
    {
        $isAsc = true;
        if ( trim(strtolower($order)) == "desc" ) $isAsc = false;
        usort($two_dimension_array, build_sorter($key, $isAsc));
    }

    private static function build_sorter($key, $isAsc)
    {
        return function ($a, $b) use ($key, $isAsc) {
            $result = strnatcmp($a[$key], $b[$key]);
            if ( !$isAsc ) $result = 0 - $result;
            return $result;
        };
    }

    /**
     * 获取数组中包含有指定字符串键值对数组。
     * @param array $data 源数组
     * @param string $needle 指定的字符串，允许使用正则表达式
     * @return 包含指定字符串键值对新数组
     * 示例:
     * Array (
     *     [db0] => keys=106,expires=0,avg_ttl=0
     *     [db1] => keys=57,expires=57,avg_ttl=500072192
     *     [db2] => keys=8,expires=7,avg_ttl=513154026
     *     [db3] => keys=38,expires=30,avg_ttl=2387575246
     * )
     */
    public static function like($data, $needle) {
        $result = array();
        foreach ($data as $key => $value)
          if ( preg_match('/' . $needle . '/', $key) )
            $result[$key] = $value;
        return $result;
    }
}

?>
