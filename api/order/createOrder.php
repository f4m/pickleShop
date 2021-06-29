<?php 
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Order.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate new product object
    $order = new Order($db);

    //getting raw data
    $data = json_decode(file_get_contents("php://input"));


    $order->id = uniqid('ORD');
    $order->phone = $data->phone;
    $order->address = $data->address;
    $order->delivery_status = $data->delivery_status;
    $order->product_id = $data->product_id;
    $order->quantity = $data->quantity;
    $order->total_price = $data->total_price;
    

    if($order->executeOrder()) {
        echo json_encode(
            array('mesage' => 'Order has been executed')
        );
    }
    else {
        echo json_encode(
            array('message' => 'Couldnt order')
        );
    }

?>