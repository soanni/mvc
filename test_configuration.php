<?php

require_once('autoloader.php');
try{
    $conf = new Framework\Configuration(array('type'=>'ini'));
    $driver = $conf->initialize();
    $driver->parse('_configuration');
    echo '<pre>';
    print_r($driver->parsed);
    echo '</pre>';
}catch(Exception $e){
    echo $e->getMessage();
}

