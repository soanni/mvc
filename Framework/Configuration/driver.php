<?php

namespace Framework\Configuration{
    use Framework\Base as Base;
    use Framework\Configuration\Exception as Exception;

    class Driver extends Base{
        /**
         * @readwrite
         */
        protected $_parsed = array();

        public function initialize(){
            return $this;
        }

    }
}