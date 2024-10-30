<?php
require '../functions.php';

require 'task.php';

function post_api() {
    global $conn;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal = $_POST['tanggal'];
        $status = !empty($_POST['status']) ? $_POST['status'] : 'belum';
    
        if (!empty($judul) && !empty($deskripsi) && !empty($tanggal)) {
            $sql = "INSERT INTO tasks (judul, deskripsi, tanggal, status) VALUES ('$judul', '$deskripsi', '$tanggal', '$status')";
    
            if ($conn->query($sql)) {
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
    }
}

post_api();