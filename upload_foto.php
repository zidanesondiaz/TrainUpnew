<?php
session_start();


// Pastikan user sudah login dan role-nya adalah Trainer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Trainer') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // Ambil username dari sesi

$response = ['success' => false, 'message' => ''];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['foto']['tmp_name'];
    $file_name = $_FILES['foto']['name'];
    $file_size = $_FILES['foto']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $max_file_size = 2 * 1024 * 1024; // 2 MB

    if (!in_array($file_ext, $allowed_extensions)) {
        $response['message'] = 'Format file tidak didukung. Hanya JPG, JPEG, dan PNG yang diizinkan.';
    } elseif ($file_size > $max_file_size) {
        $response['message'] = 'Ukuran file terlalu besar. Maksimal 2MB.';
    } else {
        $new_file_name = $username . '.' . $file_ext; // Nama file berdasarkan username
        $upload_dir = 'uploads/';
        $target_path = $upload_dir . $new_file_name;

        // Pastikan direktori upload ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($file_tmp, $target_path)) {
            $response['success'] = true;
            $response['message'] = 'Foto profil berhasil diupload.';
        } else {
            $response['message'] = 'Gagal memindahkan file yang diupload.';
        }
    }
} else {
    $response['message'] = 'Upload gagal. Kode error: ' . $_FILES['foto']['error'];
}

// Gunakan sesi untuk menyimpan pesan toast, karena halaman akan di-redirect
session_start(); // Pastikan session_start() sudah dipanggil
if ($response['success']) {
    $_SESSION['toastMessageOnLoad'] = $response['message'];
    $_SESSION['toastTypeOnLoad'] = 'success';
} else {
    $_SESSION['toastMessageOnLoad'] = $response['message'];
    $_SESSION['toastTypeOnLoad'] = 'danger';
}

// Redirect kembali ke halaman profil_trainer.php
header("Location: profile_trainer.php");
exit();
?>