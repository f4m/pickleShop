<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    include_once '../../config/Database.php';
    include_once '../../models/Order.php';

    $database = new Database();
    $db = $database->connect();

    $order = new Order($db);

    $data = json_decode(file_get_contents("php://input"));

    $order->id = $data->id;
    $order->delivery_status = $data->delivery_status;

    if($data->delivery_status) {
        if($order->updateOnDelivery()) {
            echo json_encode(
                array('message' => 'delivered and updated')
            );
        } 
        else {
            echo json_encode(
                array('message' => 'delivered but not updated')
            );
        }
    }
    else {
        echo json_encode(
            array('message' => 'Not delivered')
        );
    }
    


?>