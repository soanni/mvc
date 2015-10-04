<?php

namespace Framework{
    use Framework\Base as Base;
    use Framework\Cache as Cache;
    use Framework\Cache\Exception as Exception;

    // cache Factory class
    class Cache extends Base{
        /**
         * @readwrite
         */
        protected $_type;
        /**
         * @readwrite
         */
        protected $_options;

        public function initialize(){
            $type = $this->getType();
            if(empty($type)){
                $configuration = Registry::get('configuration');
                if($configuration){
                    $configuration = $configuration->initialize();
                    $parsed = $configuration->parse('configuration/cache');
                    if(!empty($parsed->cache->default) && !empty($parsed->cache->default->type)){
                        $type = $parsed->cache->default->type;
                        unset($parsed->cache->default->type);
                        $this->__construct(array(
                            'type'=>$type,
                            'options'=> (array)$parsed->cache->default
                        ));
                    }
                }
            }
            if(empty($type)){
                throw new Exception\Argument('Invalid type');
            }

            switch($type){
                case 'memcached':{
                    return new Cache\Driver\Memcached($this->getOptions());
                    break;
                }
                default:{
                    throw new Exception\Argument('Invalid type');
                    break;
                }
            }
        }

        ///////////////////////////////
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method is not implemented");
        }
    }
}