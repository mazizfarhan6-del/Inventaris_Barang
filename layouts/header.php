<?php
if (!isset($base_url)) {
    if (isset($_SERVER['SCRIPT_NAME'])) {
        $script_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $project_root = preg_replace('/(\/(barang|kategori|auth|layouts|config))$/i', '', $script_path);
        $base_url = rtrim($project_root, '/') . '/';
    } else {
        $base_url = "http://localhost/inventaris_barang/";
    }
}
if (!isset($page)) { $page = ''; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Barang</title>
    
    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #09090b;
            --bg-card: #18181b;
            --bg-sidebar: #141417;
            --bg-input: #121215;
            --border-color: rgba(255, 255, 255, 0.08);
            --primary-red: #dc2626;
            --primary-red-hover: #b91c1c;
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
            --text-dim: #71717a;
            --sidebar-width: 240px;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* Layout */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mac-dots {
            display: flex;
            gap: 6px;
        }

        .mac-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .mac-dot-red { background-color: #ff5f56; }
        .mac-dot-yellow { background-color: #ffbd2e; }
        .mac-dot-green { background-color: #27c93f; }

        .brand-title {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }

        /* Profile Card */
        .sidebar-profile {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-label {
            font-size: 11px;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-status {
            font-size: 12px;
            color: #4ade80;
            margin-top: 2px;
        }

        /* Navigation Menu */
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .menu-item {
            display: block;
            padding: 10px 14px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .menu-item.active {
            background-color: var(--primary-red);
            color: #ffffff;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border-color);
        }

        .menu-item-logout {
            color: #f87171;
        }

        .menu-item-logout:hover {
            background-color: rgba(220, 38, 38, 0.15);
            color: #ffffff;
        }

        /* Main Content Area */
        .main-layout {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-topbar {
            height: 56px;
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
        }

        .topbar-date {
            font-size: 12px;
            color: var(--text-dim);
        }

        .main-content {
            flex: 1;
            padding: 28px;
        }

        /* Container Box */
        .container-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-color);
        }

        .box-title {
            font-size: 17px;
            font-weight: 600;
            color: #ffffff;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table th {
            background: #141417;
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-color);
        }

        table td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 13px;
            color: #e4e4e7;
            vertical-align: middle;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }

        .badge-category {
            display: inline-block;
            padding: 3px 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 11px;
            color: #d4d4d8;
        }

        .badge-stock {
            display: inline-block;
            padding: 3px 8px;
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-stock-empty {
            background: rgba(220, 38, 38, 0.1);
            color: #f87171;
        }

        .action-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-edit {
            padding: 5px 12px;
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.25);
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .btn-hapus {
            padding: 5px 12px;
            background: rgba(220, 38, 38, 0.1);
            color: #f87171;
            border: 1px solid rgba(220, 38, 38, 0.25);
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-hapus:hover {
            background: var(--primary-red);
            color: #ffffff;
            border-color: var(--primary-red);
        }

        .btn-primary-sm {
            padding: 7px 14px;
            background: var(--primary-red);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-primary-sm:hover {
            background: var(--primary-red-hover);
        }

        /* Forms & Inputs */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 13px;
            color: #d4d4d8;
        }

        .form-group input[type="text"], 
        .form-group input[type="password"], 
        .form-group input[type="number"], 
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 13px;
            color: #ffffff;
            outline: none;
            transition: border-color 0.2s, background-color 0.2s;
            font-family: 'Poppins', sans-serif;
        }

        .form-group input[type="text"]:focus, 
        .form-group input[type="password"]:focus, 
        .form-group input[type="number"]:focus, 
        .form-group select:focus {
            border-color: var(--primary-red);
            background-color: #16161b;
        }

        .form-group select option {
            background-color: var(--bg-card);
            color: #ffffff;
        }

        /* Autofill Dark Mode Fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #ffffff !important;
            -webkit-box-shadow: 0 0 0px 1000px #27272a inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .btn-submit {
            width: 100%;
            padding: 10px;
            background: var(--primary-red);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background: var(--primary-red-hover);
        }

        .btn-cancel {
            display: block;
            text-align: center;
            color: var(--text-dim);
            text-decoration: none;
            margin-top: 14px;
            font-size: 13px;
            transition: color 0.2s;
        }

        .btn-cancel:hover {
            color: #ffffff;
        }

        .alert-error {
            padding: 10px 14px;
            background: rgba(220, 38, 38, 0.12);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 6px;
            color: #fca5a5;
            font-size: 12.5px;
            margin-bottom: 18px;
        }

        /* Footer */
        .footer {
            padding: 16px 28px;
            background: var(--bg-sidebar);
            border-top: 1px solid var(--border-color);
            text-align: center;
            font-size: 12px;
            color: var(--text-dim);
            margin-top: auto;
        }
    </style>
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar Bersih & Minimalis -->
    <aside class="sidebar">
        <!-- Header dengan macOS dots & Judul -->
        <div class="sidebar-header">
            <div class="mac-dots">
                <span class="mac-dot mac-dot-red"></span>
                <span class="mac-dot mac-dot-yellow"></span>
                <span class="mac-dot mac-dot-green"></span>
            </div>
            <span class="brand-title">Inventaris</span>
        </div>

        <!-- Profil Pengguna -->
        <div class="sidebar-profile">
            <div class="profile-label">Pengguna Aktif</div>
            <div class="profile-name" title="<?php echo htmlspecialchars($_SESSION['nama_user'] ?? 'User'); ?>">
                <?php echo htmlspecialchars($_SESSION['nama_user'] ?? 'User'); ?>
            </div>
            <div class="profile-status">Online</div>
        </div>

        <!-- Navigasi Menu Utama -->
        <nav class="sidebar-nav">
            <a href="<?php echo $base_url; ?>dashboard.php" class="menu-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                Dashboard
            </a>
            <a href="<?php echo $base_url; ?>barang/index.php" class="menu-item <?php echo ($page == 'barang' || $page == 'tambah_barang') ? 'active' : ''; ?>">
                Barang
            </a>
            <a href="<?php echo $base_url; ?>kategori/index.php" class="menu-item <?php echo ($page == 'kategori' || $page == 'tambah_kategori') ? 'active' : ''; ?>">
                Kategori
            </a>
            <a href="<?php echo $base_url; ?>auth/tambah_user.php" class="menu-item <?php echo ($page == 'tambah_user') ? 'active' : ''; ?>">
                Tambah User
            </a>
        </nav>

        <!-- Menu Logout Utama (Pojok Kiri Bawah) -->
        <div class="sidebar-footer">
            <a href="<?php echo $base_url; ?>auth/logout.php" class="menu-item menu-item-logout">
                Logout
            </a>
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <div class="main-layout">
        <!-- Topbar Sederhana (Hanya Tanggal di Kanan Atas) -->
        <header class="main-topbar">
            <div class="topbar-title">
                <?php 
                if ($page == 'dashboard') {
                    echo 'Dashboard';
                } elseif ($page == 'barang') {
                    echo 'Barang';
                } elseif ($page == 'tambah_barang') {
                    echo 'Tambah Barang';
                } elseif ($page == 'kategori') {
                    echo 'Kategori';
                } elseif ($page == 'tambah_kategori') {
                    echo 'Tambah Kategori';
                } elseif ($page == 'tambah_user') {
                    echo 'Tambah User';
                } else {
                    echo 'Inventaris Barang';
                }
                ?>
            </div>
            <div class="topbar-actions">
                <span class="topbar-date"><?php echo date('d M Y'); ?></span>
            </div>
        </header>

        <!-- Konten Halaman -->
        <main class="main-content">
