<?php 
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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
    

    $prod->name = $data->name;
    $prod->price = $data->price;
    $prod->available_unit = $data->available_unit;
    $prod->taste = $data->taste;

    //storing product
    if($prod->store()) {
        echo json_encode(
            array('mesage' => 'Product has been stored')
        );
    }
    else {
        echo json_encode(
            array('message' => 'Post couldnt be created')
        );
    }
?>