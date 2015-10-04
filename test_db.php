<?php

require_once('autoloader.php');

try{
    $database = new Framework\Database(array("type"=>"mysql"
                                            ,"options"=>array("host"=>"localhost"
                                                              ,"username"=>"root"
                                                              ,"password"=>""
                                                              ,"schema"=>"prophpmvc"
                                                              ,"port"=>"3306")));
    $database = $database->initialize();
   //$database->connect();
    $all = $database->query()->from("users",array('first_name','last_name'=>'surname'))
        ->join('points',"points.id = users.id", array('points'=>'rewards'))
        ->where('first_name = ?','Andrey')
        ->order('last_name','desc')
        ->limit(100)
        ->all();
    $print = print_r($all,true);
    echo "all=>{$print}</br>";
    $id = $database->query()->from('users')->save(array('first_name'=>'Liz','last_name'=>'Pitt'));
    echo "id => {$id}</br>";
    $affected = $database->query()->from('users')->where('first_name = ?', 'Liz')->delete();
    echo "affected => {$affected}</br>";
    $id = $database->query()->from('users')->where('first_name = ?', 'Andrey')->save(array('modified'=>date("Y-m-d H:i:s")));
    echo "id => {$id}</br>";
    $count = $database->query()->from('users')->count();
    echo "count => {$count}</br>";
}catch(Exception $e){
    echo $e->getMessage();
}