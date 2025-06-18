<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Trainer') {
    echo json_encode(['success' => false, 'message' => 'Hanya trainer yang dapat menghapus jadwal.']);
    exit;
}

$trainer_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID jadwal tidak ditemukan.']);
    exit;
}

$id = intval($data['id']);

try {
    // Verifikasi kepemilikan sebelum menghapus
    $stmt = $pdo->prepare("SELECT trainer_id FROM trainer_availability WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $schedule = $stmt->fetch();

    if (!$schedule || $schedule['trainer_id'] != $trainer_id) {
        echo json_encode(['success' => false, 'message' => 'Anda tidak berhak menghapus jadwal ini.']);
        exit;
    }
    
    // Hapus jadwal
    $stmt = $pdo->prepare("DELETE FROM trainer_availability WHERE id = :id AND trainer_id = :trainer_id");
    $deleted = $stmt->execute(['id' => $id, 'trainer_id' => $trainer_id]);

    if ($deleted) {
        echo json_encode(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus jadwal.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>