<?php

namespace Framework{
    use Framework\Base as Base;
    use Framework\Inspector as Inspector;
    use Framework\Registry as Registry;
    use Framework\Router\Exception as Exception;

    class Router extends Base{
        /**
         * @readwrite
         */
        protected $_url;
        /**
         * @readwrite
         */
        protected $_extension;
        /**
         * @read
         */
        protected $_controller;
        /**
         * @read
         */
        protected $_action;
        protected $_routes = array();

        public function addRoute($route){
            $this->_routes[] = $route;
            return $this;
        }

        public function removeRoute($route){
            foreach($this->_routes as $i=>$stored){
                if($stored == $route){
                    unset($this->_routes[i]);
                }
            }
            return $this;
        }

        public function getRoutes(){
            $list = array();
            foreach($this->_routes as $route){
                $list[$route->pattern] = get_class($route);
            }
            return $list;
        }

        public function dispatch(){
            $url = $this->_url;
            $parameters = array();
            $controller = "index";
            $action = "index";
            foreach($this->_routes as $route){
                $matches = $route->matches($url);
                if($matches){
                    $controller = $route->controller;
                    $action = $route->action;
                    $parameters = $route->parameters;
                    $this->_pass($controller,$action,$parameters);
                    return;
                }
            }
            $parts = explode("/",trim($url,"/"));
            if(count($parts)>0){
                $controller = $parts[0];
                if(count($parts) >= 2){
                    $action = $parts[1];
                    $parameters = array_slice($parts,2);
                }
            }
            $this->_pass($controller,$action,$parameters);
        }

        //////////////////////
        protected function _pass($controller, $action, $parameters = array()){
            $name = ucfirst($controller);
            $this->_controller = $controller;
            $this->_action = $action;
            try{
                $instance = new $name(array("parameters"=>$parameters));
                Registry::set('controller',$instance);
            }catch(\Exception $e){
                throw new Exception\Controller("Controller {$name} is not found");
            }
            if(!method_exists($instance,$action)){
                $instance->willRenderLayoutView = false;
                $instance->willRenderActionView = false;
                throw new Exception\Action("Action {$action} is not found");
            }

            $inspector = new Inspector($instance);
            $methodMeta = $inspector->getMethodMeta($action);
            if(!empty($methodMeta['@protected']) || !empty($methodMeta['@private'])){
                throw new Exception\Action("Action {$action} is not found");
            }

            $hooks = function($meta, $type) use ($inspector, $instance){
                if (isset($meta[$type])){
                    $run = array();
                    foreach ($meta[$type] as $method){
                        $hookMeta = $inspector-> getMethodMeta($method);
                        if (in_array($method, $run) && !empty($hookMeta["@once"])){
                            continue;
                        }
                        $instance->$method();
                        $run[] = $method;
                    }
                }
            };
            $hooks($methodMeta, "@before");
            call_user_func_array(array($instance, $action), is_array($parameters) ? $parameters : array());
            $hooks($methodMeta, "@after");
            Registry::erase("controller");

        }

        protected function _getExceptionForImplementation($method){
            return new Exception\Implementation("{$method} method is not implemented");
        }

    }

}