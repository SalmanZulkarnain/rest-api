<?php

require_once 'helper.php';

require_once './vendor/autoload.php';
// include jwt 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// LOGIN
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo response(array(
        'status' => false,
        'message' => 'Method salah',
        'data' => array()
    ), 400);
    exit;
}

$get_email = $_POST['email'];
$get_password = $_POST['password'];

$query_login = "SELECT * FROM user WHERE email='$get_email'";
$eksekusi_login = connect_db()->query($query_login);

$user = $eksekusi_login->fetch_assoc();

if ($user) {
    if (password_verify($get_password, $user['password'])) {
        $expired_time = time() + (60 * 60 * 24 * 30); // 30 hari
        $payload = array(
            'id' => $user['id'],
            'email' => $user['email'],
            'exp' => $expired_time
        );
        $jwt_encode = JWT::encode($payload, JWT_KEY, 'HS256');
        echo response(array(
            'status' => true,
            'message' => 'Berhasil login',
            'data' => array(
                'access_token' => $jwt_encode,
                'expired_token' => date('Y-m-d H:i:s', $expired_time)
            )
        ), 200);
        exit;
    } else {
        echo response(array(
            'status' => false,
            'message' => 'Password salah login',
            'data' => array()
        ), 400);
        exit;
    }
} else {
    echo response(array(
        'status' => false,
        'message' => 'Belum register',
        'data' => array()
    ), 400);
    exit;
}
