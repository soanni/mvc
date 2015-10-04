<?php

namespace Framework\Database\Query{
    use Framework\Database as Database;
    use Framework\Database\Exception as Exception;

    class MySql extends Database\Query{
        public function all(){
            $sql = $this->_buildSelect();
            $result = $this->_connector->execute($sql);
            if($result === false){
                $error = $this->_connector->lastError;
                throw new Exception\Sql("There is an error in your query: {$error}");
            }
            $rows = array();
            for($i = 0; $i < $result->num_rows; $i++){
                $rows[] = $result->fetch_array(MYSQLI_ASSOC);
            }
            return $rows;
        }
    }
}