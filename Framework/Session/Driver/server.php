<?php

namespace Framework\Session\Driver{
    use Framework\Session as Session;

    class Server extends Session\Driver{
        /**
         * @readwrite
         */
        protected $_prefix = 'app_';

        public function __construct($options = array()){
            parent::__construct($options);
            session_start();
        }

        public function __destruct(){
            session_commit();
        }

        public function set($key, $value){
            $prefix = $this->getPrefix();
            $_SESSION[$prefix.$key] = $value;
            return $this;
        }

        public function get($key,$default=null){
            $prefix = $this->getPrefix();
            if(isset($_SESSION[$prefix.$key])){
                return $_SESSION[$prefix.$key];
            }
            return $default;
        }

        public function erase($key){
            $prefix = $this->getPrefix();
            unset($_SESSION[$prefix.$key]);
            return $this;
        }
    }
}