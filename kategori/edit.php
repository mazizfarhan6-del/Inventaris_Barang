<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location: index.php");
    exit;
}

$id_kategori = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori = '$id_kategori'");
$kategori = mysqli_fetch_assoc($data);

if (!$kategori) {
    header("location: index.php");
    exit;
}

if (isset($_POST['update'])) {
    $nama_kategori = trim($_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        // Cek duplikasi nama kategori untuk ID lain
        $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE nama_kategori = '$nama_kategori' AND id_kategori != '$id_kategori'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Nama kategori sudah digunakan!";
        } else {
            $update = mysqli_query($koneksi, "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id_kategori = '$id_kategori'");

            if ($update) {
                header("location: index.php");
                exit;
            } else {
                $error = "Gagal memperbarui kategori: " . mysqli_error($koneksi);
            }
        }
    } else {
        $error = "Nama kategori wajib diisi!";
    }
}

$page = 'kategori';
include '../layouts/header.php';
?>

<div class="container-box" style="max-width: 500px; margin: 0 auto;">
    <div class="box-header">
        <h2 class="box-title">Edit Kategori</h2>
    </div>

    <?php if (isset($error)) { ?>
        <div class="alert-error">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" value="<?php echo htmlspecialchars($kategori['nama_kategori']); ?>" required autocomplete="off">
        </div>

        <button type="submit" name="update" class="btn-submit" style="margin-top: 8px;">Perbarui Kategori</button>
        <a href="index.php" class="btn-cancel">Batal</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
