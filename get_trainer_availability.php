<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu.']);
    exit;
}

$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

try {
    if ($user_role === 'Trainer') {
        // Trainer hanya melihat jadwalnya sendiri
        $stmt = $pdo->prepare("
            SELECT * FROM trainer_availability 
            WHERE trainer_id = :trainer_id
            ORDER BY FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), time_start
        ");
        $stmt->execute(['trainer_id' => $user_id]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'schedules' => $schedules]);

    } elseif ($user_role === 'Client') {
        // Client melihat jadwal semua trainer, dikelompokkan
        $stmt = $pdo->query("
            SELECT ta.*, u.fullName AS trainer_name 
            FROM trainer_availability ta
            JOIN users u ON ta.trainer_id = u.id
            WHERE u.role = 'Trainer'
            ORDER BY u.fullName, FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), time_start
        ");
        $all_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengelompokkan jadwal berdasarkan trainer
        $grouped_schedules = [];
        foreach ($all_schedules as $schedule) {
            $grouped_schedules[$schedule['trainer_name']][] = $schedule;
        }

        echo json_encode(['success' => true, 'schedules' => $grouped_schedules]);
    } else {
        // Admin atau role lain bisa melihat semua (mirip client)
        $stmt = $pdo->query("
            SELECT ta.*, u.fullName AS trainer_name 
            FROM trainer_availability ta
            JOIN users u ON ta.trainer_id = u.id
            WHERE u.role = 'Trainer'
            ORDER BY u.fullName, FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), time_start
        ");
        $all_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grouped_schedules = [];
        foreach ($all_schedules as $schedule) {
            $grouped_schedules[$schedule['trainer_name']][] = $schedule;
        }
        echo json_encode(['success' => true, 'schedules' => $grouped_schedules]);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil jadwal: ' . $e->getMessage()]);
}
?>