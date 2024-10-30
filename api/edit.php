<?php
require '../functions.php';

require 'task.php';

function edit_api() {
    global $conn;
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $put_vars = json_decode(file_get_contents("php://input"), true);
    
        $id = $put_vars['id']; 
        $judul = $put_vars['judul'];
        $deskripsi = $put_vars['deskripsi'];
        $tanggal = $put_vars['tanggal'];
        $status = $put_vars['status'];
    
        if (isset($id) && !empty($judul) && !empty($deskripsi) && !empty($tanggal)) {
            $sql = "UPDATE tasks SET judul='$judul', deskripsi='$deskripsi', tanggal='$tanggal', status='$status' WHERE id='$id'";
    
            if ($conn->query($sql) === TRUE) {
                $respon = array(
                    'status' => true,
                    'message' => 'Berhasil memperbarui data',
                    'data' => array()
                );
    
                echo response($respon, 200);
            } else {
                $respon = array(
                    'status' => false,
                    'message' => 'Gagal memperbarui data: ' . $conn->error,
                    'data' => array()
                );
    
                echo response($respon, 500);
            }
        } else {
            $respon = array(
                'status' => false,
                'message' => 'Data tidak lengkap',
                'data' => array()
            );
    
            echo response($respon, 400);
        }
    }
}

edit_api();