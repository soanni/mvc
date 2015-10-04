<?php

namespace Framework{
    use Framework\Base as Base;
    use Framework\View as View;
    use Framework\Registry as Registry;
    use Framework\Template as Template;
    use Framework\Controller\Exception as Exception;

    class Controller extends Base{
        /**
         * @readwrite
         */
        protected $_parameters;
        /**
         * @readwrite
         */
        protected $_layoutView;
        /**
         * @readwrite
         */
        protected $_actionView;
        /**
         * @readwrite
         */
        protected $_willRenderLayoutView = true;
        /**
         * @readwrite
         */
        protected $_willRenderActionView = true;
        /**
         * @readwrite
         */
        protected $_defaultPath = 'application\views';
        /**
         * @readwrite
         */
        protected $_defaultLayout = 'layouts\standard';
        /**
         * @readwrite
         */
        protected $_defaultExtension = 'html';
        /**
         * @readwrite
         */
        protected $_defaultContentType = 'text/html';

        public function __construct($options = array()){
            parent::__construct($options);
            if($this->getWillRenderLayoutView()){
                $defaultPath = $this->getDefaultPath();
                $defaultLayout = $this->getDefaultLayout();
                $defaultExtension = $this->getDefaultExtension();
                $view = new View(array(
                    'file'=>APP_PATH . "\\{$defaultPath}\\{$defaultLayout}.{$defaultExtension}"
                ));
                $this->setLayoutView($view);
            }
            if($this->getWillRenderActionView()){
                $router = Registry::get('router');
                $controller = $router->getController();
                $action = $router->getAction();
                $view = new View(array(
                    'file'=>APP_PATH . "\\{$defaultPath}\\{$controller}\\{$action}.{$defaultExtension}"
                ));
                $this->setActionView($view);
            }
        }

        public function __destruct(){
            $this->render();
        }

        public function render(){
            $defaultContentType = $this->getDefaultContentType();
            $actionView = $this->getActionView();
            $layoutView = $this->getLayoutView();
            $results = null;
            $doAction = $this->getWillRenderActionView() && $actionView;
            $doLayout = $this->getWillRenderLayoutView() && $layoutView;
            try{
                if($doAction){
                    $results = $actionView->render();
                }
                if($doLayout){
                    $layoutView->set('template',$results);
                    $results = $layoutView->render();
                    header("Content-type: {$defaultContentType}");
                    echo $results;
                }elseif($doAction){
                    header("Content-type: {$defaultContentType}");
                    echo $results;
                    $this->setWillRenderLayoutView(false);
                    $this->setWillRenderActionView(false);
                }
            }catch(\Exception $e){
                throw new View\Exception\Renderer("Invalid layout/template syntax");
            }
        }
        /////////////////////////////////////
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
        protected function _getExceptionForArgument()
        {
            return new Exception\Argument('Invalid argument');
        }
    }
}