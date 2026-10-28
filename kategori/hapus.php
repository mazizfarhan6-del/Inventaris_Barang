<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_kategori = $_GET['id'];

    // Set id_kategori barang terkait menjadi NULL agar data barang tetap aman
    mysqli_query($koneksi, "UPDATE barang SET id_kategori = NULL WHERE id_kategori = '$id_kategori'");

    $hapus = mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori = '$id_kategori'");

    if ($hapus) {
        header("location: index.php");
        exit;
    } else {
        echo "Gagal menghapus kategori: " . mysqli_error($koneksi);
    }
} else {
    header("location: index.php");
    exit;
}
?>
