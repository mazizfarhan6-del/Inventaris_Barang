<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama_user = $_POST['nama_user'];
    $username  = $_POST['username'];
    $password  = $_POST['password'];

    $cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah digunakan, pilih username lain!";
    } else {
        $simpan = mysqli_query($koneksi, "INSERT INTO user (nama_user, username, password) 
                                          VALUES ('$nama_user', '$username', '$password')");

        if ($simpan) {
            header("location: ../dashboard.php");
            exit;
        } else {
            $error = "Gagal menambah user: " . mysqli_error($koneksi);
        }
    }
}

$page = 'tambah_user';
include '../layouts/header.php';
?>

<div class="container-box" style="max-width: 500px; margin: 0 auto;">
    <div class="box-header">
        <h2 class="box-title">Tambah User Baru</h2>
    </div>

    <?php if (isset($error)) { ?>
        <div class="alert-error">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_user" required placeholder="Contoh: Admin Dua" autocomplete="off">
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required autocomplete="off" placeholder="Contoh: admin2">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Masukkan password">
        </div>

        <button type="submit" name="simpan" class="btn-submit" style="margin-top: 8px;">Simpan User</button>
        <a href="../dashboard.php" class="btn-cancel">Batal</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
