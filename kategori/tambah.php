<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama_kategori = trim($_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        // Cek duplikasi nama kategori
        $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE nama_kategori = '$nama_kategori'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Nama kategori sudah ada, silakan gunakan nama lain!";
        } else {
            $simpan = mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");

            if ($simpan) {
                header("location: index.php");
                exit;
            } else {
                $error = "Gagal menambah kategori: " . mysqli_error($koneksi);
            }
        }
    } else {
        $error = "Nama kategori wajib diisi!";
    }
}

$page = 'tambah_kategori';
include '../layouts/header.php';
?>

<div class="container-box" style="max-width: 500px; margin: 0 auto;">
    <div class="box-header">
        <h2 class="box-title">Tambah Kategori Baru</h2>
    </div>

    <?php if (isset($error)) { ?>
        <div class="alert-error">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" required placeholder="Contoh: Elektronik, Pakaian, Makanan" autocomplete="off">
        </div>

        <button type="submit" name="simpan" class="btn-submit" style="margin-top: 8px;">Simpan Kategori</button>
        <a href="index.php" class="btn-cancel">Batal</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
