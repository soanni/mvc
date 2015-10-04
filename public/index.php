<?php

    define("APP_PATH", dirname(dirname(__FILE__)));
    require("../framework/core.php");
    Framework\Core::initialize();
    $configuration = new Framework\Configuration(array("type" => "ini"));
    Framework\Registry::set("configuration", $configuration->initialize());
    $database = new Framework\Database();
    Framework\Registry::set("database", $database->initialize());
    $cache = new Framework\Cache();
    Framework\Registry::set("cache", $cache->initialize());
    $session = new Framework\Session();
    Framework\Registry::set("session", $session->initialize());
    $router = new Framework\Router(array(
        "url" => isset($_GET["url"]) ? $_GET["url"] : "index/index",
        "extension" => isset($_GET["extension"]) ? $_GET["extension"] : "html"
    ));
    Framework\Registry::set("router", $router);
    $router->dispatch();

    unset($configuration);
    unset($database);
    unset($cache);
    unset($session);
    unset($router);