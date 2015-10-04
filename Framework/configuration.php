<?php

namespace Framework{
    use Framework\Base as Base;
    use Framework\Configuration as Configuration;
    use Framework\Configuration\Exception as Exception;

    // Configuration Factory class //////////////
    class Configuration extends Base{
        /**
         * @readwrite
         */
        protected $_type;
        /**
         * @readwrite
         */
        protected $_options;

        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method is not implemented");
        }

        public function initialize(){
            if(!$this->_type){
                throw new Exception\Argument('Invalid type');
            }
            switch($this->_type){
                case 'ini': {
                    return new Configuration\Driver\Ini($this->_options);
                    break;
                }
                default: {
                    throw new Exception\Argument('Invalid type');
                    break;
                }
            }
        }


    }
}