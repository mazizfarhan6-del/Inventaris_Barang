<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location: ../dashboard.php");
    exit;
}

$id_barang = $_GET['id'];

$data_barang = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id_barang'");
$barang = mysqli_fetch_assoc($data_barang);

if (!$barang) {
    header("location: ../dashboard.php");
    exit;
}

$query_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");

if (isset($_POST['update'])) {
    $nama_barang = $_POST['nama_barang'];
    $id_kategori = $_POST['id_kategori'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];

    $update = mysqli_query($koneksi, "UPDATE barang SET 
                                      id_kategori='$id_kategori', 
                                      nama_barang='$nama_barang', 
                                      harga='$harga', 
                                      stok='$stok' 
                                      WHERE id_barang='$id_barang'");

    if ($update) {
        header("location: index.php");
        exit;
    } else {
        $error = "Gagal mengupdate barang: " . mysqli_error($koneksi);
    }
}

$page = 'barang';
include '../layouts/header.php';
?>

<div class="container-box" style="max-width: 500px; margin: 0 auto;">
    <div class="box-header">
        <h2 class="box-title">Edit Data Barang</h2>
    </div>

    <?php if (isset($error)) { ?>
        <div class="alert-error">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required autocomplete="off">
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="id_kategori" required>
                <?php while ($k = mysqli_fetch_assoc($query_kategori)) { ?>
                    <option value="<?php echo $k['id_kategori']; ?>" <?php if ($k['id_kategori'] == $barang['id_kategori']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($k['nama_kategori']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?php echo htmlspecialchars($barang['harga']); ?>" required min="0">
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="<?php echo htmlspecialchars($barang['stok']); ?>" required min="0">
        </div>

        <button type="submit" name="update" class="btn-submit" style="margin-top: 8px;">Perbarui Barang</button>
        <a href="index.php" class="btn-cancel">Batal</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
