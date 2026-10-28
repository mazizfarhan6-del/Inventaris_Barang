<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit;
}

// Query kategori beserta jumlah barang yang terikat
$query_kategori = mysqli_query($koneksi, "SELECT kategori.*, COUNT(barang.id_barang) AS jumlah_barang 
                                          FROM kategori 
                                          LEFT JOIN barang ON kategori.id_kategori = barang.id_kategori 
                                          GROUP BY kategori.id_kategori 
                                          ORDER BY kategori.id_kategori DESC");

$page = 'kategori';
include '../layouts/header.php';
?>

<div class="container-box">
    <div class="box-header">
        <h2 class="box-title">Daftar Kategori</h2>
        <a href="tambah.php" class="btn-primary-sm">Tambah Kategori</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Nama Kategori</th>
                    <th class="text-center" style="width: 180px;">Jumlah Barang</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if ($query_kategori && mysqli_num_rows($query_kategori) > 0) {
                    while ($k = mysqli_fetch_assoc($query_kategori)) { 
                ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($k['nama_kategori']); ?></strong></td>
                    <td class="text-center">
                        <span class="badge-stock <?php echo ($k['jumlah_barang'] <= 0) ? 'badge-stock-empty' : ''; ?>">
                            <?php echo (int)$k['jumlah_barang']; ?> Barang
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="action-group">
                            <a href="edit.php?id=<?php echo $k['id_kategori']; ?>" class="btn-edit">Edit</a>
                            <a href="hapus.php?id=<?php echo $k['id_kategori']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus kategori ini? Data barang yang terikat akan diset tanpa kategori.')">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                ?>
                <tr>
                    <td colspan="4" class="text-center" style="padding: 30px; color: var(--text-dim);">
                        Belum ada data kategori.
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
