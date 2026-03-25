<?php
/**
 * Script untuk membuat symbolic link storage Laravel
 * Upload file ini ke public_html via cPanel File Manager
 * Lalu akses via browser: yourdomain.com/create-symlink.php
 * 
 * PENTING: Delete file ini setelah selesai untuk keamanan!
 */

// Keamanan sederhana - ganti dengan secret Anda
$secret = 'create-link-'.date('Ymd');
if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    die('Akses ditolak. Gunakan key: ' . $secret);
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Storage Link - Laravel</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #2563eb; margin-bottom: 10px; }
        .success { 
            background: #d1fae5; 
            color: #065f46; 
            padding: 15px; 
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #10b981;
        }
        .error { 
            background: #fee2e2; 
            color: #991b1b; 
            padding: 15px; 
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #ef4444;
        }
        .info {
            background: #dbeafe;
            color: #1e40af;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #3b82f6;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            margin: 5px;
        }
        .btn:hover { background: #1d4ed8; }
        .btn-danger { background: #dc2626; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-success { background: #059669; }
        .btn-success:hover { background: #047857; }
        pre {
            background: #1f2937;
            color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
        }
        .step {
            background: #f9fafb;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .step strong { color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔗 Laravel Storage Link Creator</h1>
        <p>Script ini akan membuat symbolic link untuk folder storage Laravel.</p>

        <?php
        // Dapatkan path
        $publicPath = __DIR__;
        $storagePath = dirname(__DIR__) . '/storage/app/public';
        $linkPath = $publicPath . '/storage';
        
        // Normalize untuk Windows
        $storagePath = str_replace('\\', '/', $storagePath);
        $linkPath = str_replace('\\', '/', $linkPath);

        echo '<div class="info">';
        echo '<strong>📍 Path Information:</strong><br>';
        echo 'Source (Target): <code>' . htmlspecialchars($storagePath) . '</code><br>';
        echo 'Destination (Link): <code>' . htmlspecialchars($linkPath) . '</code>';
        echo '</div>';

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            
            if ($action === 'create') {
                echo '<h2>📥 Creating Symbolic Link...</h2>';
                
                // Cek apakah sudah ada
                if (is_link($linkPath)) {
                    echo '<div class="success">';
                    echo '✅ Symbolic link sudah ada!<br>';
                    echo 'Target: ' . readlink($linkPath);
                    echo '</div>';
                } elseif (file_exists($linkPath)) {
                    echo '<div class="error">';
                    echo '❌ Folder/file "storage" sudah ada dan bukan symbolic link.<br>';
                    echo 'Silakan hapus/rename folder "storage" yang ada terlebih dahulu.';
                    echo '</div>';
                } else {
                    // Cek apakah storage source ada
                    if (!is_dir($storagePath)) {
                        echo '<div class="error">';
                        echo '❌ Source folder tidak ditemukan: ' . htmlspecialchars($storagePath) . '<br>';
                        echo 'Pastikan folder storage/app/public sudah ada.';
                        echo '</div>';
                    } else {
                        // Coba buat symlink
                        if (@symlink($storagePath, $linkPath)) {
                            echo '<div class="success">';
                            echo '✅ <strong>Berhasil!</strong> Symbolic link telah dibuat.<br><br>';
                            echo 'Link: <code>' . htmlspecialchars($linkPath) . '</code> → <code>' . htmlspecialchars(readlink($linkPath)) . '</code><br><br>';
                            echo 'Sekarang gambar di storage/app/public bisa diakses via yourdomain.com/storage/filename.jpg';
                            echo '</div>';
                            
                            // Test apakah link bekerja
                            if (is_dir($linkPath)) {
                                echo '<div class="success">';
                                echo '✅ <strong>Test Berhasil!</strong> Symbolic link berfungsi dengan baik.';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="error">';
                            echo '❌ Gagal membuat symbolic link.<br>';
                            echo 'Error: ' . error_get_last()['message'] ?? 'Unknown error';
                            echo '<br><br>';
                            echo '<strong>Alternatif:</strong> Gunakan cPanel File Manager untuk membuat symlink manual.';
                            echo '</div>';
                        }
                    }
                }
            }
            
            if ($action === 'delete') {
                echo '<h2>🗑️ Deleting This Script...</h2>';
                
                if (unlink(__FILE__)) {
                    echo '<div class="success">';
                    echo '✅ File script telah dihapus demi keamanan.<br><br>';
                    echo 'Anda akan diarahkan ke halaman utama dalam 3 detik...';
                    echo '</div>';
                    header('Refresh: 3; URL=/');
                } else {
                    echo '<div class="error">';
                    echo '❌ Gagal menghapus file. Silakan hapus manual via cPanel File Manager.';
                    echo '</div>';
                }
            }
        }

        // Cek status saat ini
        echo '<h2>📊 Current Status</h2>';
        
        if (is_link($linkPath)) {
            echo '<div class="success">';
            echo '✅ Symbolic link SUDAH ADA<br>';
            echo 'Target: <code>' . htmlspecialchars(readlink($linkPath)) . '</code>';
            echo '</div>';
        } elseif (file_exists($linkPath)) {
            echo '<div class="error">';
            echo '⚠️ Folder "storage" ada tapi BUKAN symbolic link<br>';
            echo 'Ini mungkin folder biasa. Untuk akses storage, perlu symbolic link.';
            echo '</div>';
        } else {
            echo '<div class="info">';
            echo 'ℹ️ Symbolic link BELUM dibuat<br>';
            echo 'Klik tombol di bawah untuk membuat symbolic link.';
            echo '</div>';
        }

        // Form aksi
        echo '<h2>🔧 Actions</h2>';
        
        if (!file_exists($linkPath) || is_link($linkPath)) {
            echo '<form method="POST" style="display:inline;">';
            echo '<button type="submit" name="action" value="create" class="btn btn-success">';
            echo '🔗 Create Symbolic Link';
            echo '</button>';
            echo '</form>';
        }
        
        echo '<form method="POST" style="display:inline;" onsubmit="return confirm(\'Yakin ingin menghapus file ini? File tidak bisa dikembalikan.\')">';
        echo '<button type="submit" name="action" value="delete" class="btn btn-danger">';
        echo '🗑️ Delete This Script (Recommended After Use)';
        echo '</button>';
        echo '</form>';

        // Panduan
        echo '<h2>📖 Panduan Lengkap</h2>';
        
        echo '<div class="step">';
        echo '<strong>Langkah 1:</strong> Upload file ini ke public_html via cPanel File Manager atau FTP.';
        echo '</div>';
        
        echo '<div class="step">';
        echo '<strong>Langkah 2:</strong> Akses via browser: <code>yourdomain.com/create-symlink.php?key=' . $secret . '</code>';
        echo '</div>';
        
        echo '<div class="step">';
        echo '<strong>Langkah 3:</strong> Klik tombol "Create Symbolic Link"';
        echo '</div>';
        
        echo '<div class="step">';
        echo '<strong>Langkah 4:</strong> Setelah berhasil, KLIK "Delete This Script" untuk keamanan!';
        echo '</div>';
        
        echo '<div class="step">';
        echo '<strong>Langkah 5 (Alternatif):</strong> Jika script ini gagal, buat symlink manual via cPanel:';
        echo '<ol style="margin-top: 10px;">';
        echo '<li>Buka cPanel → File Manager</li>';
        echo '<li>Klik menu "+ Symlink" di bagian atas</li>';
        echo '<li>Source: <code>' . htmlspecialchars($storagePath) . '</code></li>';
        echo '<li>Destination: <code>' . htmlspecialchars($linkPath) . '</code></li>';
        echo '<li>Klik Create</li>';
        echo '</ol>';
        echo '</div>';

        echo '<div class="info">';
        echo '<strong>⚠️ PENTING:</strong> Setelah symbolic link berhasil dibuat, HAPUS file ini untuk keamanan!';
        echo '</div>';
        ?>
    </div>
</body>
</html>
