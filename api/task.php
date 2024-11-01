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

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) 
{
    case 'GET':
        api_get();
        break;
    
    case 'POST':
        api_post();
        break;
    
    case 'DELETE':
        api_delete();
        break;
    
    case 'PUT':
        api_put();
        break;
    
    default:
        
        $respon = array(
            'status' => false,
            'message' => 'Not Found',
            'data' => array()
        );
        
        echo response($respon, 404);

        break;
}

