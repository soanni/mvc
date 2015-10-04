<?php

namespace Framework\Router\Route{
    use Framework\Router as Router;
    use Framework\ArrayMethods as ArrayMethods;

    class Simple extends Router\Route{
        public function matches($url){
            $pattern = $this->_pattern;
            preg_match_all("#:([a-zA-Z0-9]+)#",$pattern,$keys);
            if(count($keys) && count($keys[0]) && count($keys[1])){
                $keys = $keys[1];
            }else{
                return preg_match("#^{$pattern}$#", $url);
            }

            $pattern = preg_replace("#(:[a-zA-Z0-9]+)#", "([a-zA-Z0-9-_]+)", $pattern);
            preg_match_all("#^{$pattern}$#", $url, $values);
            if(count($values) && count($values[0]) && count($values[1])){
                unset($values[0]);
                $derived = array_combine($keys,ArrayMethods::flatten($values));
                $this->_parameters = array_merge($this->_parameters,$derived);
                return true;
            }
            return false;
        }
    }
}