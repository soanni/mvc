<?php
//phpinfo();

require_once("autoloader.php");
$test_class = new Test\Validator(array('name','age','country'));
$inspector = new Framework\Inspector($test_class);
echo '<pre>';
var_dump($inspector->getClassMeta());
var_dump($inspector->getClassMethods());
var_dump($inspector->getClassProperties());
var_dump($inspector->getMethodMeta('validateInput'));
var_dump($inspector->getMethodMeta('matches'));
var_dump($inspector->getPropertyMeta('_errors'));
echo '</pre>';