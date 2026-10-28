<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit;
}

$query_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");

if (isset($_POST['simpan'])) {
    $nama_barang = $_POST['nama_barang'];
    $id_kategori = $_POST['id_kategori'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];

    $simpan = mysqli_query($koneksi, "INSERT INTO barang (id_kategori, nama_barang, harga, stok) 
                                      VALUES ('$id_kategori', '$nama_barang', '$harga', '$stok')");

    if ($simpan) {
        header("location: ../dashboard.php");
        exit;
    } else {
        $error = "Gagal menambah barang: " . mysqli_error($koneksi);
    }
}

$page = 'tambah_barang';
include '../layouts/header.php';
?>

<div class="container-box" style="max-width: 500px; margin: 0 auto;">
    <div class="box-header">
        <h2 class="box-title">Tambah Barang Baru</h2>
    </div>

    <?php if (isset($error)) { ?>
        <div class="alert-error">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" required placeholder="Contoh: Laptop Asus" autocomplete="off">
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($k = mysqli_fetch_assoc($query_kategori)) { ?>
                    <option value="<?php echo $k['id_kategori']; ?>"><?php echo htmlspecialchars($k['nama_kategori']); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" required placeholder="Contoh: 5000000" min="0">
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" required placeholder="Contoh: 10" min="0">
        </div>

        <button type="submit" name="simpan" class="btn-submit" style="margin-top: 8px;">Simpan Barang</button>
        <a href="../dashboard.php" class="btn-cancel">Batal</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
