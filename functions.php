<?php 

require 'config.php';

function connect_db() {
    $db = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

    if ($db->connect_error) {
        die("Koneksi gagal: " . $db->connect_error);
    }
    
    return $db;
}

$db = connect_db();

function insertTask()
{
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $sql_tambah = "INSERT INTO tasks (judul, deskripsi, tanggal) VALUES ('$judul', '$deskripsi', '$tanggal')";
    $eksekusi = connect_db()->query($sql_tambah);
    return $eksekusi;
}

function viewTask() {
    global $db;
    
    $result = $db->query("SELECT * FROM tasks");
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

function doneTask(){
    global $db;

    if(isset($_GET['done'])) {
        $status = 'belum';
        $id = $_GET['done'];
        
        if($_GET['status'] == 'belum') {
            $status = 'sudah';
        } else {
            $status = 'belum';
        }
        $stmt = $db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        
        if($stmt->execute()) {
            header('Location: index.php');
            exit;
        }
    }
}

function get_edit()
{
    global $db;

    $edit = $_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM tasks WHERE id=?");
    $stmt->bind_param('i', $edit);
    if($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
function get_delete()
{
    global $db;

    $delete = $_GET['delete'];
    $stmt = $db->prepare("SELECT * FROM tasks WHERE id=?");
    $stmt->bind_param('i', $delete);
    if($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

function updateTask() {
    global $db;

    $gagal = '';
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal = $_POST['tanggal'];

        $formattedDate = DateTime::createFromFormat('d/m/Y', $tanggal);

        if ($formattedDate) {
            $tanggal = $formattedDate->format('Y-m-d');
        } 
        
        if (!empty($judul)) {

            $stmt = $db->prepare("UPDATE tasks SET judul = ?, deskripsi = ?, tanggal = ? WHERE id = ?");
            $stmt->bind_param('sssi', $judul, $deskripsi, $tanggal, $id);
            if($stmt->execute()) {
                header('Location: index.php');
            } else {
                $gagal = "Gagal mengupdate data";
            }
        }
    }
    return $gagal;
}

function deleteTask()
{
    $id = $_GET['id'];
    $sql_delete_task = "DELETE FROM tasks WHERE id='$id'";
    $eksekusi = connect_db()->query($sql_delete_task);
    return $eksekusi;
}