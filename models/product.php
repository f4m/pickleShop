<?php 
 
    class Product {
        //DB
        private $conn;
        private $table = 'product';
        
        //properties

        public $id;
        public $name;
        public $price;
        public $available_unit;
        public $taste;

        public function __construct($db)
        {
            $this->conn = $db;
        }
        // statement prep function
        private function prepareStatement($querry) {
            return $this->conn->prepare($querry);
            
        }

        //store product
        public function store() {
            //query
            // $query = 'INSERT INTO '. $this->table. ' (name, price, available_unit, taste) VALUES (:name, :price, :available_unit, :taste)';

            $query = 'INSERT INTO '. $this->table . ' 
            SET name = :name, price = :price, available_unit = :available_unit, taste = :taste';

            //prep statement
            $stmt = $this->prepareStatement($query);
            // $stmt = $this->conn->prepare($query);

            //clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->price = htmlspecialchars(strip_tags($this->price));
            $this->available_unit = htmlspecialchars(strip_tags($this->available_unit));
            $this->taste = htmlspecialchars(strip_tags($this->taste));

            //Bind parameter
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':available_unit', $this->available_unit);
            $stmt->bindParam(':taste', $this->taste);

            if($stmt->execute()) {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);

            return false;
        }


    }

?>