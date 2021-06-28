<?php 
    class Database {
        private $host = 'localhost';
        private $db_name = 'picleshop';
        private $username = 'root';
        private $password = '';
        private $conn;

        //DB connect

        public function connect()
        {
            $this->conn = null;
            $dsn = 'mysql:host='. $this->host. 
                    ';dbname='.$this->db_name;
            try {
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection error' . $e->getMessage();
            }

            return $this->conn;
        }
    }

?>