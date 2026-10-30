
<?php
session_start();
include '../config/koneksi.php';

// Memastikan pengguna sudah login
if (!isset($_SESSION['status']) ||
    $_SESSION['status'] != "login") {
    header("Location: ../auth/login.php");
    exit;
}

// Validasi ID kategori
$id_kategori = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id_kategori || $id_kategori < 1) {
    header("Location: index.php");
    exit;
}

// Menyiapkan query penghapusan
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM kategori WHERE id_kategori = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id_kategori);

// Menjalankan query
if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php");
    exit;
} else {
    error_log(mysqli_stmt_error($stmt));
    echo "Gagal menghapus kategori.";
}

mysqli_stmt_close($stmt);
?>