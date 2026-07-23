-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 23 Jul 2026 pada 09.02
-- Versi server: 8.0.30
-- Versi PHP: 8.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel12`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f1b95-3eb4-7232-a057-913b7bd5a25d', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"fgd usaha masyarakat\", \"jenis_kegiatan\": \"usaha masyarakat\", \"keterangan_status\": null}}', NULL, '2026-07-01 02:49:53', '2026-07-01 02:49:53'),
(2, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f1bd0-3afa-70ff-8e90-2254eeb5dd89', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"patroli dugong\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-01 03:54:19', '2026-07-01 03:54:19'),
(3, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019c3adf-9a6e-72eb-8d0d-2582bbf628d9', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"revisi\", \"keterangan_status\": \"tolong perbaiki lagi\"}, \"attributes\": {\"status\": \"diajukan\", \"keterangan_status\": null}}', NULL, '2026-07-01 04:21:55', '2026-07-01 04:21:55'),
(4, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f1bd0-3afa-70ff-8e90-2254eeb5dd89', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"tolong perbaiki tujuannya\"}}', NULL, '2026-07-01 04:54:37', '2026-07-01 04:54:37'),
(5, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f1bd0-3afa-70ff-8e90-2254eeb5dd89', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"revisi\", \"keterangan_status\": \"tolong perbaiki tujuannya\"}, \"attributes\": {\"status\": \"diajukan\", \"keterangan_status\": null}}', NULL, '2026-07-01 13:54:02', '2026-07-01 13:54:02'),
(6, 'default', 'Rencana Kegiatan deleted', 'App\\Models\\RencanaKegiatan', 'deleted', '019f1b91-2695-73e5-8b25-28b023e338c9', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"fgd usaha masyarakat\", \"jenis_kegiatan\": \"usaha masyarakat\", \"keterangan_status\": null}}', NULL, '2026-07-01 14:27:42', '2026-07-01 14:27:42'),
(7, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019c3adf-9a6e-72eb-8d0d-2582bbf628d9', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"perbaiki bagian deskripsi\"}}', NULL, '2026-07-01 15:43:18', '2026-07-01 15:43:18'),
(8, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f1e9b-ca7a-732f-937f-a24af5dd7820', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"penanaman mangrove\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-01 16:55:55', '2026-07-01 16:55:55'),
(9, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2053-6365-7127-8daa-da9ab4bc059e', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"penanaman mangrove\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-02 00:56:03', '2026-07-02 00:56:03'),
(10, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2058-75bc-73a4-a5e4-d0eb49b83cbc', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"penanaman mangrove\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-02 01:01:36', '2026-07-02 01:01:36'),
(11, 'default', 'Rencana Kegiatan deleted', 'App\\Models\\RencanaKegiatan', 'deleted', '019f2058-75bc-73a4-a5e4-d0eb49b83cbc', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"penanaman mangrove\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-02 01:18:51', '2026-07-02 01:18:51'),
(12, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2087-d91f-716a-8950-1e95fd6a10b6', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"patroli dugong\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-02 01:53:21', '2026-07-02 01:53:21'),
(13, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '34', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\"}}', NULL, '2026-07-02 02:37:23', '2026-07-02 02:37:23'),
(14, 'default', 'Laporan Kegiatan deleted', 'App\\Models\\LaporanKegiatan', 'deleted', '34', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}}', NULL, '2026-07-02 02:39:11', '2026-07-02 02:39:11'),
(15, 'default', 'Rencana Kegiatan deleted', 'App\\Models\\RencanaKegiatan', 'deleted', '019f1e9b-ca7a-732f-937f-a24af5dd7820', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"penanaman mangrove\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-02 15:18:21', '2026-07-02 15:18:21'),
(16, 'default', 'Rencana Kegiatan deleted', 'App\\Models\\RencanaKegiatan', 'deleted', '019cf4ad-3afe-71ea-b4c3-5bcc350a5900', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"Diskusi Mengenai Tree Planting\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-02 15:26:22', '2026-07-02 15:26:22'),
(17, 'default', 'Rencana Kegiatan deleted', 'App\\Models\\RencanaKegiatan', 'deleted', '019f1b91-fd85-70f6-b7de-878618339efd', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"fgd usaha masyarakat\", \"jenis_kegiatan\": \"usaha masyarakat\", \"keterangan_status\": null}}', NULL, '2026-07-02 15:36:33', '2026-07-02 15:36:33'),
(18, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '35', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-02 16:05:33', '2026-07-02 16:05:33'),
(19, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f25e9-a79c-7175-902f-b92e66c9bad7', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"kalbar walking tour\", \"jenis_kegiatan\": \"lainnya\", \"keterangan_status\": null}}', NULL, '2026-07-03 02:58:18', '2026-07-03 02:58:18'),
(20, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f25f3-0159-718b-b44f-4b0004c7cd6d', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"penanaman mangrove\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-03 03:08:30', '2026-07-03 03:08:30'),
(21, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f25f3-0159-718b-b44f-4b0004c7cd6d', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-03 03:16:56', '2026-07-03 03:16:56'),
(22, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019c3adf-9a6e-72eb-8d0d-2582bbf628d9', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"revisi\", \"keterangan_status\": \"perbaiki bagian deskripsi\"}, \"attributes\": {\"status\": \"draft\", \"keterangan_status\": null}}', NULL, '2026-07-03 03:19:28', '2026-07-03 03:19:28'),
(23, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f25e9-a79c-7175-902f-b92e66c9bad7', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"tolong perbaiki rincian kebutuannya\"}}', NULL, '2026-07-03 03:30:14', '2026-07-03 03:30:14'),
(24, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f25f3-0159-718b-b44f-4b0004c7cd6d', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"deskripsi kegiatan belum detail\"}}', NULL, '2026-07-03 03:31:12', '2026-07-03 03:31:12'),
(25, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f25f3-0159-718b-b44f-4b0004c7cd6d', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"revisi\", \"keterangan_status\": \"deskripsi kegiatan belum detail\"}, \"attributes\": {\"status\": \"diajukan\", \"keterangan_status\": null}}', NULL, '2026-07-03 03:33:28', '2026-07-03 03:33:28'),
(26, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f286c-e202-73d5-a36b-9dfabc783460', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"penanaman mangrove\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-03 14:40:53', '2026-07-03 14:40:53'),
(27, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f286c-e202-73d5-a36b-9dfabc783460', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-03 15:12:31', '2026-07-03 15:12:31'),
(28, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f286c-e202-73d5-a36b-9dfabc783460', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"disetujui\"}}', NULL, '2026-07-03 15:39:07', '2026-07-03 15:39:07'),
(29, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '36', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\"}}', NULL, '2026-07-03 15:52:26', '2026-07-03 15:52:26'),
(30, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '36', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-03 15:53:35', '2026-07-03 15:53:35'),
(31, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '36', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"revisi\"}}', NULL, '2026-07-03 16:06:06', '2026-07-03 16:06:06'),
(32, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '36', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"revisi\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-03 16:07:26', '2026-07-03 16:07:26'),
(33, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '36', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"final\"}}', NULL, '2026-07-03 16:08:42', '2026-07-03 16:08:42'),
(34, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f286c-e202-73d5-a36b-9dfabc783460', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"disetujui\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"selesai\", \"keterangan_status\": \"Kegiatan telah diselesaikan berdasarkan laporan final.\"}}', NULL, '2026-07-03 16:08:42', '2026-07-03 16:08:42'),
(35, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2ac4-606b-71ec-8e32-6457174c6d52', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"survei dan pemetaan pesisir pulau gelam\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-04 01:35:40', '2026-07-04 01:35:40'),
(36, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f2ac4-606b-71ec-8e32-6457174c6d52', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-04 01:38:05', '2026-07-04 01:38:05'),
(37, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2af2-66cd-719d-9567-4dfe37bfe5de', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"Pengambilan Data dan Dokumentasi Ekosistem Lamun di Pulau Cempedak\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-04 02:25:56', '2026-07-04 02:25:56'),
(38, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f2af2-66cd-719d-9567-4dfe37bfe5de', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-04 02:32:02', '2026-07-04 02:32:02'),
(39, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2b0c-4de7-70fd-8571-6dea3cf70741', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"Patroli Dugong\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-04 02:54:14', '2026-07-04 02:54:14'),
(40, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f2b0c-4de7-70fd-8571-6dea3cf70741', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-04 02:59:33', '2026-07-04 02:59:33'),
(41, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f2af2-66cd-719d-9567-4dfe37bfe5de', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"perbaiki lagi detail kegiatnnya\"}}', NULL, '2026-07-04 03:12:10', '2026-07-04 03:12:10'),
(42, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2b26-1aa1-73f3-882c-bd34513b1993', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"Penanaman Mangrove di Pulau Cempedak\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-04 03:22:25', '2026-07-04 03:22:25'),
(43, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f2b26-1aa1-73f3-882c-bd34513b1993', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"ditolak\", \"keterangan_status\": \"tanggal dan waktu kegiatan bentrok dengan kegiatan lain\"}}', NULL, '2026-07-04 03:32:58', '2026-07-04 03:32:58'),
(44, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f2b0c-4de7-70fd-8571-6dea3cf70741', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"disetujui\"}}', NULL, '2026-07-04 04:10:59', '2026-07-04 04:10:59'),
(45, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '37', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\"}}', NULL, '2026-07-04 14:00:01', '2026-07-04 14:00:01'),
(46, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f2ffe-261c-737c-8678-18ffc17af86a', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"Diskusi Mengenai Tree Planting\", \"jenis_kegiatan\": \"lainnya\", \"keterangan_status\": null}}', NULL, '2026-07-05 01:56:52', '2026-07-05 01:56:52'),
(47, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f3081-e636-718b-a4a9-21ee4f97b5d2', 'App\\Models\\User', 4, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"FGD Reviu Rencana Pengelolaan dan Zonasi (RPZ) Kawasan Konservasi Pesisir dan Pulau-Pulau Kecil (KKPD) Kendawangan dan Perairan di Sekitarnya\", \"jenis_kegiatan\": \"lainnya\", \"keterangan_status\": null}}', NULL, '2026-07-05 04:20:47', '2026-07-05 04:20:47'),
(48, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '37', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-05 17:17:12', '2026-07-05 17:17:12'),
(49, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '38', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\"}}', NULL, '2026-07-05 18:10:02', '2026-07-05 18:10:02'),
(50, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f3081-e636-718b-a4a9-21ee4f97b5d2', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"perbaiki rincian kebutuhannya\"}}', NULL, '2026-07-07 05:58:39', '2026-07-07 05:58:39'),
(51, 'default', 'Laporan Kegiatan deleted', 'App\\Models\\LaporanKegiatan', 'deleted', '38', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}}', NULL, '2026-07-08 02:18:43', '2026-07-08 02:18:43'),
(52, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '37', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"revisi\"}}', NULL, '2026-07-11 15:19:34', '2026-07-11 15:19:34'),
(53, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f7080-9714-7389-886a-6634c5e68139', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"Penanaman Mangrove di Pulau Cempedak\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-17 14:35:04', '2026-07-17 14:35:04'),
(54, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f7086-2181-70f8-bb25-527b4d8cb17d', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"Aksi Bersih Pantai Desa Sungai Nanjung\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": null}}', NULL, '2026-07-17 14:41:06', '2026-07-17 14:41:06'),
(55, 'default', 'Rencana Kegiatan deleted', 'App\\Models\\RencanaKegiatan', 'deleted', '019f2af2-66cd-719d-9567-4dfe37bfe5de', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\", \"nama_kegiatan\": \"Pengambilan Data dan Dokumentasi Ekosistem Lamun di Pulau Cempedak\", \"jenis_kegiatan\": \"konservasi\", \"keterangan_status\": \"perbaiki lagi detail kegiatnnya\"}}', NULL, '2026-07-17 14:55:51', '2026-07-17 14:55:51'),
(56, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f7086-2181-70f8-bb25-527b4d8cb17d', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"disetujui\"}}', NULL, '2026-07-17 15:01:47', '2026-07-17 15:01:47'),
(57, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f2ac4-606b-71ec-8e32-6457174c6d52', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"lengkapi rincian kebutuhannya\"}}', NULL, '2026-07-17 15:08:13', '2026-07-17 15:08:13'),
(58, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '39', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\"}}', NULL, '2026-07-17 16:16:37', '2026-07-17 16:16:37'),
(59, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '40', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-17 16:35:56', '2026-07-17 16:35:56'),
(60, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '41', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"draft\"}}', NULL, '2026-07-17 16:52:46', '2026-07-17 16:52:46'),
(61, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '42', 'App\\Models\\User', 15, '{\"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-17 17:04:59', '2026-07-17 17:04:59'),
(62, 'default', 'Laporan Kegiatan deleted', 'App\\Models\\LaporanKegiatan', 'deleted', '36', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}}', NULL, '2026-07-18 01:33:01', '2026-07-18 01:33:01'),
(63, 'default', 'Laporan Kegiatan deleted', 'App\\Models\\LaporanKegiatan', 'deleted', '39', 'App\\Models\\User', 15, '{\"old\": {\"status\": \"draft\"}}', NULL, '2026-07-18 01:33:43', '2026-07-18 01:33:43'),
(64, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '40', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"final\"}}', NULL, '2026-07-18 01:38:17', '2026-07-18 01:38:17'),
(65, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f7086-2181-70f8-bb25-527b4d8cb17d', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"disetujui\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"selesai\", \"keterangan_status\": \"Kegiatan telah diselesaikan berdasarkan laporan final.\"}}', NULL, '2026-07-18 01:38:17', '2026-07-18 01:38:17'),
(66, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '40', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"final\"}}', NULL, '2026-07-18 01:45:50', '2026-07-18 01:45:50'),
(67, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f77b7-a8a1-71ad-aebe-65907176d8e9', 'App\\Models\\User', 30, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"Survey ODTW Kendawangan\", \"jenis_kegiatan\": \"lainnya\", \"keterangan_status\": null}}', NULL, '2026-07-19 00:12:34', '2026-07-19 00:12:34'),
(68, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f77c2-d956-7320-a60d-1d0b52ad39b8', 'App\\Models\\User', 30, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026\", \"jenis_kegiatan\": \"lainnya\", \"keterangan_status\": null}}', NULL, '2026-07-19 00:24:46', '2026-07-19 00:24:46'),
(69, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f77e8-ba6a-7289-8eb6-deb1aacf8526', 'App\\Models\\User', 29, '{\"attributes\": {\"status\": \"diajukan\", \"nama_kegiatan\": \"Agenda Inisiasi Garda Emak Pelapis (dukungan sarana transportasi laut)\", \"jenis_kegiatan\": \"usaha masyarakat\", \"keterangan_status\": null}}', NULL, '2026-07-19 01:06:08', '2026-07-19 01:06:08'),
(70, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f77c2-d956-7320-a60d-1d0b52ad39b8', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"disetujui\"}}', NULL, '2026-07-19 01:27:06', '2026-07-19 01:27:06'),
(71, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '43', 'App\\Models\\User', 27, '{\"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-19 01:58:12', '2026-07-19 01:58:12'),
(72, 'default', 'Laporan Kegiatan created', 'App\\Models\\LaporanKegiatan', 'created', '44', 'App\\Models\\User', 30, '{\"attributes\": {\"status\": \"draft\"}}', NULL, '2026-07-19 02:18:31', '2026-07-19 02:18:31'),
(73, 'default', 'Laporan Kegiatan updated', 'App\\Models\\LaporanKegiatan', 'updated', '44', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\"}, \"attributes\": {\"status\": \"final\"}}', NULL, '2026-07-19 02:59:13', '2026-07-19 02:59:13'),
(74, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f77c2-d956-7320-a60d-1d0b52ad39b8', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"disetujui\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"selesai\", \"keterangan_status\": \"Kegiatan telah diselesaikan berdasarkan laporan final.\"}}', NULL, '2026-07-19 02:59:14', '2026-07-19 02:59:14'),
(75, 'default', 'Rencana Kegiatan created', 'App\\Models\\RencanaKegiatan', 'created', '019f84f0-7a83-738a-87dc-12044e61b8b1', 'App\\Models\\User', 27, '{\"attributes\": {\"status\": \"draft\", \"nama_kegiatan\": \"Peningkatan Kapasitas Internal Tim WeBe\", \"jenis_kegiatan\": \"lainnya\", \"keterangan_status\": null}}', NULL, '2026-07-21 13:49:41', '2026-07-21 13:49:41'),
(76, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f84f0-7a83-738a-87dc-12044e61b8b1', 'App\\Models\\User', 27, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"diajukan\"}}', NULL, '2026-07-21 14:02:47', '2026-07-21 14:02:47'),
(77, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f77b7-a8a1-71ad-aebe-65907176d8e9', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"tolong perbaiki rincian kebutuhannya\"}}', NULL, '2026-07-22 01:36:39', '2026-07-22 01:36:39'),
(78, 'default', 'Rencana Kegiatan updated', 'App\\Models\\RencanaKegiatan', 'updated', '019f77e8-ba6a-7289-8eb6-deb1aacf8526', 'App\\Models\\User', 1, '{\"old\": {\"status\": \"diajukan\", \"keterangan_status\": null}, \"attributes\": {\"status\": \"revisi\", \"keterangan_status\": \"perbaiki rincian kebutuhannya\"}}', NULL, '2026-07-22 01:46:32', '2026-07-22 01:46:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin@admn.com|::1', 'i:1;', 1782809721),
('laravel-cache-admin@admn.com|::1:timer', 'i:1782809721;', 1782809721),
('laravel-cache-admin@gmail.com|::1', 'i:1;', 1783429604),
('laravel-cache-admin@gmail.com|::1:timer', 'i:1783429604;', 1783429604),
('laravel-cache-admin@webe.com|::1', 'i:1;', 1783957115),
('laravel-cache-admin@webe.com|::1:timer', 'i:1783957115;', 1783957115);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_kegiatans`
--

CREATE TABLE `laporan_kegiatans` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rencana_kegiatan_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `realisasi_tanggal_mulai` date DEFAULT NULL,
  `realisasi_tanggal_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `metode_lainnya` text COLLATE utf8mb4_unicode_ci,
  `rangkaian_kegiatan` longtext COLLATE utf8mb4_unicode_ci,
  `target_peserta` int DEFAULT NULL,
  `realisasi_peserta` int DEFAULT NULL,
  `profil_peserta` text COLLATE utf8mb4_unicode_ci,
  `hasil_dicapai` text COLLATE utf8mb4_unicode_ci,
  `output_nyata` longtext COLLATE utf8mb4_unicode_ci,
  `dampak_awal` longtext COLLATE utf8mb4_unicode_ci,
  `kendala` text COLLATE utf8mb4_unicode_ci,
  `solusi` text COLLATE utf8mb4_unicode_ci,
  `evaluasi_rekomendasi` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','diajukan','revisi','final') COLLATE utf8mb4_unicode_ci DEFAULT 'diajukan',
  `kendala_dihadapi` longtext COLLATE utf8mb4_unicode_ci,
  `solusi_dilakukan` longtext COLLATE utf8mb4_unicode_ci,
  `foto_kegiatan` json DEFAULT NULL,
  `daftar_hadir` json DEFAULT NULL,
  `notulen` json DEFAULT NULL,
  `materi` json DEFAULT NULL,
  `berita_acara` json DEFAULT NULL,
  `hasil_yang_dicapai` longtext COLLATE utf8mb4_unicode_ci,
  `catatan_evaluasi` longtext COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `laporan_kegiatans`
--

INSERT INTO `laporan_kegiatans` (`id`, `uuid`, `rencana_kegiatan_id`, `judul_kegiatan`, `lokasi_kegiatan`, `realisasi_tanggal_mulai`, `realisasi_tanggal_selesai`, `created_at`, `updated_at`, `user_id`, `metode_lainnya`, `rangkaian_kegiatan`, `target_peserta`, `realisasi_peserta`, `profil_peserta`, `hasil_dicapai`, `output_nyata`, `dampak_awal`, `kendala`, `solusi`, `evaluasi_rekomendasi`, `status`, `kendala_dihadapi`, `solusi_dilakukan`, `foto_kegiatan`, `daftar_hadir`, `notulen`, `materi`, `berita_acara`, `hasil_yang_dicapai`, `catatan_evaluasi`, `deleted_at`) VALUES
(43, '7fb637ce-7a0a-44f8-9ec9-16023d176fda', NULL, 'Penanganan dan Pemeriksaan Dugong Mati di Perairan Kendawangan', 'Perairan Kendawangan, Kecamatan Kendawangan, Kabupaten Ketapang, Kalimantan Barat', '2026-06-24', '2026-06-24', '2026-07-19 01:58:12', '2026-07-19 01:58:12', 27, NULL, '<ol><li data-section-id=\"1h2lx17\" data-start=\"138\" data-end=\"228\">Menerima laporan dari masyarakat mengenai penemuan dugong mati di perairan Kendawangan.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span>\r\n</li><li data-section-id=\"um8iyx\" data-start=\"229\" data-end=\"290\">\r\nMelakukan verifikasi awal dan dokumentasi lokasi penemuan.\r\n</li><li data-section-id=\"1luxbna\" data-start=\"291\" data-end=\"374\">\r\nBerkoordinasi dengan Pokmaswas Cempedak Lestari, Polair, dan Polsek Kendawangan.\r\n</li><li data-section-id=\"1k4wbfw\" data-start=\"375\" data-end=\"436\">\r\nMelaksanakan evakuasi bangkai dugong dari lokasi penemuan.\r\n</li><li data-section-id=\"12tko1n\" data-start=\"437\" data-end=\"554\">\r\nMelakukan identifikasi visual dan pemeriksaan morfometrik (pengukuran panjang, lingkar tubuh, dan estimasi berat).\r\n</li><li data-section-id=\"1vn1afg\" data-start=\"555\" data-end=\"622\">\r\nMendokumentasikan hasil pemeriksaan dalam bentuk foto dan video.</li><li data-section-id=\"adl4rv\" data-start=\"623\" data-end=\"749\">\r\nMenyusun laporan hasil pemeriksaan serta melakukan koordinasi untuk investigasi lanjutan mengenai penyebab kematian dugong.</li></ol>', 0, 8, '<p>Pokmaswas Cempedak Lestari, Polair, Polsek Kendawangan, Masyarakat Desa Mekar Utama</p>', '<ul><li data-section-id=\"2dhx9a\" data-start=\"1225\" data-end=\"1282\">Dugong berhasil diidentifikasi sebagai individu betina.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span>\r\n</li><li data-section-id=\"svl5hl\" data-start=\"1283\" data-end=\"1427\">\r\nDilakukan pengukuran morfometrik dengan panjang tubuh sekitar <strong data-start=\"1347\" data-end=\"1357\">285 cm</strong>, lingkar tubuh sekitar <strong data-start=\"1381\" data-end=\"1391\">220 cm</strong>, dan estimasi berat <strong data-start=\"1412\" data-end=\"1426\">250–270 kg</strong>.\r\n</li><li data-section-id=\"1ac1s8x\" data-start=\"1428\" data-end=\"1530\">\r\nKondisi bangkai telah mengalami pembusukan lanjut sehingga penyebab kematian belum dapat dipastikan.</li><li data-section-id=\"omm2pn\" data-start=\"1531\" data-end=\"1614\">\r\nSeluruh temuan didokumentasikan sebagai bahan pelaporan dan investigasi lanjutan.</li></ul>', '<ul><li data-section-id=\"1mfph2j\" data-start=\"203\" data-end=\"265\">1 ekor dugong mati berhasil dievakuasi dan didokumentasikan.<span aria-hidden=\"true\" class=\"PDq2pG_selectionAnchor\"></span>\r\n</li><li data-section-id=\"r6x2a7\" data-start=\"266\" data-end=\"341\">\r\nData identifikasi dan pengukuran morfometrik dugong berhasil dikumpulkan.\r\n</li><li data-section-id=\"1nsjre6\" data-start=\"342\" data-end=\"423\">\r\nLaporan hasil pemeriksaan lapangan tersusun sebagai bahan investigasi lanjutan.</li><li data-section-id=\"czf4xu\" data-start=\"424\" data-end=\"482\">\r\nDokumentasi foto dan video kegiatan penanganan tersedia.</li></ul>', '<p>Dugong mati berhasil ditangani dan didokumentasikan dengan baik, sehingga diperoleh data awal mengenai kondisi satwa untuk mendukung investigasi penyebab kematian serta memperkuat koordinasi antarinstansi dalam upaya konservasi satwa laut dilindungi.</p>', '<p>Kondisi bangkai dugong telah mengalami pembusukan lanjut sehingga menyulitkan proses identifikasi penyebab kematian secara pasti.</p>', '<p>Meningkatkan koordinasi dengan instansi terkait dan masyarakat agar laporan penemuan satwa dilindungi dapat diterima lebih cepat, serta melakukan penanganan sesuai prosedur konservasi.</p>', '<p>Kegiatan penanganan dugong mati telah dilaksanakan sesuai prosedur melalui koordinasi dengan berbagai pihak. Ke depan, perlu meningkatkan kecepatan pelaporan dari masyarakat dan respons lapangan agar kondisi satwa dapat segera diperiksa sehingga penyebab kematian dapat diidentifikasi dengan lebih akurat.</p>', 'diajukan', NULL, NULL, '[{\"path\": \"laporan_kegiatan/foto_kegiatan/1784426292_WhatsApp_Image_2026-07-19_at_08.53.53.jpeg\", \"original_name\": \"WhatsApp Image 2026-07-19 at 08.53.53.jpeg\"}]', '[{\"path\": \"laporan_kegiatan/daftar_hadir/1784426292_Daftar_Hadir_Kegiatan_Dugong.pdf\", \"original_name\": \"Daftar_Hadir_Kegiatan_Dugong.pdf\"}]', NULL, '[{\"path\": \"laporan_kegiatan/materi/1784426292_Materi_Kegiatan_Dugong.pdf\", \"original_name\": \"Materi_Kegiatan_Dugong.pdf\"}]', NULL, NULL, NULL, NULL),
(44, '1c0b2ada-def6-4842-86f8-c06d1d6c55e7', '019f77c2-d956-7320-a60d-1d0b52ad39b8', NULL, NULL, '2026-05-05', '2026-07-07', '2026-07-19 02:18:31', '2026-07-19 02:59:13', 30, NULL, '<p style=\"font-size: 16px;\">LAPORAN KEGIATAN PEMETAAN KAWASAN</p><p style=\"font-size: 16px;\">Lokasi: Kendawangan – Pulau Gelam – Pulau Cempedak</p><p style=\"font-size: 16px;\">Periode: 5 – 7 Mei 2026</p><p style=\"font-size: 16px;\">1. Ringkasan Kegiatan</p><p style=\"font-size: 16px;\">Kegiatan ini difokuskan pada pemetaan udara (drone) di sejumlah titik koordinat yang telah ditentukan di kawasan Pulau Gelam, serta mobilisasi logistik melalui jalur Kendawangan dan Pulau Cempedak.</p><p style=\"font-size: 16px;\">2. Rincian Perjalanan dan Operasional</p><p style=\"font-size: 16px;\">Hari Pertama (5 Mei 2026)</p><p style=\"font-size: 16px;\">Keberangkatan: Tim berangkat dari Ketapang menuju Kendawangan.</p><p style=\"font-size: 16px;\">Persiapan: Melakukan pembelian alat pendukung dan pemenuhan kebutuhan logistik tim.</p><p style=\"font-size: 16px;\">Mobilisasi: Menuju Pulau Cempedak untuk pengambilan logistik tambahan, kemudian langsung bertolak menuju Pulau Gelam.</p><p style=\"font-size: 16px;\">Kegiatan Lapangan: Setibanya di lokasi, tim langsung melakukan pemetaan pada Titik 35 dan Titik 36. Kegiatan berlangsung hingga sore hari.</p><p style=\"font-size: 16px;\">Hari Kedua (6 Mei 2026)</p><p style=\"font-size: 16px;\">Waktu Operasional: 06.00 WIB – 17.10 WIB.</p><p style=\"font-size: 16px;\">Kegiatan Lapangan: Melanjutkan pemetaan udara secara intensif pada 7 titik koordinat:</p><p style=\"font-size: 16px;\">Titik: 31, 26, 27, 19, 10, 9, dan 4.</p><p style=\"font-size: 16px;\">Status: Seluruh target titik pada hari kedua berhasil terpetakan dengan aman.</p><p style=\"font-size: 16px;\">Hari Ketiga (7 Mei 2026)</p><p style=\"font-size: 16px;\">Waktu Operasional: 05.30 WIB – 12.30 WIB.</p><p style=\"font-size: 16px;\">Kegiatan Lapangan: Melakukan pemetaan pada 6 titik terakhir:</p><p style=\"font-size: 16px;\">Titik: 3, 2, 1, 5, 20, dan 28.</p><p style=\"font-size: 16px;\">Mobilisasi Kepulangan: Setelah seluruh titik pemetaan selesai, tim kembali menuju Pulau Cempedak.</p><p style=\"font-size: 16px;\">Demobilisasi: Melakukan pembongkaran alat-alat pendukunh dan menyimpannya dengan aman di Pos DKP.</p><p style=\"font-size: 16px;\">3. Kendala Lapangan &amp; Catatan Khusus</p><p style=\"font-size: 16px;\">Pembatalan Pemantauan Lamun:</p><p style=\"font-size: 16px;\">Rencana kegiatan pemantauan lamun tidak dapat direalisasikan dikarenakan kondisi teknis sebagai berikut:</p><p style=\"font-size: 16px;\">Visibilitas Rendah: Kondisi air yang sangat keruh tidak memungkinkan untuk melakukan pemantauan bawah air.</p><p style=\"font-size: 16px;\">Keamanan Tim: Adanya risiko tinggi terkait faktor keselamatan (potensi keberadaan predator/buaya) di area air keruh tersebut, sehingga tim memutuskan untuk tidak turun ke air.</p><p style=\"font-size: 16px;\">pada saat pagi air surut dan tidak lepeh tidak bisa menjangkau lokasi yang ada lamunya dikarenakan akun sangkut batu dan karang</p>', NULL, 5, '<p data-path-to-node=\"5\" style=\"font-size: 16px;\">Kegiatan pemetaan ini dilaksanakan oleh tim gabungan yang terdiri dari 5 personel:</p><ul data-path-to-node=\"6\" style=\"font-size: 16px;\"><li><p data-path-to-node=\"6,0,0\"><span data-path-to-node=\"6,0,0\" data-index-in-node=\"0\" style=\"font-weight: bolder;\">Ketua Tim:</span> Dang Yanto (Ketua Pokmaswas Cempedak Lestari)</p></li><li><p data-path-to-node=\"6,1,0\"><span data-path-to-node=\"6,1,0\" data-index-in-node=\"0\" style=\"font-weight: bolder;\">Anggota Tim (Yayasan WeBe):</span><span style=\"font-size: 1rem;\">Evyandri, </span><span style=\"font-size: 1rem;\">Sandi, A</span><span style=\"font-size: 1rem;\">ndre, M</span><span style=\"font-size: 1rem;\">. Luqinul</span></p></li></ul>', '<ul style=\"font-size: 16px;\"><li>Kelengkapan Data Spasial: Berhasil mengumpulkan data foto udara dari 15 titik koordinat strategis (Titik 1, 2, 3, 4, 5, 9, 10, 19, 20, 26, 27, 28, 31, 35, dan 36) di kawasan Pulau Gelam.</li><li>Efisiensi Waktu: Operasional pemetaan berjalan sesuai jadwal (3 hari) dengan pemanfaatan waktu kerja yang optimal, dimulai sejak pagi buta (05:30) untuk mendapatkan kondisi cahaya dan angin terbaik bagi drone.</li></ul>', '<ul style=\"font-size: 16px;\"><li>Kumpulan Foto Udara Resolusi Tinggi: Data mentah&nbsp; dari seluruh titik yang siap diproses lebih lanjut.</li><li>Peta Citra Udara (Orthophoto): Dokumen visual terbaru yang menggambarkan kondisi eksisting kawasan Pulau Gelam dan sekitarnya secara akurat.</li><li><span data-path-to-node=\"6,3,0\" data-index-in-node=\"0\">Laporan Kendala Lapangan:</span> Catatan penting mengenai kondisi perairan dan ancaman predator sebagai referensi perencanaan kegiatan pemantauan lamun di periode berikutnya.</li></ul>', '<ul style=\"font-size: 16px;\"><li>Akurasi Perencanaan: Tersedianya data spasial yang akurat akan membantu Yayasan WeBe dan Pokmaswas Cempedak Lestari dalam menentukan kebijakan konservasi atau pengelolaan wilayah yang tepat sasaran.</li><li>Kesiapan Data Baseline: Hasil pemetaan ini menjadi data dasar (baseline) yang sangat penting untuk memantau perubahan lingkungan atau penggunaan lahan di kawasan tersebut di tahun-tahun mendatang.</li></ul>', '<p><span style=\"font-size: 16px;\">kondisi angin kurang bersahabat mengakibatkan adanya gelombang dan air menjadi keruh</span></p>', '<p><span style=\"font-size: 16px;\">pada saat angin dan gelombang ada tim melakukan antisipasi masuk ke teluk pulau agar tidak terlalu terkena gelombang dan pemantauan lamun di tindakan.&nbsp;</span></p>', '<ul style=\"font-size: 16px;\"><li>tim menunjukkan sikap profesional terhadap pekerjaan dengan terselesaikannya titik-titik yang akan di ambil.&nbsp;</li><li>mungkin bisa di jadwalkan ulang untuk pemantauan lamun pada kondisi cuaca yang lebih aman.&nbsp;</li><li>kami juga menemukan 2 jenis lamun yang tersangkut di jangkar lepeh pada titik 2</li></ul>', 'draft', NULL, NULL, '[{\"path\": \"laporan_kegiatan/foto_kegiatan/1784427511_survey_pulau_gelam_1.jpg\", \"original_name\": \"survey pulau gelam 1.jpg\"}, {\"path\": \"laporan_kegiatan/foto_kegiatan/1784427511_survey_pulau_gelam_2.jpg\", \"original_name\": \"survey pulau gelam 2.jpg\"}, {\"path\": \"laporan_kegiatan/foto_kegiatan/1784427511_survey_pulau_gelam_3.jpg\", \"original_name\": \"survey pulau gelam 3.jpg\"}, {\"path\": \"laporan_kegiatan/foto_kegiatan/1784427511_survey_pulau_gelam_4.jpg\", \"original_name\": \"survey pulau gelam 4.jpg\"}]', '[{\"path\": \"laporan_kegiatan/daftar_hadir/1784427511_Daftar_Hadir_Survei_Pulau_Gelam.pdf\", \"original_name\": \"Daftar_Hadir_Survei_Pulau_Gelam.pdf\"}]', NULL, '[{\"path\": \"laporan_kegiatan/materi/1784427511_Materi_Survei_Pulau_Gelam.pdf\", \"original_name\": \"Materi_Survei_Pulau_Gelam.pdf\"}]', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(7, '2025_11_26_131647_create_pegawais_table', 2),
(8, '2025_11_26_140031_add_nip_to_table_pegawais', 3),
(9, '2025_11_26_140822_rename_nip_to_nik_table_pegawais', 4),
(10, '2025_12_10_035711_add_column_foto_pegawai_table_pegawais', 5),
(11, '2025_12_10_100644_add_column_pegawai_id_table_users', 6),
(12, '2025_12_10_101337_add_column_user_id_table_pegawais', 7),
(13, '2025_12_10_135121_create_bagians_table', 8),
(14, '2025_12_10_140403_add_column_bagian_id_table_pegawais', 9),
(15, '2025_12_11_034254_create_roles_table', 10),
(18, '2025_12_11_040353_add_column_role_id_teble_users', 11),
(19, '2025_12_15_000000_create_reports_table', 11),
(20, '2025_12_15_010000_add_activity_fields_to_reports_table', 11),
(21, '2025_12_15_020000_remove_pelapor_columns_from_reports_table', 12),
(22, '2025_12_15_030000_rename_reports_table_to_rencana_kegiatans', 13),
(23, '2025_12_23_134544_change_status_enum_on_rencana_kegiatans_table', 14),
(24, '2025_12_30_093304_add_uuid_to_rencana_kegiatans_table', 15),
(27, '2025_12_30_142330_replace_estimasi_anggaran_with_rincian_kebutuhan', 16),
(28, '2026_01_01_044056_add_uuid_to_rencana_kegiatans_table', 17),
(29, '2026_01_01_071748_alter_status_column_on_rencana_kegiatans_table', 18),
(30, '2026_01_06_022755_change_foto_column_type_on_rencana_kegiatans_table', 19),
(31, '2026_01_07_033951_change_foto_column_to_json_in_rencana_kegiatans_table', 20),
(32, '2026_01_07_130546_change_dokumen_column_to_json_on_rencana_kegiatans_table', 21),
(33, '2026_01_09_035830_drop_pegawai_id_from_users_table', 22),
(34, '2026_01_10_032013_add_uuid_to_users_table', 23),
(37, '2026_01_10_064356_add_keterangan_status_to_rencana_kegiatans_table', 24),
(38, '2026_01_10_064603_update_status_enum_on_rencana_kegiatans_table', 24),
(39, '2026_01_21_024307_drop_bagians_table', 25),
(41, '2026_02_06_150420_add_user_id_to_rencana_kegiatans_table', 26),
(49, '2026_02_21_100824_add_uuid_kegiatan_to_notifications_table', 31),
(50, '2026_02_28_112833_add_structured_fields_to_laporan_kegiatans_table', 32),
(51, '2026_01_10_144132_create_laporan_kegiatans_table', 33),
(52, '2026_01_21_030404_drop_pegawais_table', 33),
(53, '2026_02_06_150459_add_user_id_to_laporan_kegiatans_table', 34),
(60, '2026_02_08_033444_update_existing_laporan_kegiatan_uuid', 35),
(61, '2026_02_09_025959_remove_judul_and_kategori_from_rencana_kegiatans_table', 35),
(62, '2026_02_20_093857_create_notifications_table', 35),
(63, '2026_02_28_120000_fix_laporan_kegiatans_migration', 35),
(64, '2026_02_28_130000_fix_column_names', 36),
(65, '2026_03_02_135717_fix_laporan_kegiatans_structure', 37),
(66, '2026_03_02_135902_clean_laporan_kegiatans_table', 38),
(67, '2026_03_02_143725_fix_laporan_kegiatan_file_paths', 39),
(68, '2026_03_02_144139_fix_laporan_kegiatan_photo_paths', 40),
(70, '2026_03_02_212527_update_laporan_kegiatans_columns_to_json', 41),
(71, '2026_03_02_215813_add_new_fields_to_laporan_kegiatans_table', 41),
(72, '2026_03_03_100000_add_waktu_fields_to_laporan_kegiatans', 42),
(73, '2026_03_03_102000_add_missing_fields_to_laporan_kegiatans', 43),
(74, '2026_03_08_164053_add_waktu_fields_to_rencana_kegiatans_table', 44),
(75, '2026_03_08_164219_remove_waktu_fields_from_laporan_kegiatans_table', 44),
(76, '2026_03_09_221408_add_jenis_kegiatan_lainnya_to_rencana_kegiatans_table', 45),
(77, '2026_03_10_094800_change_jenis_kegiatan_to_enum_on_rencana_kegiatans_table', 46),
(78, '2026_04_02_202344_add_realisasi_tanggal_fields_to_laporan_kegiatans_table', 47),
(79, '2026_04_02_205834_remove_metode_pelaksanaan_fields_from_laporan_kegiatans_table', 48),
(80, '2026_04_04_091855_add_status_revisi_to_rencana_kegiatans_table', 49),
(81, '2026_04_06_102533_add_anggaran_kegiatan_to_rencana_kegiatans_table', 50),
(82, '2026_06_30_153733_add_menunggu_verifikasi_to_rencana_kegiatans_table', 51),
(83, '2026_06_30_205821_update_status_on_laporan_kegiatans_table', 52),
(84, '2026_06_30_221134_add_deleted_at_to_kegiatans_table', 53),
(85, '2026_06_30_234936_create_activity_log_table', 54),
(86, '2026_06_30_234937_add_event_column_to_activity_log_table', 54),
(87, '2026_06_30_234938_add_batch_uuid_column_to_activity_log_table', 54),
(88, '2026_07_01_094700_fix_activity_log_subject_id_to_uuid', 55),
(89, '2026_07_01_215223_clean_up_status_enum_on_rencana_and_laporan_tables', 56),
(90, '2026_07_03_092806_modify_status_enum_in_rencana_kegiatans_table', 57),
(91, '2026_07_04_112508_modify_laporan_kegiatans_for_laporan_langsung', 58);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0186de1d-2a81-4481-9247-49e31b7f2a99', 'App\\Notifications\\KegiatanActivityNotification', 'App\\Models\\User', 1, '{\"id_kegiatan\":\"019f77e8-ba6a-7289-8eb6-deb1aacf8526\",\"judul_kegiatan\":\"Agenda Inisiasi Garda Emak Pelapis (dukungan sarana transportasi laut)\",\"aksi\":\"diajukan\",\"user_name\":\"Hesty Yolanda\",\"keterangan\":null,\"created_at\":\"2026-07-19T01:06:08.683843Z\",\"message\":\"Rencana kegiatan \'Agenda Inisiasi Garda Emak Pelapis (dukungan sarana transportasi laut)\' diajukan oleh Hesty Yolanda\",\"type\":\"rencana_kegiatan\"}', '2026-07-19 01:07:21', '2026-07-19 01:06:08', '2026-07-19 01:07:21'),
('01b90f89-fa4a-4ce0-8a38-2411e07777e7', 'App\\Notifications\\KegiatanActivityNotification', 'App\\Models\\User', 1, '{\"id_kegiatan\":\"019f2af2-66cd-719d-9567-4dfe37bfe5de\",\"judul_kegiatan\":\"Pengambilan Data dan Dokumentasi Ekosistem Lamun di Pulau Cempedak\",\"aksi\":\"dihapus\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-17T14:55:51.561614Z\",\"message\":\"Rencana kegiatan \'Pengambilan Data dan Dokumentasi Ekosistem Lamun di Pulau Cempedak\' dihapus oleh septian\",\"type\":\"rencana_kegiatan\"}', '2026-07-17 15:12:55', '2026-07-17 14:55:51', '2026-07-17 15:12:55'),
('049dc641-7036-4b65-8432-d8014f8f8345', 'App\\Notifications\\LaporanActivityNotification', 'App\\Models\\User', 1, '{\"id_laporan\":\"4c2f20f7-2d8b-4183-b909-f86bc348c266\",\"id_kegiatan\":\"019f2b0c-4de7-70fd-8571-6dea3cf70741\",\"judul_laporan\":null,\"judul_kegiatan\":\"Patroli Dugong\",\"aksi\":\"dihapus\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-08T02:18:44.034997Z\",\"message\":\"Laporan kegiatan \'\' dihapus oleh septian\",\"type\":\"laporan_kegiatan\"}', '2026-07-16 16:29:39', '2026-07-08 02:18:48', '2026-07-16 16:29:39'),
('0b0d7362-c2fc-4d87-ac14-0fd7d000dbff', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 24, '{\"id_kegiatan\":\"019d2009-9cbd-732a-ae4a-a89567750248\",\"judul_kegiatan\":\"Patroli Dugong\",\"status_baru\":\"disetujui\",\"keterangan\":\"mantap\",\"updated_at\":\"2026-03-24T13:42:17.608666Z\",\"message\":\"Kegiatan Patroli Dugong telah diubah menjadi disetujui\"}', '2026-03-24 13:42:57', '2026-03-24 13:42:17', '2026-03-24 13:42:57'),
('12529f91-012b-4f23-a232-a3b30338540a', 'App\\Notifications\\KegiatanActivityNotification', 'App\\Models\\User', 1, '{\"id_kegiatan\":\"019f77b7-a8a1-71ad-aebe-65907176d8e9\",\"judul_kegiatan\":\"Survey ODTW Kendawangan\",\"aksi\":\"diajukan\",\"user_name\":\"M. Luqinul Mifdlol Assiddiqi\",\"keterangan\":null,\"created_at\":\"2026-07-19T00:12:34.380884Z\",\"message\":\"Rencana kegiatan \'Survey ODTW Kendawangan\' diajukan oleh M. Luqinul Mifdlol Assiddiqi\",\"type\":\"rencana_kegiatan\"}', '2026-07-19 00:59:16', '2026-07-19 00:12:35', '2026-07-19 00:59:16'),
('16f0c0c9-7cc8-4d06-82cc-eb2602bd3626', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 15, '{\"id_kegiatan\":\"019f2af2-66cd-719d-9567-4dfe37bfe5de\",\"judul_kegiatan\":\"Pengambilan Data dan Dokumentasi Ekosistem Lamun di Pulau Cempedak\",\"status_baru\":\"revisi\",\"keterangan\":\"perbaiki lagi detail kegiatnnya\",\"updated_at\":\"2026-07-04T03:12:10.207128Z\",\"message\":\"Kegiatan Pengambilan Data dan Dokumentasi Ekosistem Lamun di Pulau Cempedak telah diubah menjadi revisi\",\"type\":\"rencana_kegiatan\"}', '2026-07-04 03:13:03', '2026-07-04 03:12:10', '2026-07-04 03:13:03'),
('200d0dd5-5ea7-47ff-bfd9-4dfd830ab2e7', 'App\\Notifications\\StatusLaporanNotification', 'App\\Models\\User', 15, '{\"id_laporan\":\"669378e2-9173-44a8-960a-3d87194fda52\",\"judul_kegiatan\":\"Aksi Bersih Pantai Desa Sungai Nanjung\",\"status_baru\":\"final\",\"keterangan\":\"Laporan telah diterima dan kegiatan selesai.\",\"updated_at\":\"2026-07-18T01:45:50.149819Z\",\"message\":\"Laporan kegiatan Aksi Bersih Pantai Desa Sungai Nanjung telah diubah menjadi final\",\"type\":\"laporan_kegiatan\"}', '2026-07-18 01:46:43', '2026-07-18 01:45:50', '2026-07-18 01:46:43'),
('225494f2-545d-4a00-8091-aafe06c2e62a', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 4, '{\"id_kegiatan\":\"019f3081-e636-718b-a4a9-21ee4f97b5d2\",\"judul_kegiatan\":\"FGD Reviu Rencana Pengelolaan dan Zonasi (RPZ) Kawasan Konservasi Pesisir dan Pulau-Pulau Kecil (KKPD) Kendawangan dan Perairan di Sekitarnya\",\"status_baru\":\"revisi\",\"keterangan\":\"perbaiki rincian kebutuhannya\",\"updated_at\":\"2026-07-07T05:58:39.628705Z\",\"message\":\"Kegiatan FGD Reviu Rencana Pengelolaan dan Zonasi (RPZ) Kawasan Konservasi Pesisir dan Pulau-Pulau Kecil (KKPD) Kendawangan dan Perairan di Sekitarnya telah diubah menjadi revisi\",\"type\":\"rencana_kegiatan\"}', NULL, '2026-07-07 05:58:46', '2026-07-07 05:58:46'),
('34627c7e-0435-4d51-bd83-0ab368ac6808', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 30, '{\"id_kegiatan\":\"019f77b7-a8a1-71ad-aebe-65907176d8e9\",\"judul_kegiatan\":\"Survey ODTW Kendawangan\",\"status_baru\":\"revisi\",\"keterangan\":\"tolong perbaiki rincian kebutuhannya\",\"updated_at\":\"2026-07-22T01:36:39.969256Z\",\"message\":\"Kegiatan Survey ODTW Kendawangan telah diubah menjadi revisi\",\"type\":\"rencana_kegiatan\"}', '2026-07-22 02:30:03', '2026-07-22 01:36:45', '2026-07-22 02:30:03'),
('372b95c8-72e8-4876-afdb-f31324127aa0', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 24, '{\"id_kegiatan\":\"019d2009-9cbd-732a-ae4a-a89567750248\",\"judul_kegiatan\":\"Patroli Dugong\",\"status_baru\":\"ditolak\",\"keterangan\":\"tolong perbaiki lagi\",\"updated_at\":\"2026-03-24T13:31:24.946950Z\",\"message\":\"Kegiatan Patroli Dugong telah diubah menjadi ditolak\"}', '2026-03-24 13:32:16', '2026-03-24 13:31:25', '2026-03-24 13:32:16'),
('3b1988a2-a27f-4e3e-acfb-b1a1033d2374', 'App\\Notifications\\StatusLaporanNotification', 'App\\Models\\User', 15, '{\"id_laporan\":\"cef51cd1-f262-43ee-9521-4ac869d399f4\",\"judul_kegiatan\":\"Kegiatan\",\"status_baru\":\"revisi\",\"keterangan\":\"tolong perbaiki\",\"updated_at\":\"2026-07-11T15:19:35.169160Z\",\"message\":\"Laporan kegiatan Kegiatan telah diubah menjadi revisi\",\"type\":\"laporan_kegiatan\"}', '2026-07-16 06:32:06', '2026-07-11 15:19:40', '2026-07-16 06:32:06'),
('446e538f-9cce-4de1-9f31-e0d68d4209f7', 'App\\Notifications\\LaporanActivityNotification', 'App\\Models\\User', 1, '{\"id_laporan\":\"835ab255-753a-45b5-b5c6-9061584e3e98\",\"id_kegiatan\":\"019f2b0c-4de7-70fd-8571-6dea3cf70741\",\"judul_laporan\":null,\"judul_kegiatan\":\"Patroli Dugong\",\"aksi\":\"dihapus\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-18T01:33:43.312163Z\",\"message\":\"Laporan kegiatan \'\' dihapus oleh septian\",\"type\":\"laporan_kegiatan\"}', '2026-07-18 01:37:04', '2026-07-18 01:33:43', '2026-07-18 01:37:04'),
('5d255f4a-fff6-4f27-a418-bdd5ef4f6c17', 'App\\Notifications\\KegiatanActivityNotification', 'App\\Models\\User', 1, '{\"id_kegiatan\":\"019f7086-2181-70f8-bb25-527b4d8cb17d\",\"judul_kegiatan\":\"Aksi Bersih Pantai Desa Sungai Nanjung\",\"aksi\":\"diajukan\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-17T14:41:06.586554Z\",\"message\":\"Rencana kegiatan \'Aksi Bersih Pantai Desa Sungai Nanjung\' diajukan oleh septian\",\"type\":\"rencana_kegiatan\"}', '2026-07-17 15:12:55', '2026-07-17 14:41:07', '2026-07-17 15:12:55'),
('6d15d207-483e-4518-a433-fce519391d67', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 15, '{\"id_kegiatan\":\"019f2b26-1aa1-73f3-882c-bd34513b1993\",\"judul_kegiatan\":\"Penanaman Mangrove di Pulau Cempedak\",\"status_baru\":\"ditolak\",\"keterangan\":\"tanggal dan waktu kegiatan bentrok dengan kegiatan lain\",\"updated_at\":\"2026-07-04T03:32:58.858837Z\",\"message\":\"Kegiatan Penanaman Mangrove di Pulau Cempedak telah diubah menjadi ditolak\",\"type\":\"rencana_kegiatan\"}', '2026-07-04 03:34:02', '2026-07-04 03:32:58', '2026-07-04 03:34:02'),
('7a4567d2-f152-4f00-96fa-8aaedd9f0616', 'App\\Notifications\\LaporanActivityNotification', 'App\\Models\\User', 1, '{\"id_laporan\":\"cef51cd1-f262-43ee-9521-4ac869d399f4\",\"id_kegiatan\":null,\"judul_laporan\":null,\"judul_kegiatan\":\"Penemuan Dugong Mati di Perairan Kendawangan\",\"aksi\":\"diajukan\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-05T17:17:12.231840Z\",\"message\":\"Laporan kegiatan \'\' diajukan oleh septian\",\"type\":\"laporan_kegiatan\"}', '2026-07-05 17:18:56', '2026-07-05 17:17:16', '2026-07-05 17:18:56'),
('806dc4d2-d746-4d14-8f1e-7530e0989a96', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 15, '{\"id_kegiatan\":\"019f7086-2181-70f8-bb25-527b4d8cb17d\",\"judul_kegiatan\":\"Aksi Bersih Pantai Desa Sungai Nanjung\",\"status_baru\":\"disetujui\",\"keterangan\":null,\"updated_at\":\"2026-07-17T15:01:47.678431Z\",\"message\":\"Kegiatan Aksi Bersih Pantai Desa Sungai Nanjung telah diubah menjadi disetujui\",\"type\":\"rencana_kegiatan\"}', '2026-07-17 16:03:52', '2026-07-17 15:01:47', '2026-07-17 16:03:52'),
('80bd1543-8f05-433e-8b53-ff8bcb228f43', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 24, '{\"id_kegiatan\":\"019d2009-9cbd-732a-ae4a-a89567750248\",\"judul_kegiatan\":\"Patroli Dugong\",\"status_baru\":\"ditolak\",\"keterangan\":\"perbaiki lagi\",\"updated_at\":\"2026-03-24T13:39:43.096204Z\",\"message\":\"Kegiatan Patroli Dugong telah diubah menjadi ditolak\"}', '2026-03-24 13:40:30', '2026-03-24 13:39:43', '2026-03-24 13:40:30'),
('80d123f2-37f5-44b1-992e-657f96df15cf', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 25, '{\"id_kegiatan\":\"019d6aef-abae-720e-86e2-298cba841f1f\",\"judul_kegiatan\":\"Penanaman Mangrove di Pulau Sawi\",\"status_baru\":\"selesai\",\"keterangan\":\"Mantap\",\"updated_at\":\"2026-04-08T02:34:45.378644Z\",\"message\":\"Kegiatan Penanaman Mangrove di Pulau Sawi telah diubah menjadi selesai\"}', NULL, '2026-04-08 02:34:45', '2026-04-08 02:34:45'),
('9a4fa6dc-daf3-40e6-950c-815e8b5ea711', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 24, '{\"id_kegiatan\":\"019d2009-9cbd-732a-ae4a-a89567750248\",\"judul_kegiatan\":\"Patroli Dugong\",\"status_baru\":\"selesai\",\"keterangan\":\"mantap\",\"updated_at\":\"2026-03-24T13:52:14.869199Z\",\"message\":\"Kegiatan Patroli Dugong telah diubah menjadi selesai\"}', '2026-03-24 13:53:00', '2026-03-24 13:52:15', '2026-03-24 13:53:00'),
('9c2e7257-7cc6-416e-b68e-a96bb9d6a5c8', 'App\\Notifications\\LaporanActivityNotification', 'App\\Models\\User', 1, '{\"id_laporan\":\"c75f084b-3dd7-4b04-8dbc-1db08b73e0d1\",\"id_kegiatan\":\"019f286c-e202-73d5-a36b-9dfabc783460\",\"judul_laporan\":null,\"judul_kegiatan\":\"penanaman mangrove\",\"aksi\":\"dihapus\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-18T01:33:01.241580Z\",\"message\":\"Laporan kegiatan \'\' dihapus oleh septian\",\"type\":\"laporan_kegiatan\"}', '2026-07-18 01:37:04', '2026-07-18 01:33:01', '2026-07-18 01:37:04'),
('9da520f2-a19b-4890-a205-9ad9c363afdf', 'App\\Notifications\\KegiatanActivityNotification', 'App\\Models\\User', 1, '{\"id_kegiatan\":\"019f3081-e636-718b-a4a9-21ee4f97b5d2\",\"judul_kegiatan\":\"FGD Reviu Rencana Pengelolaan dan Zonasi (RPZ) Kawasan Konservasi Pesisir dan Pulau-Pulau Kecil (KKPD) Kendawangan dan Perairan di Sekitarnya\",\"aksi\":\"diajukan\",\"user_name\":\"dido\",\"keterangan\":null,\"created_at\":\"2026-07-05T04:20:47.601780Z\",\"message\":\"Rencana kegiatan \'FGD Reviu Rencana Pengelolaan dan Zonasi (RPZ) Kawasan Konservasi Pesisir dan Pulau-Pulau Kecil (KKPD) Kendawangan dan Perairan di Sekitarnya\' diajukan oleh dido\",\"type\":\"rencana_kegiatan\"}', '2026-07-05 08:59:08', '2026-07-05 04:20:48', '2026-07-05 08:59:08'),
('9dff0f5b-8260-47d6-bb81-288d9081ad15', 'App\\Notifications\\LaporanActivityNotification', 'App\\Models\\User', 1, '{\"id_laporan\":\"c9c6da48-3a5a-4aef-adde-bfbbb06702ae\",\"id_kegiatan\":null,\"judul_laporan\":null,\"judul_kegiatan\":\"Respon Cepat Penemuan Dugong Mati di Perairan Kendawangan\",\"aksi\":\"diajukan\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-17T17:04:59.325886Z\",\"message\":\"Laporan kegiatan \'\' diajukan oleh septian\",\"type\":\"laporan_kegiatan\"}', '2026-07-18 01:37:04', '2026-07-17 17:04:59', '2026-07-18 01:37:04'),
('af9cf395-557a-4463-b628-6ccc5d0c0c1f', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 29, '{\"id_kegiatan\":\"019f77e8-ba6a-7289-8eb6-deb1aacf8526\",\"judul_kegiatan\":\"Agenda Inisiasi Garda Emak Pelapis (dukungan sarana transportasi laut)\",\"status_baru\":\"revisi\",\"keterangan\":\"perbaiki rincian kebutuhannya\",\"updated_at\":\"2026-07-22T01:46:32.233791Z\",\"message\":\"Kegiatan Agenda Inisiasi Garda Emak Pelapis (dukungan sarana transportasi laut) telah diubah menjadi revisi\",\"type\":\"rencana_kegiatan\"}', NULL, '2026-07-22 01:46:32', '2026-07-22 01:46:32'),
('b5e3c90f-f5f7-4360-b915-5963b9baa913', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 15, '{\"id_kegiatan\":\"019f2ac4-606b-71ec-8e32-6457174c6d52\",\"judul_kegiatan\":\"survei dan pemetaan pesisir pulau gelam\",\"status_baru\":\"revisi\",\"keterangan\":\"lengkapi rincian kebutuhannya\",\"updated_at\":\"2026-07-17T15:08:13.720596Z\",\"message\":\"Kegiatan survei dan pemetaan pesisir pulau gelam telah diubah menjadi revisi\",\"type\":\"rencana_kegiatan\"}', '2026-07-17 16:03:52', '2026-07-17 15:08:13', '2026-07-17 16:03:52'),
('badf3a6a-eb4d-4bee-b3c6-d705edac25cb', 'App\\Notifications\\LaporanActivityNotification', 'App\\Models\\User', 1, '{\"id_laporan\":\"669378e2-9173-44a8-960a-3d87194fda52\",\"id_kegiatan\":\"019f7086-2181-70f8-bb25-527b4d8cb17d\",\"judul_laporan\":null,\"judul_kegiatan\":\"Aksi Bersih Pantai Desa Sungai Nanjung\",\"aksi\":\"diajukan\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-17T16:35:56.393690Z\",\"message\":\"Laporan kegiatan \'\' diajukan oleh septian\",\"type\":\"laporan_kegiatan\"}', '2026-07-18 01:37:04', '2026-07-17 16:35:56', '2026-07-18 01:37:04'),
('bc00651e-3dd9-4c82-b731-078a64e21ec7', 'App\\Notifications\\StatusLaporanNotification', 'App\\Models\\User', 15, '{\"id_laporan\":\"669378e2-9173-44a8-960a-3d87194fda52\",\"judul_kegiatan\":\"Aksi Bersih Pantai Desa Sungai Nanjung\",\"status_baru\":\"final\",\"keterangan\":\"Laporan telah diterima dan kegiatan selesai.\",\"updated_at\":\"2026-07-18T01:38:17.330468Z\",\"message\":\"Laporan kegiatan Aksi Bersih Pantai Desa Sungai Nanjung telah diubah menjadi final\",\"type\":\"laporan_kegiatan\"}', '2026-07-18 01:46:43', '2026-07-18 01:38:17', '2026-07-18 01:46:43'),
('da3b7b8f-66ca-4487-a3a6-f2f8d8ed7da0', 'App\\Notifications\\LaporanActivityNotification', 'App\\Models\\User', 1, '{\"id_laporan\":\"7fb637ce-7a0a-44f8-9ec9-16023d176fda\",\"id_kegiatan\":null,\"judul_laporan\":null,\"judul_kegiatan\":\"Penanganan dan Pemeriksaan Dugong Mati di Perairan Kendawangan\",\"aksi\":\"diajukan\",\"user_name\":\"Anggun Safitri\",\"keterangan\":null,\"created_at\":\"2026-07-19T01:58:12.867735Z\",\"message\":\"Laporan kegiatan \'\' diajukan oleh Anggun Safitri\",\"type\":\"laporan_kegiatan\"}', '2026-07-19 02:43:34', '2026-07-19 01:58:12', '2026-07-19 02:43:34'),
('daeb4432-7983-42d9-b8fb-2a0e34cea496', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 15, '{\"id_kegiatan\":\"019f2b0c-4de7-70fd-8571-6dea3cf70741\",\"judul_kegiatan\":\"Patroli Dugong\",\"status_baru\":\"disetujui\",\"keterangan\":null,\"updated_at\":\"2026-07-04T04:10:59.161960Z\",\"message\":\"Kegiatan Patroli Dugong telah diubah menjadi disetujui\",\"type\":\"rencana_kegiatan\"}', '2026-07-04 04:11:43', '2026-07-04 04:10:59', '2026-07-04 04:11:43'),
('dda8751a-c7e2-4ac4-adb3-b21252ce75ad', 'App\\Notifications\\StatusLaporanNotification', 'App\\Models\\User', 30, '{\"id_laporan\":\"1c0b2ada-def6-4842-86f8-c06d1d6c55e7\",\"judul_kegiatan\":\"Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026\",\"status_baru\":\"final\",\"keterangan\":\"Laporan telah diterima dan kegiatan selesai.\",\"updated_at\":\"2026-07-19T02:59:14.344675Z\",\"message\":\"Laporan kegiatan Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026 telah diubah menjadi final\",\"type\":\"laporan_kegiatan\"}', '2026-07-19 03:56:05', '2026-07-19 02:59:18', '2026-07-19 03:56:05'),
('e2c3063d-68a6-4808-901a-84b39435cb88', 'App\\Notifications\\KegiatanActivityNotification', 'App\\Models\\User', 1, '{\"id_kegiatan\":\"019f77c2-d956-7320-a60d-1d0b52ad39b8\",\"judul_kegiatan\":\"Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026\",\"aksi\":\"diajukan\",\"user_name\":\"M. Luqinul Mifdlol Assiddiqi\",\"keterangan\":null,\"created_at\":\"2026-07-19T00:24:46.232100Z\",\"message\":\"Rencana kegiatan \'Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026\' diajukan oleh M. Luqinul Mifdlol Assiddiqi\",\"type\":\"rencana_kegiatan\"}', '2026-07-19 00:59:16', '2026-07-19 00:24:46', '2026-07-19 00:59:16'),
('ed3248e2-e740-4e69-88cd-ee98fa14e2ba', 'App\\Notifications\\KegiatanActivityNotification', 'App\\Models\\User', 1, '{\"id_kegiatan\":\"019f2b26-1aa1-73f3-882c-bd34513b1993\",\"judul_kegiatan\":\"Penanaman Mangrove di Pulau Cempedak\",\"aksi\":\"diajukan\",\"user_name\":\"septian\",\"keterangan\":null,\"created_at\":\"2026-07-04T03:22:25.390881Z\",\"message\":\"Rencana kegiatan \'Penanaman Mangrove di Pulau Cempedak\' diajukan oleh septian\",\"type\":\"rencana_kegiatan\"}', '2026-07-04 03:32:00', '2026-07-04 03:22:25', '2026-07-04 03:32:00'),
('f5d35157-5d3f-450f-b1b1-d5083d76e67c', 'App\\Notifications\\StatusKegiatanNotification', 'App\\Models\\User', 30, '{\"id_kegiatan\":\"019f77c2-d956-7320-a60d-1d0b52ad39b8\",\"judul_kegiatan\":\"Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026\",\"status_baru\":\"disetujui\",\"keterangan\":null,\"updated_at\":\"2026-07-19T01:27:06.604077Z\",\"message\":\"Kegiatan Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026 telah diubah menjadi disetujui\",\"type\":\"rencana_kegiatan\"}', '2026-07-19 02:03:52', '2026-07-19 01:27:06', '2026-07-19 02:03:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rencana_kegiatans`
--

CREATE TABLE `rencana_kegiatans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kegiatan` enum('konservasi','usaha masyarakat','edukasi','lainnya') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kegiatan_lainnya` text COLLATE utf8mb4_unicode_ci,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tujuan` text COLLATE utf8mb4_unicode_ci,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `desa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `penanggung_jawab` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelompok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimasi_peserta` int DEFAULT NULL,
  `rincian_kebutuhan` longtext COLLATE utf8mb4_unicode_ci,
  `foto` json DEFAULT NULL,
  `dokumen` json DEFAULT NULL,
  `anggaran_kegiatan` json DEFAULT NULL,
  `status` enum('draft','diajukan','disetujui','revisi','ditolak','selesai') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `keterangan_status` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rencana_kegiatans`
--

INSERT INTO `rencana_kegiatans` (`id`, `user_id`, `uuid`, `nama_kegiatan`, `jenis_kegiatan`, `jenis_kegiatan_lainnya`, `deskripsi`, `tujuan`, `lat`, `lng`, `desa`, `tanggal_mulai`, `tanggal_selesai`, `waktu_mulai`, `waktu_selesai`, `penanggung_jawab`, `kelompok`, `estimasi_peserta`, `rincian_kebutuhan`, `foto`, `dokumen`, `anggaran_kegiatan`, `status`, `keterangan_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(191, 30, '019f77b7-a8a1-71ad-aebe-65907176d8e9', 'Survey ODTW Kendawangan', 'lainnya', 'Survey ODTW Kendawangan', '<p><span style=\"font-size: 16px;\">Kegiatan survei odtw yang akan dilaksanakan pada tanggal 8 juni 2026 hingga tanggal 12 juni 2026 oleh tim yayasan webe yang terdiri atas fian, sandi, andre, lia dan qinul.</span></p>', '<p><span style=\"font-size: 16px;\">Survei ODTW Kendawangan dilakukan untuk mengidentifikasi dan mendokumentasikan berbagai potensi wisata yang dimiliki wilayah Kendawangan. Melalui kegiatan ini, tim mengumpulkan data mengenai daya tarik wisata, aksesibilitas, serta kondisi lingkungan sebagai dasar dalam upaya pengembangan destinasi wisata yang berkelanjutan dan memberikan manfaat bagi masyarakat lokal</span></p>', -2.51797000, 110.20294500, 'Kecamatan Kendawangan', '2026-06-08', '2026-06-12', '06:00:00', '17:00:00', 'M. Luqinul Mifdlol Assiddiqi', 'Tim Yayasan WeBe', 7, '<p><span style=\"font-size: 16px;\">sudah tercover pada anggaran kegiatan</span></p>', NULL, NULL, '{\"path\": \"rencana_kegiatans/anggaran/1784419944_detail_pengajuan.pdf\", \"original_name\": \"detail pengajuan.pdf\"}', 'revisi', 'tolong perbaiki rincian kebutuhannya', '2026-07-19 00:12:33', '2026-07-22 01:36:38', NULL),
(192, 30, '019f77c2-d956-7320-a60d-1d0b52ad39b8', 'Survey dan Pemetaan Mangrove dan Lamun Pulau Gelam 2026', 'lainnya', 'Survey dan Pemetaan', '<p><span style=\"font-size: 16px;\">Kegiatan survey dan pemetaan pesisir pulau gelam yang akan dilaksanakan dari tanggal 5-7 Mei 2026 yang akan dilaksanakan oleh 3 anggota Yayasan webe terdiri dari Fian, Andre dan Luqinul dan didampingi oleh 2 anggota pokmaswas yaitu Hartono dan Dang yanto.</span></p>', '<ul style=\"font-size: 16px;\"><li>mendapatkan data peta keadaan pulau terbaru</li><li>menghasilkan data spasial berkualitas untuk mendapatkan dukungan pelestarian atau pengembangan berkelanjutan</li></ul>', -2.88372300, 110.17400200, 'Desa Kendawangan Kiri, Pulau Gelam', '2026-05-05', '2026-05-07', '07:30:00', '12:00:00', 'M. Luqinul Mifdlol Assiddiqi', 'Tim Yayasan WeBe', 5, '<p><span style=\"font-size: 16px;\">sudah ada pada dokumen anggaran kegiatan</span></p>', NULL, NULL, '{\"path\": \"rencana_kegiatans/anggaran/1784420686_detail_pengajuan_survey_pulau_gelam.pdf\", \"original_name\": \"detail pengajuan survey pulau gelam.pdf\"}', 'selesai', 'Kegiatan telah diselesaikan berdasarkan laporan final.', '2026-07-19 00:24:46', '2026-07-19 02:59:14', NULL),
(193, 29, '019f77e8-ba6a-7289-8eb6-deb1aacf8526', 'Agenda Inisiasi Garda Emak Pelapis (dukungan sarana transportasi laut)', 'usaha masyarakat', NULL, '<p data-start=\"25\" data-end=\"550\" style=\"font-size: 16px;\">Kegiatan Inisiasi Garda Emak Pelapis (Dukungan Sarana Transportasi) di <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Pulau Pelapis</span> merupakan upaya pemberdayaan masyarakat yang berfokus pada penguatan peran perempuan dalam mendukung aktivitas sosial, ekonomi, dan pelayanan masyarakat di wilayah kepulauan.&nbsp;</p><p style=\"font-size: 16px;\"></p><p data-start=\"552\" data-end=\"1037\" style=\"font-size: 16px;\">Kegiatan ini diharapkan menjadi langkah awal dalam membangun sistem dukungan masyarakat yang lebih aktif, mandiri, dan responsif terhadap kebutuhan lokal, khususnya dalam meningkatkan aksesibilitas dan efektivitas pelaksanaan kegiatan sosial kemasyarakatan di Pulau Pelapis. Selain itu, program ini juga menjadi bentuk dukungan terhadap peningkatan kapasitas perempuan sebagai penggerak komunitas yang memiliki peran strategis dalam pembangunan sosial di wilayah pesisir dan kepulauan.</p>', '<li data-section-id=\"15bkcqc\" data-start=\"1061\" data-end=\"1197\" style=\"font-size: 16px;\">Mendukung terbentuknya Garda Emak Pelapis sebagai kelompok perempuan penggerak masyarakat di <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Pulau Pelapis</span>.</li><li data-section-id=\"51ckg1\" data-start=\"1299\" data-end=\"1409\" style=\"font-size: 16px;\">Memperkuat peran perempuan dalam kegiatan sosial, pelayanan masyarakat, dan pemberdayaan komunitas lokal.</li><li data-section-id=\"1uazg1o\" data-start=\"1410\" data-end=\"1502\" style=\"font-size: 16px;\">Mendorong peningkatan koordinasi dan akses pelayanan antarwilayah di kawasan kepulauan.</li><p style=\"font-size: 16px;\"></p><li data-section-id=\"z43d2c\" data-start=\"1503\" data-end=\"1615\" data-is-last-node=\"\" style=\"font-size: 16px;\">Membangun kemandirian dan partisipasi aktif masyarakat dalam mendukung pembangunan sosial berbasis komunitas.</li>', -1.27702500, 109.16770900, 'Pulau Pelapis, Kab. Kayong Utara', '2026-05-12', '2026-05-12', '07:00:00', '23:00:00', 'Hesty Yolanda', 'Leaf Book Club', 23, '<p><span style=\"font-size: 16px;\">Sudah tertera di Anggaran Kegiatan</span></p>', NULL, NULL, '{\"path\": \"rencana_kegiatans/anggaran/1784423168_detail_pengajuan.pdf\", \"original_name\": \"detail pengajuan.pdf\"}', 'revisi', 'perbaiki rincian kebutuhannya', '2026-07-19 01:06:08', '2026-07-22 01:46:32', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2025-12-10 20:49:49', '2026-06-30 12:57:24'),
(2, 'anggota', '2025-12-10 20:49:49', '2026-06-30 12:57:24'),
(3, 'admin', '2026-07-13 15:44:42', '2026-07-13 15:44:42'),
(4, 'anggota', '2026-07-13 15:44:42', '2026-07-13 15:44:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('j9voU2j7rAp436Cx47FED7jKGYzYyCy5YlFcrDl5', 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoicUJGS21vMkRNRmhvem01RnhmMXI3WGhTRExwZVh0eTdSbVRPRHJ1ZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sYXBvcmFuX2tlZ2lhdGFuLzFjMGIyYWRhLWRlZjYtNDg0Mi04NmY4LWMwNmQxZDZjNTVlNyI7czo1OiJyb3V0ZSI7czoyMToibGFwb3Jhbl9rZWdpYXRhbi5zaG93Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MzA7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzg0Njg2Njg1O31zOjU6ImFsZXJ0IjthOjA6e319', 1784687483);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `uuid`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '00ae2118-edd4-11f0-8e33-b4a9fc69b9a3', 1, 'admin', 'admin@admin.com', NULL, '$2y$12$iV4a7CyI90cqmD9hz3n.quRhbyIDa.VM2GSq3rL9knwhYtYTyhHmK', NULL, '2025-11-20 06:15:25', '2025-12-20 23:36:20'),
(27, NULL, 2, 'Anggun Safitri', 'anggun@gmail.com', NULL, '$2y$12$5hJzsg5p00QxAyKP1NeEBeCSxeixHdjTBVl112QrceJY.9BDxySlG', NULL, '2026-07-18 23:28:52', '2026-07-18 23:28:52'),
(29, NULL, 2, 'Hesty Yolanda', 'hesty@gmail.com', NULL, '$2y$12$MJLF1Z3piCsu2JAszPyEaunx.Ex5CD5penDfZr83sv5YQYmMF9Qoe', NULL, '2026-07-18 23:31:03', '2026-07-18 23:40:18'),
(30, NULL, 2, 'M. Luqinul Mifdlol Assiddiqi', 'qinul@gmail.com', NULL, '$2y$12$OfeCDgxUl250w9z7fmEc1.K6qfNL0sQZK8fH3/Gk69Bxh8aMIAKCq', NULL, '2026-07-18 23:32:06', '2026-07-18 23:40:02');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`),
  ADD KEY `subject` (`subject_type`,`subject_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_kegiatans`
--
ALTER TABLE `laporan_kegiatans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laporan_kegiatans_rencana_kegiatan_id_unique` (`rencana_kegiatan_id`),
  ADD KEY `laporan_kegiatans_rencana_kegiatan_id_index` (`rencana_kegiatan_id`),
  ADD KEY `laporan_kegiatans_uuid_index` (`uuid`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `rencana_kegiatans`
--
ALTER TABLE `rencana_kegiatans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rencana_kegiatans_uuid_index` (`uuid`),
  ADD KEY `rencana_kegiatans_user_id_index` (`user_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_uuid_index` (`uuid`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan_kegiatans`
--
ALTER TABLE `laporan_kegiatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT untuk tabel `rencana_kegiatans`
--
ALTER TABLE `rencana_kegiatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `laporan_kegiatans`
--
ALTER TABLE `laporan_kegiatans`
  ADD CONSTRAINT `laporan_kegiatans_rencana_kegiatan_id_foreign` FOREIGN KEY (`rencana_kegiatan_id`) REFERENCES `rencana_kegiatans` (`uuid`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rencana_kegiatans`
--
ALTER TABLE `rencana_kegiatans`
  ADD CONSTRAINT `rencana_kegiatans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
