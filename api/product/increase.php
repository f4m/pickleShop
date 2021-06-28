<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
    

    include_once '../../config/Database.php';
    include_once '../../models/product.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate new product object
    $prod = new Product($db);

    //getting raw data
    $data = json_decode(file_get_contents("php://input"));

    $prod->id = $data->id;
    $prod->available_unit = $data->available_unit;

    //increase product unit
    if($prod->increaseStock()) {
        echo json_encode(
            array('message' => 'available unit increased')
        );
    } 
    else {
        echo json_encode(
            array('message' => 'product unit couldnt be updated')
        );
    }
?>