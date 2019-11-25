<?php
/**
  +---------------------------------<br/>
 * 所有缓冲策略的必须实现的接口类。<br/>
  +---------------------------------
 * @category betterlife
 * @package core.cache
 * @author skygreen
 */
interface ICache
{
    /**
    * 在缓存里保存指定$key的数据<br/>
    * 仅当存储空间中不存在键相同的数据时才保存<br/>
    * @param string $key
    * @param string|array|object $value
    * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
    * @return bool
    */
    public function save($key,$value,$expired = 86400);

    /**
     * 在缓存里保存指定$key的数据 <br/>
     * 与save和update不同，无论何时都保存 <br/>
     * @param string $key
     * @param string|array|object $value
     * @param int $expired 过期时间，默认是1天；最高设置不能超过2592000(30天)
     * @return bool
     */
     public function set($key,$value,$expired = 86400);

     /**
      * 在缓存里更新指定key的数据<br/>
      * 仅当存储空间中存在键相同的数据时才保存<br/>
      * @param string $key
      * @param string|array|object $value
      * @return bool
      */
      public function update($key,$value,$expired=86400);

      /**
       * 在缓存里删除所有指定$key的数据
       * @param string|array $key
       * @return bool
       */
       public function delete($key);

       /**
       * 清除所有的对象。
       */
       public function clear();

      /**
       * 查看键key是否存在。
       * @param string $key
       */
      public function contains($key);

      /**
       * 获取指定key的值
       * @param string $key
       * @return string|array|object
       */
      public function get($key);

      /**
       * 获取指定keys的值们。<br/>
       * 允许一次查询多个键值，减少通讯次数。
       * @param array $key
       * @return array
       */
      public function gets($keyArr);
      /**
       * 关闭缓存
       */
      public function close();
      /**
       * 测试体验Cache
       */
      public function TestRun();
}
