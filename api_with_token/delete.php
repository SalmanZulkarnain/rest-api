<?php
require '../functions.php';

require 'task.php';

function delete_api() {
    autorized();

    global $conn;
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = $_GET['id'];
    
        if (isset($id)) {
            $cek_data = $conn->query("SELECT * FROM tasks WHERE id='$id'");

            if($cek_data && $cek_data->num_rows > 0) {
                $sql = "DELETE FROM tasks WHERE id='$id'";
    
                if ($conn->query($sql)) {
                    $respon = array(
                        'status' => true,
                        'message' => 'Berhasil hapus',
                        'data' => array()
                    );
                    echo response($respon, 200);
                } else {
                    $respon = array(
                        'status' => false,
                        'message' => 'Gagal hapus',
                        'data' => array()
                    );
        
                    echo response($respon, 500);
                }
            } else {
                $respon = array(
                    'status' => false,
                    'message' => 'ID tidak ditemukan',
                    'data' => array()
                );
                echo response($respon, 500);
            }
        } else {
            $respon = array(
                'status' => false,
                'message' => 'ID tidak diberikan',
                'data' => array()
            );
            echo response($respon, 500);
        }
    }
}

delete_api();
