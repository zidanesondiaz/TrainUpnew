-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jun 2025 pada 18.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trainup`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feedback` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `feedback`, `created_at`) VALUES
(1, NULL, 'test, untuk testing', '2024-11-24 21:10:45'),
(2, 6, 'ini masukan saya sendiri', '2024-11-24 21:57:25'),
(3, 3, 'tolong naikkan gaji saya', '2024-11-24 22:26:21'),
(4, NULL, 'kembangkan lebih baik', '2024-11-25 09:48:53'),
(5, 9, 'Saran awak, trainernyo kalau dihubungi jan lambek lah, coba lebih fast respon supaya urang puas!', '2024-11-25 12:22:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trainer_availability`
--

CREATE TABLE `trainer_availability` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `trainer_availability`
--

INSERT INTO `trainer_availability` (`id`, `trainer_id`, `day`, `time_start`, `time_end`, `description`, `created_at`, `updated_at`) VALUES
(1, 8, 'Senin', '12:59:00', '16:00:00', 'senam', '2025-06-12 15:49:00', '2025-06-12 15:49:00'),
(2, 8, 'Selasa', '13:48:00', '02:48:00', 'angkat beban', '2025-06-12 15:49:00', '2025-06-12 15:49:00'),
(3, 8, 'Sabtu', '16:48:00', '17:53:00', 'edukasi gizi', '2025-06-12 15:49:00', '2025-06-12 15:49:00'),
(4, 3, 'Senin', '23:50:00', '23:50:00', 'adad', '2025-06-12 15:50:28', '2025-06-12 15:50:28'),
(6, 9, 'Senin', '00:50:00', '23:50:00', 'dsad', '2025-06-12 15:51:02', '2025-06-12 15:51:02'),
(7, 9, 'Selasa', '22:50:00', '23:51:00', 'sada', '2025-06-12 15:51:02', '2025-06-12 15:51:02'),
(9, 3, 'Senin', '23:58:00', '09:59:00', '43653654', '2025-06-12 16:18:23', '2025-06-12 16:18:23'),
(11, 3, 'Selasa', '06:57:00', '08:06:00', 'i65765', '2025-06-12 16:18:23', '2025-06-12 16:18:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trainer_schedules`
--

CREATE TABLE `trainer_schedules` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `day` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `activity` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `trainer_schedules`
--

INSERT INTO `trainer_schedules` (`id`, `trainer_id`, `client_id`, `day`, `time_start`, `time_end`, `activity`, `created_at`, `updated_at`) VALUES
(8, 3, 7, 'Senin', '20:35:00', '20:37:00', 'Stretching Saja', '2024-12-14 20:35:54', '2024-12-14 21:23:00'),
(9, 3, 7, 'Rabu', '20:36:00', '20:36:00', 'kerja berat', '2024-12-14 20:37:17', '2024-12-14 20:37:17'),
(10, 3, 7, 'Kamis', '20:36:00', '20:37:00', 'HIIT', '2024-12-14 20:37:17', '2024-12-14 20:37:17'),
(11, 3, 7, 'Selasa', '20:36:00', '20:36:00', 'apa aja', '2024-12-14 20:37:17', '2024-12-14 20:37:17'),
(12, 3, 7, 'Jumat', '20:37:00', '20:37:00', 'Angkat Beban', '2024-12-14 20:37:17', '2024-12-14 20:37:17'),
(13, 3, 7, 'Minggu', '20:37:00', '20:37:00', 'Senam', '2024-12-14 20:37:17', '2024-12-14 20:37:17'),
(14, 3, 7, 'Sabtu', '20:37:00', '20:37:00', 'Istirahat', '2024-12-14 20:37:17', '2024-12-14 20:37:17'),
(17, 3, 10, 'Selasa', '20:56:00', '21:56:00', 'Angkat Beban', '2024-12-14 20:56:24', '2024-12-14 20:56:24'),
(18, 3, 10, 'Senin', '01:55:00', '23:56:00', 'Tidur', '2024-12-14 20:56:24', '2024-12-14 20:56:24'),
(19, 8, 12, 'Senin', '12:22:00', '23:45:00', 'angkat beban', '2025-06-12 21:52:10', '2025-06-12 21:52:33'),
(20, 3, 2, 'Senin', '22:58:00', '23:59:00', 'dsada', '2025-06-12 22:58:11', '2025-06-12 22:58:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `gender` enum('Laki-laki','Perempuan') NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `programGoals` varchar(255) NOT NULL,
  `medicalHistory` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Client','Trainer','Admin') NOT NULL DEFAULT 'Client',
  `specialization` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `fullName`, `username`, `gender`, `email`, `contact`, `programGoals`, `medicalHistory`, `password`, `role`, `specialization`, `experience`, `description`, `created_at`, `updated_at`) VALUES
(2, 'ambalabu', 'client', 'Laki-laki', 'client@gmail.com', '08123456789', 'Meningkatkan kekuatan dan massa otot', 'Tidak ada', '$2y$10$3o23TFtQcy2EfE0kar1.l.CxDO23kwBJ8DoGKY7ER7vSe/ZkpQCAS', 'Client', NULL, NULL, NULL, '2024-11-24 21:11:42', '2024-11-24 21:41:10'),
(3, 'Ikhsan', 'isan', 'Laki-laki', 'isan@gmail.com', '08123456788', 'Meningkatkan kekuatan dan massa otot', 'Tidak ada', '$2y$10$2FCLb86ALqVAjS/IC62EFuEcre9u3QPVKAyFAWGBB.DKgp.9ZrsdG', 'Trainer', 'Angkat beban', 5, 'Memantapkan program anda supaya jadi sigma dengan teknik mewing khusus Ikhsan.', '2024-11-24 21:14:25', '2024-11-24 21:18:10'),
(6, 'Farel', 'farel', 'Laki-laki', 'farel@gmail.com', '2211512035', 'ada', 'Tidak ada', '$2y$10$lnkz2uQhcONh7GA2fEK8ouZc4qEscvVWC9G5AZekAX79YyT5xyJ.e', 'Admin', NULL, NULL, NULL, '2024-11-24 21:38:03', '2024-12-14 18:38:32'),
(7, 'Fikra', 'fikra', 'Laki-laki', 'fikra@gmail.com', '0555551', 'Cepat besar', 'Tidak ada', '$2y$10$fH1S2MouaN50BDm2qlAXpOCozPXMaLWDs0vMMGHxh.A5W607.P9li', 'Client', NULL, NULL, NULL, '2024-11-24 22:22:59', '2024-11-24 22:22:59'),
(8, 'Dinda', 'dinda', 'Perempuan', 'dinda@gmail.com', '08123456788', 'Kardio', 'Tidak ada', '$2y$10$D0XACL94t4tWjwxFMDAFl.JV8.w.SDv9nUL.cTjBjWnkzcXFOaU3O', 'Trainer', 'Kardio', 2, 'Mau langsing dan sehat? Yuk, coba program aku! Dijamin kelihatan progresnya dalam seminggu.', '2024-11-24 22:28:33', '2024-11-25 12:17:34'),
(9, 'Haikal', 'haikal', 'Laki-laki', 'haikal@gmail.com', '0234654645', 'Meningkatkan kesehatan', 'Tidak ada', '$2y$10$wwerM6fFocv.6kF.26YEEOs3E/jW2aMSFnePOIZWfsBDoophrtqna', 'Trainer', 'Gym Schedule Maker & Pola Makan', 2, 'Gulo manih, tapi bahayo, makan tamanuang, badan taganggu. Jauhi gulo, jagalah pola makan jo rajin baraja badan.<br>\nIkuti program ambo, hasil indak bakana mananti!', '2024-11-24 22:31:31', '2024-11-25 12:18:13'),
(10, 'Zidane', 'zidan', 'Laki-laki', 'zidane@gmail.com', '034568743', 'Meningkatkan kesehatan', 'Tidak ada', '$2y$10$DOuShdoa45E1L2jORFrSPOaXdvN/3QEhE72vOOvjeRf0ydcoX7RtC', 'Client', NULL, NULL, NULL, '2024-11-25 11:02:00', '2024-11-25 11:02:00'),
(11, 'Ichwan', 'aan', 'Laki-laki', 'aan@gmail.com', '08543456833', 'Meningkatkan kesehatan', 'Tidak ada', '$2y$10$r8We28A5uWhhBWKCLi4zc.ws3G1GaAIVGOMR/K5W.aF4lGLj/m6P.', 'Trainer', 'Trainer Gym', 7, 'apotu', '2024-11-25 11:35:35', '2024-11-25 11:40:52'),
(12, 'Aini', 'aini', 'Perempuan', 'aini@gmail.com', '0888812556', 'Meningkatkan kesehatan', 'Tidak ada', '$2y$10$Y1VOiAAt8np/6BKMurRWWuSjkMKH0T1qVq3olzoOZyBQj.fo9ACMm', 'Client', NULL, NULL, NULL, '2024-11-25 12:36:43', '2024-11-25 12:36:43'),
(13, 'bayu', 'bayu', 'Laki-laki', 'bayu@mail.com', '08124778345677', 'Fit', '', '$2y$10$mSLTShK3JEjmUSh0DvKif.jTfMjoEwlgP9eYkKjktqM4J4VxyUi7G', 'Client', NULL, NULL, NULL, '2025-06-12 23:20:58', '2025-06-12 23:20:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_trainers`
--

CREATE TABLE `user_trainers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_trainers`
--

INSERT INTO `user_trainers` (`id`, `user_id`, `trainer_id`, `assigned_at`) VALUES
(3, 7, 3, '2024-11-24 22:25:20'),
(6, 2, 3, '2024-11-25 11:01:09'),
(11, 12, 8, '2024-12-14 20:49:44'),
(12, 10, 3, '2025-06-12 22:53:03');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `trainer_availability`
--
ALTER TABLE `trainer_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trainer_id` (`trainer_id`);

--
-- Indeks untuk tabel `trainer_schedules`
--
ALTER TABLE `trainer_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`),
  ADD KEY `trainer_schedules_ibfk_2` (`client_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_trainers`
--
ALTER TABLE `user_trainers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `trainer_availability`
--
ALTER TABLE `trainer_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `trainer_schedules`
--
ALTER TABLE `trainer_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `user_trainers`
--
ALTER TABLE `user_trainers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `trainer_availability`
--
ALTER TABLE `trainer_availability`
  ADD CONSTRAINT `fk_trainer_availability_users` FOREIGN KEY (`trainer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `trainer_schedules`
--
ALTER TABLE `trainer_schedules`
  ADD CONSTRAINT `trainer_schedules_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trainer_schedules_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_trainers`
--
ALTER TABLE `user_trainers`
  ADD CONSTRAINT `user_trainers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_trainers_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
