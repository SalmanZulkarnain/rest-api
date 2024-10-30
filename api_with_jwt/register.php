<?php

require_once 'helper.php'; 

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo response(array(
        'status' => false,
        'message' => 'Tidak berhasil',
        'data' => array()
    ), 400);
    exit; 
}

$get_name = $_POST['name'];
$get_email = $_POST['email'];
$get_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$get_token = $_POST['token'];

$query_register = "INSERT INTO user (name, email, password, token) VALUES ('$get_name', '$get_email', '$get_password', '$get_token')";
$eksekusi_register = connect_db()->query($query_register);

if($eksekusi_register) {
    echo response(array(
        'status' => true,
        'message' => 'Berhasil register',
        'data' => array()
    ), 200);
    exit; 
} else {
    echo response(array(
        'status' => false,
        'message' => 'Tidak berhasil register',
        'data' => array()
    ), 400);
    exit; 
}
