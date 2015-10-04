<?php

namespace Framework\Router{
    use Framework\Base as Base;
    use Framework\Router\Exception as Exception;

    class Route extends Base{
        /**
         * @readwrite
         */
        protected $_pattern;
        /**
         * @readwrite
         */
        protected $_controller;
        /**
         * @readwrite
         */
        protected $_action;
        /**
         * @readwrite
         */
        protected $_parameters = array();

        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method is not implemented");
        }


    }
}