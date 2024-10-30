<?php

require 'config.php';

function connect_db()
{
    $conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    return $conn;
}

/**
 * Fungsi untuk mengambil data list task
 */
function get_list_task()
{
    $sql_get_all = "SELECT * FROM tasks";
    $eksekusi = connect_db()->query($sql_get_all);
    $result = array();

    while ($row = $eksekusi->fetch_assoc())
    {
        $result[] = $row;
    }

    return $result;
}

/**
 * Fungsi untuk mendebug data
 */
function debug($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/**
 * Fungsi untuk tambah task
 */
function add_task()
{
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $status = !empty($_POST['status']) ? $_POST['status'] : 'belum';
    $user_id = authorized();    

    $sql_tambah = "INSERT INTO tasks (judul, deskripsi, tanggal, status, user_id) VALUES ('$judul', '$deskripsi', '$tanggal', '$status', '$user_id')";
    $eksekusi = connect_db()->query($sql_tambah);
    return $eksekusi;
}

/**
 * Fungsi untuk pindah url
 */
function redirect($file)
{
    header('Location: '.$file.'');
    }

/**
 * Fungsi untuk mengambil data yang mau diedit
 */
function get_edit_task()
{
    $id = $_GET['id'];
    $sql_ambil_edit = "SELECT * FROM tasks WHERE id='$id'";
    $eksekusi = connect_db()->query($sql_ambil_edit);
    return $eksekusi->fetch_assoc();
}

/**
 * Fungsi untuk mengubah data task
 */
function update_task()
{
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];
    $tanggal = $_POST['tanggal'];

    $sql_update = "UPDATE tasks SET judul='$judul', deskripsi='$deskripsi', status='$status', tanggal='$tanggal' WHERE id='$id'";
    $eksekusi = connect_db()->query($sql_update);
    return $eksekusi;
}

/**
 * Fungsi untuk hapus data task
 */
function delete_task()
{
    $id = $_GET['id'];
    $sql_delete = "DELETE FROM tasks WHERE id='$id'";
    $eksekusi = connect_db()->query($sql_delete);
    return $eksekusi;
}


// function doneTask()
// {
//     global $conn;

//     if (isset($_GET['done'])) {
//         $status = 'belum';
//         $id = $_GET['done'];

//         if ($_GET['status'] == 'belum') {
//             $status = 'sudah';
//         } else {
//             $status = 'belum';
//         }
//         $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
//         $stmt->bind_param('si', $status, $id);

//         if ($stmt->execute()) {
//             header('Location: index.php');
//             exit;
//         }
//     }
// }
