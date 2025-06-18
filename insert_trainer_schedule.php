<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Trainer') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Hanya trainer yang bisa menambahkan jadwal.']);
    exit;
}

$trainer_id = $_SESSION['user_id'];

// Ambil data dari POST
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['client_id'], $data['day'], $data['time_start'], $data['time_end'], $data['start_date'], $data['end_date'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit;
}

$client_id = $data['client_id'];
$day = $data['day']; // Contoh: "Senin"
$time_start = $data['time_start']; // Contoh: "08:00"
$time_end = $data['time_end'];     // Contoh: "10:00"
$start_date = new DateTime($data['start_date']);
$end_date = new DateTime($data['end_date']);

// Validasi hari (opsional, bisa dihapus jika yakin input benar)
$valid_days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
if (!in_array($day, $valid_days)) {
    echo json_encode(['success' => false, 'message' => 'Hari tidak valid.']);
    exit;
}

// Generate tanggal-tanggal sesuai hari yang dipilih
$interval = new DateInterval('P1D');
$period = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // termasuk end_date

$inserted = 0;
foreach ($period as $date) {
    if (strftime('%A', strtotime($date->format('Y-m-d'))) == $day) {
        // Format ke versi Indonesia
        $hariIndonesia = date('N', strtotime($date->format('Y-m-d')));
        $namaHari = $valid_days[$hariIndonesia - 1];

        $stmt = $pdo->prepare("
            INSERT INTO trainer_schedules (trainer_id, client_id, day, time_start, time_end)
            VALUES (:trainer_id, :client_id, :day, :time_start, :time_end)
        ");
        $stmt->execute([
            'trainer_id' => $trainer_id,
            'client_id' => $client_id,
            'day' => $namaHari,
            'time_start' => $time_start,
            'time_end' => $time_end
        ]);
        $inserted++;
    }
}

echo json_encode(['success' => true, 'message' => "$inserted jadwal berhasil ditambahkan."]);
