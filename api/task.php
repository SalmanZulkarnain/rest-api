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

switch ($request_method) {
    case 'GET':
        if (isset($_GET['edit']) && !empty($_GET['edit'])) {
            $get_id = $_GET['edit'];

            $ambil_task = get_edit();

            if ($ambil_task == NULL) {
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
                    'data' => $ambil_task
                );
            }
        } else {
            $respon = array(
                'status' => true,
                'message' => 'Sukses',
                'data' => viewTask()
            );
        }

        echo response($respon, 200);
        break;
    case 'POST':
        if (empty($_POST['judul']) || empty($_POST['deskripsi']) || empty($_POST['tanggal'])) {
            $respon = array(
                'status' => false,
                'message' => 'judul, deskripsi, dan tanggal tidak boleh kosong',
                'data' => array()
            );

            echo response($respon, 500);
        } else {
            $insert_task = insertTask();

            if ($insert_task) {
                $respon = array(
                    'status' => true,
                    'message' => 'Berhasil nambah',
                    'data' => array()
                );

                echo response($respon, 200);
            } else {

                $respon = array(
                    'status' => false,
                    'message' => 'Gagal nambah',
                    'data' => array()
                );

                echo response($respon, 500);
            }
        }
        break;

    case 'DELETE':
        $cek_todo = deleteTask();

        if ($cek_todo == FALSE) {
            $respon = array(
                'status' => false,
                'message' => 'Tugas tidak ditemukan',
                'data' => array()
            );

            echo response($respon, 404);
        } else {
            $delete_task = deleteTask();

            if ($delete_task) {
                $respon = array(
                    'status' => true,
                    'message' => 'Behasil menghapus',
                    'data' => array()
                );

                echo response($respon, 200);
            } else {
                $respon = array(
                    'status' => false,
                    'message' => 'Gagal menghapus',
                    'data' => array()
                );

                echo response($respon, 500);
            }
        }

        break;
    case 'PUT':
        $get_body = file_get_contents("php://input"); // jadi data JSON
        $get_json = json_decode($get_body, TRUE); // jadi array data

        $_POST['id'] = $get_json['id'];
        $_POST['judul'] = $get_json['judul'];
        $_POST['deskripsi'] = $get_json['deskripsi'];
        $_POST['tanggal'] = $get_json['tanggal'];

        if (empty($_POST['id']) || empty($_POST['judul']) || empty($_POST['deskripsi']) || empty($_POST['tanggal'])) {
            $respon = array(
                'status' => false,
                'message' => 'id, judul, deskripsi, dan tanggal tidak boleh kosong',
                'data' => array()
            );

            echo response($respon, 500);
        } else {
            $update_task = updateTask();

            if ($update_task) {
                $respon = array(
                    'status' => true,
                    'message' => 'Berhasil diubah',
                    'data' => array()
                );

                echo response($respon, 200);
            } else {

                $respon = array(
                    'status' => false,
                    'message' => 'Gagal diubah',
                    'data' => array()
                );

                echo response($respon, 500);
            }
        }
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

