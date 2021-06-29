<?php 
    class Order {
        private $conn;
        private $table1 = 'order_tbl';
        private $table2 = 'product_order_line';
        
        //table1 properties
        public $id;
        public $phone;
        public $address;
        public $delivery_status;
        public $total_price;
        
        //table2 properties
        public $order_id;
        public $product_id;
        public $quantity;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        private function prepareStatement($querry) {
            return $this->conn->prepare($querry);
            
        }

        public function calculateTotalPrice() {

        }

        
        public function executeOrder() {
            $query1 = 'INSERT INTO '. $this->table1 . ' 
            SET id = :id, phone = :phone, address = :address,
            delivery_status = :delivery_status, total_price = :total_price';

            $query2 = 'INSERT INTO '. $this->table2 . ' 
            SET order_id = :id, product_id = :product_id, quantity = :quantity';

            $stmt1 = $this->prepareStatement($query1);
            $stmt2 = $this->prepareStatement($query2);

            $stmt1->bindParam(':id', $this->id);
            $stmt1->bindParam(':phone', $this->phone);
            $stmt1->bindParam(':address', $this->address);
            $stmt1->bindParam(':delivery_status', $this->delivery_status);
            $stmt1->bindParam(':total_price', $this->total_price);
            
            
            $stmt2->bindParam(':id', $this->id);


            if($stmt1->execute() ) {
                foreach(array_combine($this->product_id, $this->quantity) as $id => $quantity) {
                    $stmt2->bindParam(':product_id', $id);
                    $stmt2->bindParam(':quantity', $quantity);
                    $stmt2->execute();
                }
                
                return true;
            }
            printf("Error: %s.\n", $stmt1->error);
            printf("Error: %s.\n", $stmt2->error);

            return false;

        }

        public function generateInvoice() {
            $query = 'SELECT 
            ol.product_id, 
            ol.quantity, 
            p.name, 
            p.price*ol.quantity AS PRICE, 
            ot.id, 
            ot.address, 
            ot.phone 
            FROM '. $this->table2. ' ol JOIN '. $this->table1. ' ot 
            ON ol.order_id = ot.id 
            JOIN product p
            ON ol.product_id = p.id 
            WHERE ol.order_id = :order_id';

            $stmt = $this->prepareStatement($query);
            $stmt->bindParam(':order_id', $this->order_id);
            $stmt->execute();
            return $stmt;
            
        }






    }




?>