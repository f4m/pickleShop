<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
     
    include_once '../../config/Database.php';
    include_once '../../models/Order.php';

    $database = new Database();
    $db = $database->connect();

    
    $order = new Order($db);

    $order->order_id = isset($_GET['order_id']) ? $_GET['order_id'] : die();

    $result = $order->generateInvoice();

    $invoice = array();
    $invoice['name'] = array();
    $invoice['prices'] = array();
    $invoice['quantity'] = array();
    
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $invoice['id'] = $id;
        $invoice['address'] = $address;
        $invoice['phone'] = $phone;
        array_push($invoice['name'], $name);
        array_push($invoice['prices'], $PRICE);
        array_push($invoice['quantity'], $quantity);
    }
    if (strpos($invoice['address'], 'dhaka')) {
        $invoice['delivery_charge'] = 60;
    }
    else {
        $invoice['delivery_charge'] = 100;
        
    }
    $invoice['Total Price'] = array_sum($invoice['prices']) + $invoice['delivery_charge'];
        
    echo json_encode($invoice);
    
    
    
    ?>