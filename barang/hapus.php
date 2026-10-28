<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];
    
    $hapus = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id_barang'");

    if ($hapus) {
        header("location: ../dashboard.php");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}
?>