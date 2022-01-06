<?php
require_once ("init.php");

// 默认redis缓存
// $serverCache = BBCache::singleton()->server();
// $serverCache->TestRun();
// $now = time();
// echo "now:::" . $now ;
// $id = array("h_1","h-2");
// $cache = $serverCache->gets($id);
// print_r($cache);
// $cache = $serverCache->get(h_3");
// print_r($cache);
// $type = $serverCache->getKeyType("h");
// print_r($type);
// $cache = $serverCache->dbInfos();
// print_pre($cache, true);
// $cache = $serverCache->select(0);
// $cache = $serverCache->getKeyType("h");
// $cache = $serverCache->get("h");
// print_r($cache);

if ( !array_key_exists('HTTP_HOST', $_SERVER) || contains( $_SERVER['HTTP_HOST'], array("127.0.0.1", "localhost", "192.168.", ".test") ) ) {
    phpinfo();
}
