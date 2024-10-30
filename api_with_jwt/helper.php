<?php

require_once '../functions.php';
// INCLUDE JWT
require_once './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Kumpulan fungsi untuk API php native
 * 
 * @author Jujun Jamaludin
 * @since 2024-10-25
 */

/**
 * Fungsi untuk generate respon API
 * 
 * @param array $data
 * @param int $code
 * @return json
 */
function response($data, $code)
{
    http_response_code($code);
    header('Content-Type: application/json');
    return json_encode($data);
}

function api_get()
{
    authorized();

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $get_id = $_GET['id'];

        $get_edit_task = get_edit_task();

        if ($get_edit_task == NULL) {
            $respon = array(
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => array()
            );

            echo response($respon, 404);
            die;
        } else {
            $respon = array(
                'status' => true,
                'message' => 'Sukses',
                'data' => $get_edit_task
            );
        }
    } else {
        $respon = array(
            'status' => true,
            'message' => 'Sukses',
            'data' => get_list_task()
        );
    }

    echo response($respon, 200);
}

function api_post()
{
    authorized();
    // validasi tambah
    if ($_POST['judul'] == '' or $_POST['deskripsi'] == '' or $_POST['tanggal'] == '') {
        $respon =  array(
            'status' => false,
            'message' => 'judul, deskripsi, dan tanggal tidak boleh kosong',
            'data' => array()
        );

        echo response($respon, 400);
    } else {
        $add_task = add_task();

        if ($add_task) {
            $respon =  array(
                'status' => true,
                'message' => 'Berhasil nambah',
                'data' => array()
            );

            echo response($respon, 200);
        } else {

            $respon =  array(
                'status' => false,
                'message' => 'Gagal nambah',
                'data' => array()
            );

            echo response($respon, 400);
        }
    }
}

function api_delete()
{
    authorized();
    // validasi hapus
    $cek_task = get_edit_task();

    if ($cek_task == FALSE) {
        $respon =  array(
            'status' => false,
            'message' => 'Tugas tidak ditemukan',
            'data' => array()
        );

        echo response($respon, 404);
    } else {
        $delete_task = delete_task();

        if ($delete_task) {
            $respon =  array(
                'status' => true,
                'message' => 'Behasil menghapus',
                'data' => array()
            );

            echo response($respon, 200);
        } else {
            $respon =  array(
                'status' => false,
                'message' => 'Gagal menghapus',
                'data' => array()
            );

            echo response($respon, 500);
        }
    }
}

function api_put()
{
    authorized();
    $get_body = file_get_contents("php://input"); // jadi data JSON
    $get_json = json_decode($get_body, TRUE); // jadi array data

    $_POST['id'] = $get_json['id'];
    $_POST['judul'] = $get_json['judul'];
    $_POST['deskripsi'] = $get_json['deskripsi'];
    $_POST['status'] = $get_json['status'];
    $_POST['tanggal'] = $get_json['tanggal'];

    // validasi edit
    if ($_POST['id'] == ''  or $_POST['judul'] == '' or $_POST['deskripsi'] == '' or $_POST['status'] == '' or $_POST['tanggal'] == '') {
        $respon =  array(
            'status' => false,
            'message' => 'id, judul, deskripsi, status dan tanggal tidak boleh kosong',
            'data' => array()
        );

        echo response($respon, 500);
    } else {
        $update_task = update_task();

        if ($update_task) {
            $respon =  array(
                'status' => true,
                'message' => 'Berhasil diubah',
                'data' => array()
            );

            echo response($respon, 200);
        } else {

            $respon =  array(
                'status' => false,
                'message' => 'Gagal diubah',
                'data' => array()
            );

            echo response($respon, 500);
        }
    }
}

function authorized()
{
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        $respon = array(
            'status' => false,
            'message' => 'Unauthorized',
            'data' => array()
        );
        echo response($respon, 401);
        die;
    }

    $token = explode(' ', $headers['Authorization']);
    $access_token = $token[1];
    
    try {
        $jwt_decode = JWT::decode($access_token, new Key(JWT_KEY, 'HS256'));
        return $jwt_decode->id;
        exit;
    } catch (Exception $e) {
        $respon = array(
            'status' => false,
            'message' => 'Invalid token',
            'data' => array()
        );
        echo response($respon, 401);
        die;
    }
}

// / /Ambil x-api-key dari header
// $get_x_api_key = $_SERVER['HTTP_X_API_KEY'];
// $query_check_token = "SELECT id FROM user WHERE token='$get_x_api_key'";
// $result = connect_db()->query($query_check_token);

// if ($result->num_rows == 0) {
//     $respon = array(
//         'status' => false,
//         'message' => 'Unauthorized',
//         'data' => array()
//     );


//     echo response($respon, 401);
//     die;
// } else {
//     // Jika token valid, ambil user_id dan return
//     $user = $result->fetch_assoc();
//     return $user['id'];
// }