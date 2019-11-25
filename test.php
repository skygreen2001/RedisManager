<?php
require_once ("init.php");
// 默认redis缓存
$serverCache = BBCache::singleton()->server();
$serverCache->TestRun();
// $id = array("BaseTaskDTO_126244","BaseTaskDTO_13199");
// $cache = $serverCache->gets($id);
// $cache = $serverCache->get("TripDTO_8550_FinishTeamRank");
// print_r($cache);
// $type = $serverCache->getKeyType("TripDTO_8550_FinishTeamRank");
// print_r($type);
// $cache = $serverCache->dbInfos();
// print_pre($cache, true);
// $id = "redisson__timeout__set__{com.itt.task.domain.Team}"; // "ittr-user";
// $cache = $serverCache->select(0);
// $cache = $serverCache->getKeyType($id);
// $cache = $serverCache->get($id);
// print_r($cache);

if ( !array_key_exists('HTTP_HOST', $_SERVER) || contains( $_SERVER['HTTP_HOST'], array("127.0.0.1", "localhost", "192.168.") ) ) {
    phpinfo();
}
