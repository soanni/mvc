<?php

namespace Framework\Database\Connector{
    use Framework\Database as Database;
    use Framework\Database\Exception as Exception;

    class MySql extends Database\Connector{
        /**
         * @readwrite
         */
        protected $_service;
        /**
         * @readwrite
         */
        protected $_host;
        /**
         * @readwrite
         */
        protected $_username;
        /**
         * @readwrite
         */
        protected $_password;
        /**
         * @readwrite
         */
        protected $_schema;
        /**
         * @readwrite
         */
        protected $_port;
        /**
         * @readwrite
         */
        protected $_charset;
        /**
         * @readwrite
         */
        protected $_engine = "InnoDB";
        /**
         * @readwrite
         */
        protected $_isConnected = false;

        //////////////////////////

        public function __construct($options = array()){
            parent::__construct($options);
            //$this->_connect();
        }

//        public function __destruct(){
//            $this->_disconnect();
//        }

        public function query(){
            return new Database\Query\MySql(array("connector"=>$this));
        }

        public function execute($sql){
            if(!$this->_isValidService()){
                throw new Exception\Service("Not connected to a valid service (database)");
            }
            return $this->_service->query($sql);
        }

        public function escape($value){
            if(!$this->_isValidService()){
                throw new Exception\Service("Not connected to a valid service (database)");
            }
            return $this->_service->real_escape_string($value);
        }

        public function getLastInsertId(){
            if(!$this->_isValidService()){
                throw new Exception\Service("Not connected to a valid service (database)");
            }
            return $this->_service->insert_id;
        }

        public function getAffectedRows(){
            if(!$this->_isValidService()){
                throw new Exception\Service("Not connected to a valid service (database)");
            }
            return $this->_service->affected_rows;
        }

        public function getLastError(){
            if(!$this->_isValidService()){
                throw new Exception\Service("Not connected to a valid service (database)");
            }
            return $this->_service->error;
        }

        public function sync($model){
            $lines = array();
            $indices = array();
            $columns = $model->columns;
            $template = "CREATE TABLE `%s` (\n%s,\n%s\n) ENGINE=%s DEFAULT CHARSET=%s;";

            foreach ($columns as $column){
                $raw = $column["raw"];
                $name = $column["name"];
                $type = $column["type"];
                $length = $column["length"];

                if ($column["primary"]){
                    $indices[] = "PRIMARY KEY (`{$name}`)";
                }
                if ($column["index"]){
                    $indices[] = "KEY `{$name}` (`{$name}`)";
                }

                switch ($type){
                    case "autonumber":
                    {
                        $lines[] = "`{$name}` int(11) NOT NULL AUTO_INCREMENT";
                        break;
                    }
                    case "text":
                    {
                        if ($length !== null && $length <= 255)
                        {
                            $lines[] = "`{$name}` varchar({$length}) DEFAULT NULL";
                        }
                        else
                        {
                            $lines[] = "`{$name}` text";
                        }
                        break;
                    }
                    case "integer":
                    {
                        $lines[] = "`{$name}` int(11) DEFAULT NULL";
                        break;
                    }
                    case "decimal":
                    {
                        $lines[] = "`{$name}` float DEFAULT NULL";
                        break;
                    }
                    case "boolean":
                    {
                        $lines[] = "`{$name}` tinyint(4) DEFAULT NULL";
                        break;
                    }
                    case "datetime":
                    {
                        $lines[] = "`{$name}` datetime DEFAULT NULL";
                        break;
                    }
                }
            }

            $table = $model->table;
            $sql = sprintf(
                $template,
                $table,
                join(",\n", $lines),
                join(",\n", $indices),
                $this->_engine,
                $this->_charset
            );

            $result = $this->execute("DROP TABLE IF EXISTS {$table};");
            if ($result === false){
                $error = $this->lastError;
                throw new Exception\Sql("There was an error in the query: {$error}");
            }

            $result = $this->execute($sql);
            if ($result === false){
                $error = $this->lastError;
                throw new Exception\Sql("There was an error in the query: {$error}");
            }

            return $this;
        }

        //////////////////////
        protected function _isValidService(){
            $isEmpty = empty($this->_service);
            $isInstance = $this->_service instanceof \mysqli;
            if($this->_isConnected && $isInstance && !$isEmpty){
                return true;
            }
            return false;
        }

        public function _connect(){
            if(!$this->_isValidService()){
                $this->_service = new \mysqli($this->_host
                    ,$this->_username
                    ,$this->_password
                    ,$this->_schema
                    ,$this->_port);
                if($this->_service->connect_error){
                    throw new Exception\Service("Unable to connect to service (database)");
                }
                $this->_isConnected = true;
            }
            return $this;
        }

        public function _disconnect(){
            if($this->_isValidService()){
                $this->_isConnected = false;
                $this->_service->close();
            }
            return $this;
        }
    }
}