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
    $invoice['preOrder_discount'] = "no";
    
    //for preorder
    $preInvoice = array();
    $preInvoice['avaiable_unit'] = array();
    $preInvoice['update_needed'] = array();
    $preInvoice['availability'] = array();
    
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $invoice['id'] = $id;
        $invoice['address'] = $address;
        $invoice['phone'] = $phone;
        array_push($invoice['name'], $name);
        array_push($invoice['prices'], $PRICE);
        array_push($invoice['quantity'], $quantity);
        // array_push($preInvoice['avaiable_unit'], $avaiable_unit);
        // array_push($preInvoice['update_needed'], $avaiable_unit - $quantity);
        if($available_unit - $quantity>=0){
            array_push($preInvoice['availability'], "yes");
        }
        else{
            array_push($preInvoice['availability'], "no");
            $invoice['preOrder_discount'] = "yes";
        }
    }

    if (strpos($invoice['address'], 'dhaka')) {
        $invoice['delivery_charge'] = 60;
    }
    else {
        $invoice['delivery_charge'] = 100;
        
    }
    if($invoice['preOrder_discount'] == "yes") {
        $invoice['Total Price'] = array_sum($invoice['prices']) + $invoice['delivery_charge'];
        $invoice['Discounted Price'] = (array_sum($invoice['prices']) + $invoice['delivery_charge']) * .2 ;
        
    }
    $invoice['Total Price'] = array_sum($invoice['prices']) + $invoice['delivery_charge'];
        
    echo json_encode($invoice);
    
    
    
    ?>