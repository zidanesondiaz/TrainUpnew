<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Trainer') {
    echo json_encode(['success' => false, 'message' => 'Hanya trainer yang dapat menambah jadwal ketersediaan.']);
    exit;
}

$trainer_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['schedules']) || !is_array($data['schedules'])) {
    echo json_encode(['success' => false, 'message' => 'Format data tidak valid.']);
    exit;
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("
        INSERT INTO trainer_availability (trainer_id, day, time_start, time_end, description, created_at, updated_at)
        VALUES (:trainer_id, :day, :time_start, :time_end, :description, NOW(), NOW())
    ");

    $successCount = 0;
    foreach ($data['schedules'] as $sch) {
        if (isset($sch['day'], $sch['time_start'], $sch['time_end'])) {
            $stmt->execute([
                'trainer_id' => $trainer_id,
                'day' => $sch['day'],
                'time_start' => $sch['time_start'],
                'time_end' => $sch['time_end'],
                'description' => $sch['description'] ?? ''
            ]);
            $successCount++;
        }
    }

    $pdo->commit();
    if ($successCount > 0) {
        echo json_encode(['success' => true, 'message' => "$successCount jadwal berhasil ditambahkan."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak ada jadwal valid yang bisa ditambahkan.']);
    }

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>