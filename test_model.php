<?php

namespace Framework{
    require_once('autoloader.php');

    $database = new Database(array("type"=>"mysql"
    ,"options"=>array("host"=>"localhost"
        ,"username"=>"root"
        ,"password"=>""
        ,"schema"=>"prophpmvc"
        ,"port"=>"3306"
        ,"charset"=>"UTF8")));
    $mysql = $database->initialize();
    Registry::set('database',$mysql);
    $mysql->sync(new User());

    $elijah = new User(array("connector" => $mysql,
        "first" => "Chris",
        "last" => "Pitt",
        "email" => "chris@example.com",
        "password" => "password",
        "notes" => "testtesttest",
        "live" => true,
        "deleted" => false,
        "created" => date("Y-m-d H:i:s"),
        "modified" => date("Y-m-d H:i:s")
    ));
    $elijah->save();
    $all = User::all(array('last = ?'=>'Pitt'));
    echo '<pre>';
    print_r($all);
    echo '</pre>';
    $elijah->delete();
}

