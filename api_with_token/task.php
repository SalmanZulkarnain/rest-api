<?php
require_once "../functions.php";

/**
 * Kumpulan fungsi untuk API PHP Native
 * 
 * @author Salman Zulkarnain
 * @since 2024-10-25
 */

function response($data, $code)
{
    http_response_code($code);
    header('Content-Type: application/json');
    return json_encode($data);
}

function api_get() {
    $data_api_key = array(
        'rahasia',
        'apacoba'
    );

    $api_key = $_SERVER['HTTP_X_API_KEY'];

    if( !isset($api_key) && !in_array($data_api_key, $api_key)) {
        $response = array(
            'status' => true,
            'message' =>  'Unauthorized',
            'data' => array()
        );
        echo response($response, 401);
    }
}

function authorized() {
    $get_x_api_key = $_SERVER['HTTP_X_API_KEY'];
    $query_check_token = "SELECT * FROM user WHERE token='$get_x_api_key'";
    $check_token = connect_db()->query($query_check_token);

    if( $check_token->num_rows == 0) {
        $response = array(
            'status' => true,
            'message' => 'Unauthorized',
            'data' => array()
        );

        echo response($response, 401);
    }
}