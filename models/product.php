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
        public $updated_unit;

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
            $query = 'INSERT INTO '. $this->table . ' 
            SET name = :name, price = :price, available_unit = :available_unit, taste = :taste';

            //prep statement
            $stmt = $this->prepareStatement($query);

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

        //get stock number
        private function getStockNumber() {
            $query = 'SELECT available_unit FROM ' . $this->table. ' 
            WHERE id = :id';

            $stmt = $this->prepareStatement($query);
            
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            $stmt->bindParam('id', $this->id);
            
            $stmt->execute();            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['available_unit'];
        } 

        //increase stock
        public function increaseStock() {
            //query 
            $query = 'UPDATE ' . $this->table . ' 
            SET available_unit = :updated_unit 
            WHERE id = :id';
            
            $stmt = $this->prepareStatement($query);
            
            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->available_unit = htmlspecialchars(strip_tags($this->available_unit));
            
            $updated_unit = $this->getStockNumber() + $this->available_unit;
            //bind parameter
            $stmt->bindParam(':updated_unit', $updated_unit);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;

            
        }


    }

?>