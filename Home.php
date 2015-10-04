<?php

class Home extends \Framework\Controller{
    /**
     * @protected
     * @once
     */
    public function init(){
        echo "init </br>";
    }

    /**
     * @protected
     */
    public function authenticate(){
        echo "authenticate </br>";
    }

    /**
     * @before init, authenticate, init
     * @after notify
     */
    public function index(){
        echo "hello world </br>";
    }

    /**
     * @protected
     */
    public function notify(){
        echo 'notify </br>';
    }
}