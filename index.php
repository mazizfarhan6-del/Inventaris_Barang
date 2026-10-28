<?php
include 'config/koneksi.php';

$search   = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$q_total_barang   = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang");
$total_barang     = mysqli_fetch_assoc($q_total_barang)['total'] ?? 0;

$q_total_kategori = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori");
$total_kategori   = mysqli_fetch_assoc($q_total_kategori)['total'] ?? 0;

$q_total_stok     = mysqli_query($koneksi, "SELECT SUM(stok) as total FROM barang");
$total_stok       = mysqli_fetch_assoc($q_total_stok)['total'] ?? 0;

// 4. Ambil daftar kategori untuk tombol filter
$query_kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// 5. Query data barang berdasarkan filter
if ($search != '' && $kategori != '') {
    $query = "SELECT barang.*, kategori.nama_kategori 
              FROM barang 
              LEFT JOIN kategori ON barang.id_kategori = kategori.id_kategori 
              WHERE (barang.nama_barang LIKE '%$search%' OR kategori.nama_kategori LIKE '%$search%')
                AND kategori.nama_kategori = '$kategori'
              ORDER BY barang.id_barang DESC";
} elseif ($search != '') {
    $query = "SELECT barang.*, kategori.nama_kategori 
              FROM barang 
              LEFT JOIN kategori ON barang.id_kategori = kategori.id_kategori 
              WHERE barang.nama_barang LIKE '%$search%' 
                 OR kategori.nama_kategori LIKE '%$search%' 
              ORDER BY barang.id_barang DESC";
} elseif ($kategori != '') {
    $query = "SELECT barang.*, kategori.nama_kategori 
              FROM barang 
              LEFT JOIN kategori ON barang.id_kategori = kategori.id_kategori 
              WHERE kategori.nama_kategori = '$kategori' 
              ORDER BY barang.id_barang DESC";
} else {
    $query = "SELECT barang.*, kategori.nama_kategori 
              FROM barang 
              LEFT JOIN kategori ON barang.id_kategori = kategori.id_kategori 
              ORDER BY barang.id_barang DESC";
}

$query_barang = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Inventaris</title>

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #09090b;
            color: #f4f4f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Navbar */
        .navbar {
            background-color: #18181b;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 16px 0;
        }

        .navbar .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
        }

        .btn-login-nav {
            display: inline-block;
            padding: 6px 14px;
            background-color: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #f4f4f5;
            text-decoration: none;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-login-nav:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            color: #ffffff;
        }

        /* Konten Utama */
        .main-content {
            flex: 1;
            padding: 24px 0 40px 0;
        }

        /* Widget Ringkasan Statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background-color: #18181b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 18px 16px;
            text-align: center;
        }

        .stat-value {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
        }

        .stat-label {
            display: block;
            font-size: 11.5px;
            color: #a1a1aa;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* Area Pencarian */
        .search-section {
            margin-bottom: 24px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input[type="text"] {
            flex: 1;
            padding: 10px 14px;
            background-color: #18181b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            color: #ffffff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-form input[type="text"]:focus {
            border-color: #dc2626;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #dc2626;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-form button:hover {
            background-color: #b91c1c;
        }

        .btn-reset {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            background-color: #27272a;
            color: #f4f4f5;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .btn-reset:hover {
            background-color: #3f3f46;
        }

        /* Filter Kategori (Pill / Chip) */
        .category-filter {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .chip {
            display: inline-block;
            padding: 6px 14px;
            background-color: #18181b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            font-size: 12.5px;
            color: #a1a1aa;
            text-decoration: none;
            transition: all 0.2s;
        }

        .chip:hover {
            background-color: #27272a;
            color: #ffffff;
        }

        .chip.active {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
            font-weight: 600;
        }

        /* Grid Kartu Barang */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 24px;
            margin-bottom: 40px;
        }

        .card {
            background-color: #18181b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .card .category {
            font-size: 11px;
            color: #a1a1aa;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .card h2 {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .card-bottom {
            margin-top: auto;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card .price {
            font-size: 15px;
            font-weight: 600;
            color: #dc2626;
        }

        .card .stock {
            font-size: 12px;
            color: #a1a1aa;
        }

        /* Empty State */
        .empty-state {
            background-color: #18181b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            margin-top: 24px;
            margin-bottom: 40px;
        }

        .empty-state p {
            color: #a1a1aa;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .empty-state a {
            color: #dc2626;
            text-decoration: none;
            font-size: 14px;
        }

        .empty-state a:hover {
            text-decoration: underline;
        }

        .footer {
            background-color: #18181b;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 20px 0;
            text-align: center;
            margin-top: auto;
        }

        .footer p {
            font-size: 12px;
            color: #71717a;
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="container nav-content">
            <h1>Katalog Inventaris</h1>
        </div>
    </header>

    <main class="container main-content">
        <section class="stats-grid">
            <div class="stat-card">
                <span class="stat-value"><?php echo number_format($total_barang); ?></span>
                <span class="stat-label">Total Barang</span>
            </div>
            <div class="stat-card">
                <span class="stat-value"><?php echo number_format($total_kategori); ?></span>
                <span class="stat-label">Total Kategori</span>
            </div>
            <div class="stat-card">
                <span class="stat-value"><?php echo number_format($total_stok); ?></span>
                <span class="stat-label">Total Stok</span>
            </div>
        </section>

        <section class="search-section">
            <form method="GET" action="" class="search-form">
                <?php if ($kategori != '') { ?>
                    <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>">
                <?php } ?>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama barang atau kategori..." 
                    value="<?php echo htmlspecialchars($search); ?>"
                >
                <button type="submit">Cari</button>
                <?php if ($search != '' || $kategori != '') { ?>
                    <a href="index.php" class="btn-reset">Reset</a>
                <?php } ?>
            </form>

            <!-- Tombol Filter Kategori Cepat (Pill / Chip) -->
            <div class="category-filter">
                <a href="index.php<?php echo ($search != '') ? '?search=' . urlencode($search) : ''; ?>" 
                   class="chip <?php echo ($kategori == '') ? 'active' : ''; ?>">
                    Semua
                </a>
                <?php while ($k = mysqli_fetch_assoc($query_kategori)) { 
                    $kat_nama = $k['nama_kategori'];
                    $url = "index.php?kategori=" . urlencode($kat_nama);
                    if ($search != '') {
                        $url .= "&search=" . urlencode($search);
                    }
                ?>
                    <a href="<?php echo $url; ?>" class="chip <?php echo ($kategori == $kat_nama) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($kat_nama); ?>
                    </a>
                <?php } ?>
            </div>
        </section>

        <!-- 3. Daftar Kartu Barang -->
        <?php if (mysqli_num_rows($query_barang) > 0) { ?>
            <div class="card-grid">
                <?php while ($b = mysqli_fetch_assoc($query_barang)) { ?>
                    <div class="card">
                        <span class="category"><?php echo htmlspecialchars($b['nama_kategori'] ? $b['nama_kategori'] : 'Umum'); ?></span>
                        <h2><?php echo htmlspecialchars($b['nama_barang']); ?></h2>
                        <div class="card-bottom">
                            <span class="price">Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?></span>
                            <span class="stock">Stok: <?php echo (int)$b['stok']; ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="empty-state">
                <p>Data barang tidak ditemukan.</p>
                <?php if ($search != '' || $kategori != '') { ?>
                    <a href="index.php">Tampilkan Semua Barang</a>
                <?php } ?>
            </div>
        <?php } ?>
    </main>

    <!-- Footer Sederhana -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistem Katalog Inventaris Barang. Publik.</p>
        </div>
    </footer>

</body>
</html>
