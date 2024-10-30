<?php
require '../functions.php';

require 'task.php';

function get_api() {
    global $conn;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if(isset($_GET['id']) && !empty($_GET['id'])) {
            $get_id = $_GET['id'];
    
            $ambil_task = $conn->query("SELECT * FROM tasks WHERE id='$get_id'");
    
            if($ambil_task && $ambil_task->num_rows > 0) {
                $respon = array(
                    'status' => true,
                    'message' => 'Berhasil ambil',
                    'data' => $ambil_task->fetch_assoc()
                );
                echo response($respon, 200);
            } else {
                $respon = array(
                    'status' => false,
                    'message' => 'Gagal ambil',
                    'data' => array()
                );
                echo response($respon, 500);
            }
        } else {
            $result = $conn->query("SELECT * FROM tasks");
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        
            if ($data) {
                $respon = array(
                    'status' => true,
                    'message' => 'Berhasil ambil',
                    'data' => $data
                );
        
                echo response($respon, 200);
            } else {
                $respon = array(
                    'status' => false,
                    'message' => 'Gagal ambil',
                    'data' => array()
                );
        
                echo response($respon, 500);
            }
        }
    }
}

get_api();