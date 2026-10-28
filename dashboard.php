<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: auth/login.php");
    exit;
}

$query_barang = mysqli_query($koneksi, "SELECT barang.*, kategori.nama_kategori 
                                        FROM barang 
                                        LEFT JOIN kategori ON barang.id_kategori = kategori.id_kategori 
                                        ORDER BY barang.id_barang DESC");

$page = 'dashboard';
include 'layouts/header.php';
?>

<div class="container-box">
    <div class="box-header">
        <h2 class="box-title">Daftar Barang</h2>
        <a href="barang/tambah.php" class="btn-primary-sm">Tambah Barang</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-right">Harga</th>
                    <th class="text-center" style="width: 90px;">Stok</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if ($query_barang && mysqli_num_rows($query_barang) > 0) {
                    while ($b = mysqli_fetch_assoc($query_barang)) { 
                ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($b['nama_barang']); ?></strong></td>
                    <td>
                        <span class="badge-category">
                            <?php echo $b['nama_kategori'] ? htmlspecialchars($b['nama_kategori']) : '-'; ?>
                        </span>
                    </td>
                    <td class="text-right">Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?></td>
                    <td class="text-center">
                        <span class="badge-stock <?php echo ($b['stok'] <= 0) ? 'badge-stock-empty' : ''; ?>">
                            <?php echo (int)$b['stok']; ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="action-group">
                            <a href="barang/edit.php?id=<?php echo $b['id_barang']; ?>" class="btn-edit">Edit</a>
                            <a href="barang/hapus.php?id=<?php echo $b['id_barang']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; color: var(--text-dim);">
                        Belum ada data barang.
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>
