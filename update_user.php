<?php
// update_user.php
session_start();
header('Content-Type: application/json');

require 'db.php'; // Mengimpor koneksi database

// Direktori untuk menyimpan gambar profil
$upload_dir = __DIR__ . '/uploads/profile_images/';

// Buat direktori jika belum ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
    exit;
}

// Cek apakah permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// [PERBAIKAN 1]: Logika untuk menangani DUA jenis input (JSON dan FormData).
// Ini adalah perbaikan inti dari masalah Anda.
$data = [];
// Cek jika header request adalah 'application/json' (dikirim saat memilih trainer).
if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true) ?: []; // Gunakan `?: []` untuk mencegah error jika JSON tidak valid
} else {
    // Jika bukan JSON, maka itu adalah 'multipart/form-data' (dari sidebar).
    // Gunakan $_POST seperti biasa.
    $data = $_POST;
}
// Sekarang, variabel `$data` akan berisi input teks, tidak peduli bagaimana cara dikirimnya.


$message = 'Informasi berhasil diperbarui.'; // Pesan default

try {
    // Ambil data pengguna yang sedang login
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Pengguna tidak ditemukan.']);
        exit;
    }

    $update_data = [];
    $update_fields = [];
    $new_profile_image_path = null;

    // --- Handle File Upload (Profile Image) ---
    // Logika ini tidak berubah karena file upload selalu menggunakan $_FILES.
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_size = $_FILES['profile_image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file_ext, $allowed_ext)) {
            echo json_encode(['success' => false, 'message' => 'Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF yang diperbolehkan.']);
            exit;
        }

        if ($file_size > $max_file_size) {
            echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB.']);
            exit;
        }

        $new_file_name = uniqid('profile_', true) . '.' . $file_ext;
        $destination = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $destination)) {
            if ($user['profile'] && file_exists(__DIR__ . '/' . $user['profile'])) {
                unlink(__DIR__ . '/' . $user['profile']);
            }
            $update_fields[] = "profile = :profile";
            $update_data['profile'] = 'uploads/profile_images/' . $new_file_name;
            $new_profile_image_path = $update_data['profile'];
            $message = 'Informasi dan gambar profil berhasil diperbarui.';
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengunggah gambar profil.']);
            exit;
        }
    }

    // --- Handle Text Data ---
    // [PERBAIKAN 2]: Menggunakan `$data` yang sudah fleksibel, bukan `$_POST` secara langsung.
    $fields_to_update_text = ['contact', 'programGoals', 'medicalHistory'];
    foreach ($fields_to_update_text as $field) {
        if (isset($data[$field])) { // Menggunakan $data, bukan $_POST
            $update_fields[] = "$field = :$field";
            $update_data[$field] = trim($data[$field]); // Menggunakan $data, bukan $_POST
        }
    }

    // [PERBAIKAN 3]: Menggunakan `$data` untuk deskripsi trainer.
    if ($user['role'] === 'Trainer' && isset($data['description'])) { // Menggunakan $data, bukan $_POST
        $update_fields[] = "description = :description";
        $update_data['description'] = trim($data['description']); // Menggunakan $data, bukan $_POST
    }

    // Eksekusi Update Profil Pengguna
    if (!empty($update_fields)) {
        $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = :id";
        $update_data['id'] = $_SESSION['user_id'];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($update_data);
    }

    // --- Handle Trainer Assignment ---
    // [PERBAIKAN 4]: Menggunakan `$data` untuk `trainer_id`. Ini adalah perbaikan paling krusial untuk masalah Anda.
    if (isset($data['trainer_id'])) {
        $trainer_id_input = $data['trainer_id']; // Menggunakan $data, bukan $_POST
        
        $trainer_id = null;
        if ($trainer_id_input !== 'null' && $trainer_id_input !== '') {
            $trainer_id = intval($trainer_id_input);
            if ($trainer_id === 0 && $trainer_id_input !== "0") {
                 echo json_encode(['success' => false, 'message' => 'Trainer ID tidak valid (bukan angka).']);
                 exit;
            }
        }
        
        if ($trainer_id !== null) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id AND role = 'Trainer'");
            $stmt->execute(['id' => $trainer_id]);
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Trainer tidak valid.']);
                exit;
            }
        }

        $stmt = $pdo->prepare("SELECT id FROM user_trainers WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $assignment = $stmt->fetch();

        if ($trainer_id !== null) {
            if ($assignment) {
                $stmt = $pdo->prepare("UPDATE user_trainers SET trainer_id = :trainer_id, assigned_at = NOW() WHERE user_id = :user_id");
                $stmt->execute(['trainer_id' => $trainer_id, 'user_id' => $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO user_trainers (user_id, trainer_id) VALUES (:user_id, :trainer_id)");
                $stmt->execute(['user_id' => $_SESSION['user_id'], 'trainer_id' => $trainer_id]);
            }
        } else {
            if ($assignment) {
                $stmt = $pdo->prepare("DELETE FROM user_trainers WHERE user_id = :user_id");
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $message .= ' Penugasan trainer berhasil dihapus.';
            }
        }
    }

    echo json_encode([
        'success' => true,
        'message' => $message,
        'new_img_profile_path' => $new_profile_image_path
    ]);

} catch (Exception $e) {
    error_log("Error in update_user.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui informasi. Silakan coba lagi.', 'error' => $e->getMessage()]);
}
?>