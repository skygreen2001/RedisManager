<?php

/**
 +--------------------------------------------------<br/>
 * 处理缓存的方式的类型<br/>
 +--------------------------------------------------<br/>
 */
class EnumCacheDriverType extends Enum{
    const REDIS      = 1;
    const MEMCACHE   = 2;
    const PREDIS     = 3;
    const MEMCACHED  = 4;
    const SQLITE3    = 5;
    const ARRAY      = 6;
    const FILESYSTEM = 7;
    const PHPFILE    = 8;
    const MONGODB    = 9;
    const APC        = 10;
    const WINCACHE   = 11;
    const XCACHE     = 12;
    const ZENDDATA   = 13;
    const APCU       = 14;
    const CHAIN      = 15;
    const COUCHBASE  = 16;
    const RIAK       = 17;
    const VOID       = 18;
    const MEMCACHED_CLIENT = 19;
}


/**
 +--------------------------------------------------<br/>
 * 优化性能: 分布式缓存管理器<br/>
 +--------------------------------------------------<br/>
 * 使用第三方库: doctrine/cache
 * 安装: composer require doctrine/cache
 * @category betterlife
 * @package core.main
 * @author skygreen <skygreen2001@gmail.com>
 * @link https://www.doctrine-project.org/projects/cache.html
 */
class BBCache {
    /**
     * 分布式缓存管理器唯一实例
     * @var BBCache
     */
    private static $instance;
    /**
     * 缓存服务器
     *
     * @var mixed
     */
    private $cache;
    /**
     * redis缓存服务器
     *
     * @var mixed
     */
    private $redisCache;

    private function __construct()
    {
    }

    /**
     * 单例化
     * @return BBCache
     */
    public static function singleton() {
        if ( !isset(self::$instance) ) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }


    private function serverCache($cache_drive = EnumCacheDriverType::REDIS) {
        switch ($cache_drive){
           case EnumCacheDriverType::REDIS:
                $cache = new Cache_Redis();
                break;
           case EnumCacheDriverType::MEMCACHE:
                $cache = new Cache_Memcache();
                break;
           case EnumCacheDriverType::PREDIS:
                // 安装: composer require predis/predis
                $client = new Predis\Client();
                $cache = new \Doctrine\Common\Cache\PredisCache($client);
                break;
           case EnumCacheDriverType::MEMCACHED:
                $cache = new Cache_Memcached();
                break;
           // case EnumCacheDriverType::ARRAY:
           //      $cache = new ArrayCache();
           //      break;
           // case EnumCacheDriverType::SQLITE3:
           //      $db = new SQLite3('mydatabase.db');
           //      $cache = new \Doctrine\Common\Cache\SQLite3Cache($db, 'table_name');
           //      break;
           case EnumCacheDriverType::APC:
                $cache = new Cache_Apc();
                break;
           // case EnumCacheDriverType::WINCACHE:
           //      $cache = new \Doctrine\Common\Cache\WinCacheCache();
           //      break;
           // case EnumCacheDriverType::XCACHE:
           //      $cache = new \Doctrine\Common\Cache\XcacheCache();
           //      break;
           // case EnumCacheDriverType::APCU:
           //      $cache = new \Doctrine\Common\Cache\ApcuCache();
           //      break;
           // case EnumCacheDriverType::ZENDDATA:
           //      $cache = new \Doctrine\Common\Cache\ZendDataCache();
           //      break;
           // case EnumCacheDriverType::CHAIN:
           //      $arrayCache = new \Doctrine\Common\Cache\ArrayCache();
           //      $apcuCache = new \Doctrine\Common\Cache\ApcuCache();
           //      $cache = new \Doctrine\Common\Cache\ChainCache([$arrayCache, $apcuCache]);
           //      break;
           // case EnumCacheDriverType::COUCHBASE:
           //      $bucketName = 'bucket-name';
           //      $authenticator = new Couchbase\PasswordAuthenticator();
           //      $authenticator->username('username')->password('password');
           //      $cluster = new CouchbaseCluster('couchbase://127.0.0.1');
           //      $cluster->authenticate($authenticator);
           //      $bucket = $cluster->openBucket($bucketName);
           //      $cache = new \Doctrine\Common\Cache\CouchbaseBucketCache($bucket);
           //      break;
           // case EnumCacheDriverType::FILESYSTEM:
           //      $cache = new \Doctrine\Common\Cache\FilesystemCache('/path/to/cache/directory');
           //      break;
           // case EnumCacheDriverType::MONGODB:
           //      $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
           //      $collection = new MongoDB\Collection($manager, 'database_name', 'collection_name');
           //      $cache = new \Doctrine\Common\Cache\MongoDBCache($collection);
           //      break;
           // case EnumCacheDriverType::PHPFILE:
           //      $cache = new \Doctrine\Common\Cache\PhpFileCache('/path/to/cache/directory');
           //      break;
           // case EnumCacheDriverType::RIAK:
           //      $connection = new Riak\Connection('localhost', 8087);
           //      $bucket = new Riak\Bucket($connection, 'bucket_name');
           //      $cache = new \Doctrine\Common\Cache\RiakCache($bucket);
           //      break;
           // case EnumCacheDriverType::VOID:
           //      $cache = new \Doctrine\Common\Cache\VoidCache();
           //      break;
           case EnumCacheDriverType::MEMCACHED_CLIENT:
                $cache = new Cache_Memcached_Client();
                break;
        }
        $this->cache = $cache;
        return $this->cache;
    }

    /**
     * 获取缓存服务器
     * @param mixed $cache_drive 处理缓存的方式的类型,默认采用Redis
     * @return 缓存服务器
     */
    public function server($cache_drive = EnumCacheDriverType::REDIS) {
        if ( $this->cache == null ) {
            $this->cache = $this->serverCache($cache_drive);
        }
        return $this->cache;
    }


    /**
     * 获取Redis缓存服务器
     * @param string $host
     * @param string $port
     * @param string $password
     * @return Redis缓存服务器
     */
    public function redisServer($host = '', $port = '', $password = '') {
        if ( $this->redisCache == null ) {
            $this->redisCache = new Cache_Redis($host, $port, $password);
        }
        return $this->redisCache;
    }
}
