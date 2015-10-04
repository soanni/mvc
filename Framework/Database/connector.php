<?php

namespace Framework\Database{
    use Framework\Base as Base;
    use Framework\Database\Exception as Exception;

    class Connector extends Base{

        public function __construct($options = array()){
            parent::__construct($options);
        }

        public function initialize(){
            return $this;
        }

        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method is not implemented");
        }


    }
}