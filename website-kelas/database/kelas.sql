-- =========================================================
-- Database: website_kelas
-- Import file ini lewat phpMyAdmin (tab Import) atau lewat
-- terminal: mysql -u root -p website_kelas < kelas.sql
-- =========================================================

CREATE DATABASE IF NOT EXISTS website_kelas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE website_kelas;

-- ---------------------------------------------------------
-- Tabel admin (akun untuk login ke panel admin)
-- ---------------------------------------------------------
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) DEFAULT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Password default: admin123 (sudah di-hash dengan bcrypt)
INSERT INTO admin (username, password, nama_lengkap) VALUES
('admin', '$2b$12$PPPwiX291XWHRqBb8zL6ae05D5N.K8KdgtYH9MdY92tWvsCNB21/y', 'Administrator Kelas');

-- ---------------------------------------------------------
-- Tabel info_kelas (data singkat kelas, ditampilkan di beranda)
-- ---------------------------------------------------------
CREATE TABLE info_kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(100) NOT NULL,
    tahun_ajaran VARCHAR(20) DEFAULT NULL,
    wali_kelas VARCHAR(100) DEFAULT NULL,
    jumlah_siswa_manual INT DEFAULT NULL,
    deskripsi TEXT,
    motto VARCHAR(255) DEFAULT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    diperbarui_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO info_kelas (nama_kelas, tahun_ajaran, wali_kelas, deskripsi, motto) VALUES
('XII IPA 1', '2025/2026', 'Ibu Siti Rahmawati, S.Pd', 'Kelas XII IPA 1 adalah kelas yang aktif, kompak, dan berprestasi di bidang akademik maupun non-akademik.', 'Bersama Kita Bisa!');

-- ---------------------------------------------------------
-- Tabel siswa
-- ---------------------------------------------------------
CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(30) DEFAULT NULL,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL DEFAULT 'L',
    tempat_lahir VARCHAR(100) DEFAULT NULL,
    tanggal_lahir DATE DEFAULT NULL,
    alamat TEXT,
    no_hp VARCHAR(20) DEFAULT NULL,
    jabatan VARCHAR(50) DEFAULT NULL COMMENT 'Contoh: Ketua Kelas, Sekretaris, Anggota',
    foto VARCHAR(255) DEFAULT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel pengumuman
-- ---------------------------------------------------------
CREATE TABLE pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    isi TEXT NOT NULL,
    penulis VARCHAR(100) DEFAULT NULL,
    tanggal DATE NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel jadwal pelajaran
-- ---------------------------------------------------------
CREATE TABLE jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    mata_pelajaran VARCHAR(100) NOT NULL,
    guru VARCHAR(100) DEFAULT NULL,
    ruangan VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel materi & tugas
-- ---------------------------------------------------------
CREATE TABLE materi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    kategori ENUM('Materi','Tugas') NOT NULL DEFAULT 'Materi',
    mata_pelajaran VARCHAR(100) DEFAULT NULL,
    deskripsi TEXT,
    file VARCHAR(255) DEFAULT NULL,
    batas_waktu DATE DEFAULT NULL COMMENT 'Deadline, khusus untuk kategori Tugas',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel galeri
-- ---------------------------------------------------------
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) DEFAULT NULL,
    gambar VARCHAR(255) NOT NULL,
    keterangan TEXT,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Contoh data jadwal (boleh dihapus/diubah lewat admin)
-- ---------------------------------------------------------
INSERT INTO jadwal (hari, jam_mulai, jam_selesai, mata_pelajaran, guru, ruangan) VALUES
('Senin', '07:00:00', '08:30:00', 'Matematika', 'Bpk. Ahmad Fauzi, S.Pd', 'R.12'),
('Senin', '08:30:00', '10:00:00', 'Fisika', 'Ibu Dewi Lestari, S.Pd', 'R.12'),
('Selasa', '07:00:00', '08:30:00', 'Bahasa Indonesia', 'Ibu Siti Rahmawati, S.Pd', 'R.12'),
('Rabu', '07:00:00', '08:30:00', 'Kimia', 'Bpk. Rudi Hartono, S.Pd', 'Lab. Kimia');
