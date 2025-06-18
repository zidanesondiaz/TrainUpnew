<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Trainer') {
    echo json_encode(['success' => false, 'message' => 'Hanya trainer yang dapat mengubah jadwal.']);
    exit;
}

$trainer_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'], $data['time_start'], $data['time_end'], $data['description'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit;
}

$id = intval($data['id']);

try {
    // Verifikasi kepemilikan jadwal
    $stmt = $pdo->prepare("SELECT trainer_id FROM trainer_availability WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $schedule = $stmt->fetch();

    if (!$schedule || $schedule['trainer_id'] != $trainer_id) {
        echo json_encode(['success' => false, 'message' => 'Anda tidak berhak mengubah jadwal ini.']);
        exit;
    }

    // Update jadwal
    $stmt = $pdo->prepare("
        UPDATE trainer_availability
        SET time_start = :time_start, time_end = :time_end, description = :description, updated_at = NOW()
        WHERE id = :id AND trainer_id = :trainer_id
    ");

    $updated = $stmt->execute([
        'time_start' => $data['time_start'],
        'time_end' => $data['time_end'],
        'description' => $data['description'],
        'id' => $id,
        'trainer_id' => $trainer_id
    ]);

    if ($updated) {
        echo json_encode(['success' => true, 'message' => 'Jadwal berhasil diubah.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengubah jadwal.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>