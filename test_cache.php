<?php

require_once('autoloader.php');

try{
    $cache = new Framework\Cache(array("type"=>"memcached"));
    $driver = $cache->initialize();
    $driver->connect();
    $driver->set('test','12345');
    echo $driver->get('test');
}catch(Exception $e){
    echo $e->getMessage();
}