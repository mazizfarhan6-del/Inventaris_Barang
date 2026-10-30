<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: auth/login.php");
    exit;
}

// Mengambil jumlah data barang
$query_barang = mysqli_query($koneksi, "SELECT * FROM barang");
$total_barang = mysqli_num_rows($query_barang);

// Mengambil jumlah data kategori
$query_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
$total_kategori = mysqli_num_rows($query_kategori);

$page = 'dashboard';
include 'layouts/header.php';
?>

<div style="margin-bottom: 28px;">
    <h1 style="font-size: 24px; font-weight: 600; color: #ffffff; margin-bottom: 6px;">Selamat Datang di Sistem Inventaris!</h1>
    <p style="color: var(--text-muted); font-size: 13.5px;">Ringkasan dan statistik inventaris barang serta kategori Anda.</p>
</div>

<!-- 2 Buah Card Informasi Sejajar Menyamping Menggunakan Flexbox / Grid -->
<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Card Total Barang -->
    <div class="container-box" style="flex: 1; min-width: 240px; margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
            Total Barang
        </div>
        <div style="font-size: 36px; font-weight: 700; color: #ffffff; line-height: 1.2;">
            <?php echo $total_barang; ?>
        </div>
        <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12px; color: var(--text-dim);">Data tersimpan</span>
            <a href="barang/index.php" style="color: #f87171; font-size: 12px; text-decoration: none; font-weight: 500;">Kelola Barang &rarr;</a>
        </div>
    </div>

    <!-- Card Total Kategori -->
    <div class="container-box" style="flex: 1; min-width: 240px; margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
            Total Kategori
        </div>
        <div style="font-size: 36px; font-weight: 700; color: #ffffff; line-height: 1.2;">
            <?php echo $total_kategori; ?>
        </div>
        <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12px; color: var(--text-dim);">Kategori aktif</span>
            <a href="kategori/index.php" style="color: #f87171; font-size: 12px; text-decoration: none; font-weight: 500;">Kelola Kategori &rarr;</a>
        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>
