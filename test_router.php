<?php

require_once('autoloader.php');

try{
    $router = new \Framework\Router();
    $router->addRoute(new \Framework\Router\Route\Simple(array("pattern"=>":name/profile"
                                                                ,"controller"=>"home"
                                                                ,"action"=>"index")));
    $router->url = "chris/profile";
    $router->dispatch();
}catch(Exception $e){
    echo $e->getMessage();
}
