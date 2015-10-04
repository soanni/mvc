<?php

function autoload($class){
    $paths = explode(PATH_SEPARATOR,get_include_path());
    $file = strtolower(str_replace('\\',DIRECTORY_SEPARATOR,trim($class,'\\'))) . '.php';
    foreach($paths as $path){
        $combined = $path . DIRECTORY_SEPARATOR . $file;
        if(file_exists($combined)){
            include($combined);
            return;
        }
    }
    throw new Exception("{$class} is not found");
}

class Autoloader{
    public static function autoload($class){
        autoload($class);
    }
}

spl_autoload_register('autoload');
spl_autoload_register(array('Autoloader','autoload'));