-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2026 at 05:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `website_kelas`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `dibuat_pada`) VALUES
(1, 'naufal', '$2y$10$u9Ku/DLlN9aWP7KJFrUeZ.rSWMMTpIRAn2TApzjBu/Fzo49.tIoh.', 'Naufal Nur Fauzan', '2026-08-29 12:56:50');

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `judul` varchar(150) DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `judul`, `gambar`, `keterangan`, `dibuat_pada`) VALUES
(2, 'flag', 'galeri_1788027016_603.jpg', 'tessss', '2026-08-29 18:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `info_kelas`
--

CREATE TABLE `info_kelas` (
  `id` int(11) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `tahun_ajaran` varchar(20) DEFAULT NULL,
  `wali_kelas` varchar(100) DEFAULT NULL,
  `jumlah_siswa_manual` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `motto` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info_kelas`
--

INSERT INTO `info_kelas` (`id`, `nama_kelas`, `tahun_ajaran`, `wali_kelas`, `jumlah_siswa_manual`, `deskripsi`, `motto`, `logo`, `diperbarui_pada`) VALUES
(1, 'XI SIJA', '2025/2026', 'Ibu Ninik Yusrotul Ula S.Pd', NULL, 'Kelas XI SIJA HEBAT', 'Belajar, Berbagi, Berkembang', 'logo_1788004420.jpg', '2026-08-31 04:27:27');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `mata_pelajaran` varchar(100) NOT NULL,
  `guru` varchar(100) DEFAULT NULL,
  `ruangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `hari`, `jam_mulai`, `jam_selesai`, `mata_pelajaran`, `guru`, `ruangan`) VALUES
(5, 'Senin', '07:40:00', '11:45:00', 'KK', 'Nurhadi, S.Kom', 'Lab.SIJA'),
(6, 'Senin', '12:30:00', '14:50:00', 'KK', 'Hanafi, S.Kom', 'Lab.SIJA'),
(7, 'Selasa', '07:00:00', '10:00:00', 'KK', 'Fajar Setiawan, S.Pd', 'Lab.Kom'),
(8, 'Selasa', '10:20:00', '12:20:00', 'PAI', 'Ninik Yusrotul Ula, S.Pd', 'Kelas'),
(9, 'Selasa', '13:05:00', '14:50:00', 'Bhs. Indonesia', 'Mudiono, S.Pd', 'Kelas'),
(10, 'Rabu', '07:00:00', '08:30:00', 'PJOK', 'Rudi Kurniawan, S.Pd', 'Lapangan'),
(11, 'Rabu', '08:30:00', '10:00:00', 'Bhs. Inggris', 'Randika Putri S, S.Pd', 'Kelas'),
(12, 'Rabu', '10:20:00', '11:40:00', 'PKN', 'Admaji Dwita Hariadi, S.Pd', 'Kelas'),
(13, 'Rabu', '11:40:00', '14:50:00', 'KK', 'Fajar Setiawan, S.Pd', 'Lab.Kom'),
(14, 'Kamis', '07:00:00', '08:30:00', 'Bhs. Inggris', 'Randika Putri S, S.Pd', 'Kelas'),
(15, 'Kamis', '08:30:00', '10:00:00', 'BK', 'Imam Afdul Munip, S.Pd', 'Kelas'),
(16, 'Kamis', '10:20:00', '12:20:00', 'MTK', 'Iva Setyawati, S.Pd', 'Kelas'),
(17, 'Kamis', '13:05:00', '13:40:00', 'MULOK', 'Anggun Primawati, S.Pd', 'Kelas'),
(18, 'Kamis', '13:40:00', '14:50:00', 'SEJARAH', 'Perwita Putri Dyaniska, S.Pd', 'Kelas'),
(19, 'Jumat', '07:30:00', '09:30:00', 'MAPIL', 'Hanafi, S.Kom', 'Lab.SIJA'),
(20, 'Jumat', '09:50:00', '13:30:00', 'KIK', 'Nurhadi, S.Kom', 'Lab.SIJA'),
(21, 'Senin', '07:00:00', '07:40:00', 'Upacara', NULL, NULL),
(22, 'Senin', '07:40:00', '08:20:00', 'KK', 'Nurhadi, S.Kom.', NULL),
(23, 'Senin', '08:20:00', '09:00:00', 'KK', 'Nurhadi, S.Kom.', NULL),
(24, 'Senin', '09:00:00', '09:40:00', 'KK', 'Nurhadi, S.Kom.', NULL),
(25, 'Senin', '09:40:00', '10:00:00', 'Istirahat', NULL, NULL),
(26, 'Senin', '10:00:00', '10:35:00', 'KK', 'Nurhadi, S.Kom.', NULL),
(27, 'Senin', '10:35:00', '11:10:00', 'Pendidikan Agama Islam (PAI)', 'Ninik Yusrotul Ula, S.Pd.', NULL),
(28, 'Senin', '11:10:00', '11:45:00', 'Pendidikan Agama Islam (PAI)', 'Ninik Yusrotul Ula, S.Pd.', NULL),
(29, 'Senin', '11:45:00', '12:30:00', 'Istirahat', NULL, NULL),
(30, 'Senin', '12:30:00', '13:05:00', 'KK', 'Hanafi, S.Kom.', NULL),
(31, 'Senin', '13:05:00', '13:40:00', 'KK', 'Hanafi, S.Kom.', NULL),
(32, 'Senin', '13:40:00', '14:15:00', 'KK', 'Hanafi, S.Kom.', NULL),
(33, 'Senin', '14:15:00', '14:50:00', 'KK', 'Hanafi, S.Kom.', NULL),
(34, 'Selasa', '07:00:00', '07:45:00', 'Sejarah', 'Perwita Putri Dyaniska, S.Pd.', NULL),
(35, 'Selasa', '07:45:00', '08:30:00', 'Sejarah', 'Perwita Putri Dyaniska, S.Pd.', NULL),
(36, 'Selasa', '08:30:00', '09:15:00', 'PJOK', 'Rudi Kurniawan, S.Pd.', NULL),
(37, 'Selasa', '09:15:00', '10:00:00', 'PJOK', 'Rudi Kurniawan, S.Pd.', NULL),
(38, 'Selasa', '10:00:00', '10:20:00', 'Istirahat', NULL, NULL),
(39, 'Selasa', '10:20:00', '11:00:00', 'Bahasa Inggris', 'Linda Jayanti, S.Pd.', NULL),
(40, 'Selasa', '11:00:00', '11:40:00', 'Bahasa Inggris', 'Linda Jayanti, S.Pd.', NULL),
(41, 'Selasa', '11:40:00', '12:20:00', 'KK', 'Burhanudin Ahmad, S.Pd.', NULL),
(42, 'Selasa', '12:20:00', '13:05:00', 'Istirahat', NULL, NULL),
(43, 'Selasa', '13:05:00', '13:40:00', 'KK', 'Burhanudin Ahmad, S.Pd.', NULL),
(44, 'Selasa', '13:40:00', '14:15:00', 'KK', 'Burhanudin Ahmad, S.Pd.', NULL),
(45, 'Selasa', '14:15:00', '14:50:00', 'KK', 'Burhanudin Ahmad, S.Pd.', NULL),
(46, 'Rabu', '07:00:00', '07:45:00', 'PKN', 'Atmaji Dwita Hariadi, S.Pd.', NULL),
(47, 'Rabu', '07:45:00', '08:30:00', 'PKN', 'Atmaji Dwita Hariadi, S.Pd.', NULL),
(48, 'Rabu', '08:30:00', '09:15:00', 'Bahasa Inggris', 'Linda Jayanti, S.Pd.', NULL),
(49, 'Rabu', '09:15:00', '10:00:00', 'Bahasa Inggris', 'Linda Jayanti, S.Pd.', NULL),
(50, 'Rabu', '10:00:00', '10:20:00', 'Istirahat', NULL, NULL),
(51, 'Rabu', '10:20:00', '11:00:00', 'KK', 'Dian Lianawati, S.Si.', NULL),
(52, 'Rabu', '11:00:00', '11:40:00', 'KK', 'Dian Lianawati, S.Si.', NULL),
(53, 'Rabu', '11:40:00', '12:20:00', 'KK', 'Dian Lianawati, S.Si.', NULL),
(54, 'Rabu', '12:20:00', '13:05:00', 'Istirahat', NULL, NULL),
(55, 'Rabu', '13:05:00', '13:40:00', 'KK', 'Dian Lianawati, S.Si.', NULL),
(56, 'Rabu', '13:40:00', '14:15:00', 'KK', 'Dian Lianawati, S.Si.', NULL),
(57, 'Rabu', '14:15:00', '14:50:00', 'KK', 'Dian Lianawati, S.Si.', NULL),
(58, 'Kamis', '07:00:00', '07:45:00', 'Muatan Lokal (MULOK)', 'Anggun Primawati, S.Pd.', NULL),
(59, 'Kamis', '07:45:00', '08:30:00', 'KIK', 'Jamiatun, S.E.', NULL),
(60, 'Kamis', '08:30:00', '09:15:00', 'KIK', 'Jamiatun, S.E.', NULL),
(61, 'Kamis', '09:15:00', '10:00:00', 'KIK', 'Jamiatun, S.E.', NULL),
(62, 'Kamis', '10:00:00', '10:20:00', 'Istirahat', NULL, NULL),
(63, 'Kamis', '10:20:00', '11:00:00', 'Matematika', 'Iva Setyawati, S.Pd.', NULL),
(64, 'Kamis', '11:00:00', '11:40:00', 'Matematika', 'Iva Setyawati, S.Pd.', NULL),
(65, 'Kamis', '11:40:00', '12:20:00', 'Matematika', 'Iva Setyawati, S.Pd.', NULL),
(66, 'Kamis', '12:20:00', '13:05:00', 'Istirahat', NULL, NULL),
(67, 'Kamis', '13:05:00', '13:40:00', 'Muatan Lokal (MULOK)', 'Anggun Primawati, S.Pd.', NULL),
(68, 'Kamis', '13:40:00', '14:15:00', 'Sejarah', 'Perwita Putri Dyaniska, S.Pd.', NULL),
(69, 'Kamis', '14:15:00', '14:50:00', 'Sejarah', 'Perwita Putri Dyaniska, S.Pd.', NULL),
(70, 'Jumat', '07:00:00', '07:30:00', 'Kegiatan Jumat', NULL, NULL),
(71, 'Jumat', '07:30:00', '08:00:00', 'Mapel Pilihan (MAPIL)', 'Hanafi, S.Kom.', NULL),
(72, 'Jumat', '08:00:00', '08:30:00', 'Mapel Pilihan (MAPIL)', 'Hanafi, S.Kom.', NULL),
(73, 'Jumat', '08:30:00', '09:00:00', 'Mapel Pilihan (MAPIL)', 'Hanafi, S.Kom.', NULL),
(74, 'Jumat', '09:00:00', '09:30:00', 'Mapel Pilihan (MAPIL)', 'Hanafi, S.Kom.', NULL),
(75, 'Jumat', '09:30:00', '09:50:00', 'Istirahat', NULL, NULL),
(76, 'Jumat', '09:50:00', '10:20:00', 'KIK', 'Nurhadi, S.Kom.', NULL),
(77, 'Jumat', '10:20:00', '10:50:00', 'KIK', 'Nurhadi, S.Kom.', NULL),
(78, 'Jumat', '10:50:00', '11:20:00', 'KIK', 'Nurhadi, S.Kom.', NULL),
(79, 'Jumat', '11:20:00', '12:30:00', 'Istirahat', NULL, NULL),
(80, 'Jumat', '12:30:00', '13:00:00', 'KIK', 'Nurhadi, S.Kom.', NULL),
(81, 'Jumat', '13:00:00', '13:30:00', 'KIK', 'Nurhadi, S.Kom.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `kategori` enum('Materi','Tugas') NOT NULL DEFAULT 'Materi',
  `mata_pelajaran` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `batas_waktu` date DEFAULT NULL COMMENT 'Deadline, khusus untuk kategori Tugas',
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id`, `judul`, `kategori`, `mata_pelajaran`, `deskripsi`, `file`, `batas_waktu`, `dibuat_pada`) VALUES
(1, 'install', 'Tugas', 'KK', 'harus selesai', 'materi_1788027142_380.jpg', '2026-08-31', '2026-08-29 18:11:39');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `penulis`, `tanggal`, `dibuat_pada`) VALUES
(2, 'tes', 'upacara', '', '2026-08-31', '2026-08-29 18:13:16');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL DEFAULT 'L',
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL COMMENT 'Contoh: Ketua Kelas, Sekretaris, Anggota',
  `foto` varchar(255) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `jabatan`, `foto`, `dibuat_pada`) VALUES
(1, '', 'Adimas Widiansyah Putra', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (1).jpg', '2026-08-29 18:21:55'),
(2, '', 'Alfina Syahrini', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (2).jpg', '2026-08-29 18:22:43'),
(3, '', 'Amelia Ayu Ardina', 'P', '', NULL, '', '', 'Bendahara', '25XS (3).jpg', '2026-08-29 18:23:01'),
(4, '', 'Arianto Setyo Budi', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (4).jpg', '2026-08-29 18:23:15'),
(5, '', 'Arya Kayana Putra', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (5).jpg', '2026-08-29 18:23:28'),
(6, '', 'Aulia Zahrahiya', 'P', '', NULL, '', '', 'Sekertaris', '25XS (6).jpg', '2026-08-29 18:23:51'),
(7, '', 'Cantika Nur', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (7).jpg', '2026-08-29 18:24:19'),
(8, '', 'Dias Maulana Oktavian', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (8).jpg', '2026-08-29 18:24:44'),
(9, '', 'Haris Suyanto', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (9).jpg', '2026-08-29 18:25:02'),
(10, '', 'Julyanda Ristin', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (10).jpg', '2026-08-29 18:25:26'),
(11, '', 'Lisnaini Cahyono Putri', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (11).jpg', '2026-08-29 18:28:05'),
(12, '', 'Listiya Nur Apriliawati', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (12).jpg', '2026-08-29 18:28:11'),
(13, '', 'Livya Shavita Vinza', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (13).jpg', '2026-08-29 18:28:49'),
(14, '', 'Lutfiyah Alvarani', 'P', '', NULL, '', '', 'Bendahara', '25XS (14).jpg', '2026-08-29 18:30:59'),
(15, '', 'Maulana Denis Diandra', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (15).jpg', '2026-08-29 18:31:31'),
(16, '', 'Mochamad Fajar Shodiq', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (16).jpg', '2026-08-29 18:32:58'),
(17, '', 'Muhammad Habiburrohman', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (17).jpg', '2026-08-29 18:33:41'),
(18, '', 'Muhammad Fathan Nugroho', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (18).jpg', '2026-08-29 18:34:03'),
(19, '', 'Muhammad Ilham Syaifullah', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (19).jpg', '2026-08-29 18:35:07'),
(20, '', 'Muhammad Kautsar Alfarizky', 'L', '', NULL, '', '', 'Ketua Kelas', '25XS (20).jpg', '2026-08-29 18:35:41'),
(21, '', 'Muhammad Kevin Permana', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (21).jpg', '2026-08-29 18:36:05'),
(22, '', 'Muhammad Ricky Fajar Adiputra', 'L', '', NULL, '', '', 'Wakil Ketua Kelas', '25XS (22).jpg', '2026-08-29 18:36:49'),
(23, '', 'Muzakka Ramadhan Iramadzatilimad', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (23).jpg', '2026-08-29 18:37:48'),
(24, '', 'Nabela Wahyuningtyas', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (24).jpg', '2026-08-29 18:38:33'),
(25, '', 'Nadira Shaqila Maheswari', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (25).jpg', '2026-08-29 18:38:52'),
(26, '', 'Naufal Nur Fauzan', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (26).jpg', '2026-08-29 18:39:38'),
(27, '', 'Rafa Baiata Alfaridzi', 'L', '', NULL, '', '', 'Anggota Kelas', '25XS (27).jpg', '2026-08-29 18:39:55'),
(28, '', 'Roziqul Alfi Thoriq', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (28).jpg', '2026-08-29 18:40:27'),
(29, '', 'Safa Eka Nur Azzahra', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (29).jpg', '2026-08-29 18:42:09'),
(30, '', 'Safa Nur Azifah', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (30).jpg', '2026-08-29 18:42:21'),
(31, '', 'Salfa Eka Oktavianah', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (31).jpg', '2026-08-29 18:42:26'),
(32, '', 'Silva Dwi Wulandari', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (32).jpg', '2026-08-29 18:42:30'),
(33, '', 'Silvia Novariska Putri', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (33).jpg', '2026-08-29 18:42:36'),
(34, '1234567890', 'Sultan Angga Pratama', 'L', 'Lombok', NULL, '', '', 'Anggota Kelas', '25XS (34).jpg', '2026-08-29 18:42:53'),
(35, '', 'Syava Al Thafunnisa', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (35).jpg', '2026-08-29 18:42:58'),
(36, '', 'Tyas Dwi Saputri', 'P', '', NULL, '', '', 'Anggota Kelas', '25XS (36).jpg', '2026-08-29 18:43:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info_kelas`
--
ALTER TABLE `info_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `info_kelas`
--
ALTER TABLE `info_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1042;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
