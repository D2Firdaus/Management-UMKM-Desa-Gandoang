-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2026 at 03:40 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umkm_desa`
--

-- --------------------------------------------------------

--
-- Table structure for table `bantuan`
--

CREATE TABLE `bantuan` (
  `id_kebutuhan` int NOT NULL,
  `id_umkm` int NOT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prioritas` enum('rendah','sedang','tinggi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','disetujui','ditolak','dihapus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_validasi` date DEFAULT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `id_validator` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bantuan`
--

INSERT INTO `bantuan` (`id_kebutuhan`, `id_umkm`, `jenis`, `prioritas`, `status`, `catatan`, `deskripsi`, `tanggal_validasi`, `tanggal_pengajuan`, `id_validator`) VALUES
(1, 1, 'Modal Usaha', 'tinggi', 'disetujui', 'Disetujui untuk renovasi', 'Butuh modal untuk renovasi warung', '2025-02-01', '2025-01-15', NULL),
(2, 2, 'Peralatan', 'sedang', 'disetujui', 'Disetujui 1 unit etalase', 'Butuh etalase baru untuk display', '2025-02-10', '2025-01-20', NULL),
(3, 3, 'Peralatan', 'tinggi', 'pending', '', 'Butuh kompresor angin baru', NULL, '2025-03-01', NULL),
(4, 4, 'Modal Usaha', 'sedang', 'disetujui', 'oke', 'Modal untuk beli mesin jahit baru', '2026-05-25', '2025-03-05', 12),
(5, 5, 'Pemasaran', 'rendah', 'disetujui', 'Bantuan desain kemasan', 'Butuh bantuan branding kemasan', '2025-03-15', '2025-03-01', NULL),
(6, 6, 'Peralatan', 'sedang', 'ditolak', 'Belum memenuhi syarat', 'Butuh kursi salon tambahan', '2025-03-20', '2025-03-10', NULL),
(7, 7, 'Modal Usaha', 'tinggi', 'pending', '', 'Perluasan kolam lele', NULL, '2025-04-01', NULL),
(8, 8, 'Pelatihan', 'rendah', 'disetujui', 'Jadwal pelatihan bulan depan', 'Pelatihan desain fashion', '2025-04-10', '2025-04-01', NULL),
(9, 9, 'Modal Usaha', 'tinggi', 'disetujui', 'oke', 'Tambah stok material bangunan', '2026-05-25', '2025-04-15', 12),
(10, 10, 'Pemasaran', 'sedang', 'disetujui', 'Dapat slot bazar desa', 'Ikut bazar desa bulan depan', '2025-05-01', '2025-04-20', NULL),
(11, 10, 'pakan', 'sedang', 'pending', NULL, 'a', NULL, '2026-05-20', NULL),
(12, 1, 'pakan', 'rendah', 'disetujui', 'oke', '1', '2026-05-20', '2026-05-20', 12),
(13, 10, 'asqwasdas', 'tinggi', 'pending', NULL, 'asdas', NULL, '2026-05-25', NULL),
(14, 1, 'Trasnport', 'rendah', 'pending', NULL, 'Pengantaran Ke Desa Sebelah', NULL, '2026-05-25', NULL);

--
-- Triggers `bantuan`
--
DELIMITER $$
CREATE TRIGGER `trg_bantuan_update` BEFORE UPDATE ON `bantuan` FOR EACH ROW INSERT INTO bantuan_history (
    id_kebutuhan,
    id_umkm,
    jenis,
    prioritas,
    status,
    catatan,
    deskripsi,
    tanggal_validasi,
    tanggal_pengajuan,
    id_validator,
    action_type
)
VALUES (
    OLD.id_kebutuhan,
    OLD.id_umkm,
    OLD.jenis,
    OLD.prioritas,
    OLD.status,
    OLD.catatan,
    OLD.deskripsi,
    OLD.tanggal_validasi,
    OLD.tanggal_pengajuan,
    OLD.id_validator,
    CASE
        WHEN NEW.status = 'dihapus' THEN 'SOFT_DELETE'
        ELSE 'UPDATE'
    END
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bantuan_history`
--

CREATE TABLE `bantuan_history` (
  `id_history` int NOT NULL,
  `id_kebutuhan` int DEFAULT NULL,
  `id_umkm` int DEFAULT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prioritas` enum('rendah','sedang','tinggi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','dihapus') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_validasi` date DEFAULT NULL,
  `tanggal_pengajuan` date DEFAULT NULL,
  `id_validator` int DEFAULT NULL,
  `action_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bantuan_history`
--

INSERT INTO `bantuan_history` (`id_history`, `id_kebutuhan`, `id_umkm`, `jenis`, `prioritas`, `status`, `catatan`, `deskripsi`, `tanggal_validasi`, `tanggal_pengajuan`, `id_validator`, `action_type`, `action_time`) VALUES
(1, 14, 1, 'asqwasdas', 'rendah', 'pending', NULL, 'asd', NULL, '2026-05-25', NULL, 'UPDATE', '2026-06-04 15:11:48');

-- --------------------------------------------------------

--
-- Table structure for table `journey`
--

CREATE TABLE `journey` (
  `id_journey` int NOT NULL,
  `id_umkm` int NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deksripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journey`
--

INSERT INTO `journey` (`id_journey`, `id_umkm`, `foto`, `deksripsi`, `tanggal`) VALUES
(1, 1, 'journey1.jpg', 'Pembukaan warung pertama kali', '2024-01-15'),
(2, 1, 'journey2.jpg', 'Renovasi warung dan tambah menu', '2024-06-20'),
(3, 2, 'journey3.jpg', 'Grand opening toko kelontong', '2024-02-10'),
(4, 3, 'journey4.jpg', 'Mulai usaha bengkel dari garasi', '2023-08-05'),
(5, 4, 'journey5.jpg', 'Dapat orderan pertama 100 kaos', '2024-03-12'),
(6, 5, 'journey6.jpg', 'Produksi keripik pertama 50 bungkus', '2024-04-01'),
(7, 5, 'journey7.jpg', 'Masuk marketplace online', '2024-09-15'),
(8, 7, 'journey8.jpg', 'Panen lele pertama 500kg', '2024-05-20'),
(9, 8, 'journey9.jpg', 'Buka kelas jahit untuk ibu-ibu', '2024-07-10'),
(10, 10, 'journey10.jpg', 'Ikut bazar desa pertama kali', '2024-11-25');

--
-- Triggers `journey`
--
DELIMITER $$
CREATE TRIGGER `trg_journey_update` BEFORE UPDATE ON `journey` FOR EACH ROW INSERT INTO journey_history (
    id_journey,
    id_umkm,
    foto,
    deksripsi,
    tanggal,
    action_type
)
VALUES (
    OLD.id_journey,
    OLD.id_umkm,
    OLD.foto,
    OLD.deksripsi,
    OLD.tanggal,
    'UPDATE'
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `journey_history`
--

CREATE TABLE `journey_history` (
  `id_history` int NOT NULL,
  `id_journey` int DEFAULT NULL,
  `id_umkm` int DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dekripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal` date DEFAULT NULL,
  `action_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `id_umkm` int NOT NULL,
  `nama_produk` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_umkm`, `nama_produk`, `kategori`, `harga`, `deskripsi`, `foto`) VALUES
(1, 1, 'Nasi Goreng Spesial', 'Makanan', 15000, 'Nasi goreng dengan telur dan ayam', 'nasgor.jpg'),
(2, 1, 'Mie Ayam Bakso', 'Makanan', 12000, 'Mie ayam dengan bakso sapi', 'mieayam.jpg'),
(3, 2, 'Beras Premium 5kg', 'Sembako', 65000, 'Beras kualitas premium', 'beras.jpg'),
(4, 2, 'Minyak Goreng 2L', 'Sembako', 32000, 'Minyak goreng kemasan 2 liter', 'minyak.jpg'),
(5, 3, 'Service Ringan Motor', 'Jasa', 50000, 'Ganti oli dan tune up', 'service.jpg'),
(6, 4, 'Kaos Polos', 'Pakaian', 45000, 'Kaos cotton combed 30s', 'kaos.jpg'),
(7, 5, 'Keripik Singkong Original', 'Makanan', 10000, 'Keripik singkong renyah 200gr', 'keripik.jpg'),
(8, 5, 'Keripik Singkong Pedas', 'Makanan', 12000, 'Keripik singkong pedas 200gr', 'keripik_pedas.jpg'),
(9, 7, 'Lele Segar 1kg', 'Perikanan', 25000, 'Lele segar siap masak', 'lele.jpg'),
(10, 10, 'Kue Lapis Legit', 'Makanan', 85000, 'Kue lapis legit homemade', 'lapis.jpg'),
(11, 5, 'Singkong Rebus', 'Kuliner', 1000, 'Lezat Dan Bergizi', 'prod_6a2148bd225cd7.75935071.jpg');

--
-- Triggers `produk`
--
DELIMITER $$
CREATE TRIGGER `trg_produk_update` BEFORE UPDATE ON `produk` FOR EACH ROW INSERT INTO produk_history (
    id_produk,
    id_umkm,
    nama_produk,
    kategori,
    harga,
    deskripsi,
    foto,
    action_type
)
VALUES (
    OLD.id_produk,
    OLD.id_umkm,
    OLD.nama_produk,
    OLD.kategori,
    OLD.harga,
    OLD.deskripsi,
    OLD.foto,
    'UPDATE'
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `produk_history`
--

CREATE TABLE `produk_history` (
  `id_history` int NOT NULL,
  `id_produk` int DEFAULT NULL,
  `id_umkm` int DEFAULT NULL,
  `nama_produk` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id_profile` int NOT NULL,
  `id_user` int NOT NULL,
  `nik` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_kk` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_kk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id_profile`, `id_user`, `nik`, `no_hp`, `no_kk`, `foto_ktp`, `foto_kk`) VALUES
(1, 1, '3201010101010001', '081234567801', '3201010101010001', NULL, 'kk_halim.jpg'),
(2, 2, '3201010101010002', '081234567802', '3201010101010002', NULL, 'kk_bajang.jpg'),
(3, 3, '3201010101010003', '081234567803', '3201010101010003', NULL, 'kk_dede.jpg'),
(4, 4, '3201010101010004', '081234567804', '3201010101010004', NULL, 'kk_aldi.jpg'),
(5, 5, '3201010101010005', '081234567805', '3201010101010005', NULL, 'kk_arif.jpg'),
(6, 6, '3201010101010006', '081234567806', '3201010101010006', NULL, 'kk_siti.jpg'),
(7, 7, '3201010101010007', '081234567807', '3201010101010007', NULL, 'kk_ahmad.jpg'),
(8, 8, '3201010101010008', '081234567808', '3201010101010008', NULL, 'kk_rina.jpg'),
(9, 9, '3201010101010009', '081234567809', '3201010101010009', NULL, 'kk_budi.jpg'),
(10, 10, '3201010101010010', '081234567810', '3201010101010010', NULL, 'kk_dewi.jpg'),
(11, 11, 'qCokj7RaDuChzZ6WqlasTWhRE0X4t8bBa4E9+Ayw9yZsz0FSCj+GxBCu7sg/8dhU', 'M1S/iwjSVJqJRKfFngRUMAHHlR95UYTHUlZeNQJScGs=', '7ucDqbdniR1GAkzwtoDnxPQmM50SDLa3pTmM+yOxSkLc/FEfyd9YQRO/yvXxIp2S', 'd7de41842c27a1f1f12bd65831d38c92.jpg', '2612024e6e147017c926a2054d662949.jpeg');

--
-- Triggers `profile`
--
DELIMITER $$
CREATE TRIGGER `trg_profile_update` BEFORE UPDATE ON `profile` FOR EACH ROW INSERT INTO profile_history (
    id_profile,
    id_user,
    nik,
    no_hp,
    no_kk,
    foto_ktp,
    foto_kk,
    action_type
)
VALUES (
    OLD.id_profile,
    OLD.id_user,
    OLD.nik,
    OLD.no_hp,
    OLD.no_kk,
    OLD.foto_ktp,
    OLD.foto_kk,
    'UPDATE'
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `profile_history`
--

CREATE TABLE `profile_history` (
  `id_history` int NOT NULL,
  `id_profile` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `nik` text COLLATE utf8mb4_unicode_ci,
  `no_hp` text COLLATE utf8mb4_unicode_ci,
  `no_kk` text COLLATE utf8mb4_unicode_ci,
  `foto_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_kk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id_umkm` int NOT NULL,
  `nama_umkm` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` int NOT NULL,
  `id_validator` int NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id_umkm`, `nama_umkm`, `id_user`, `id_validator`, `alamat`, `status`) VALUES
(1, 'Warung Makan Barokah', 1, 1, 'Jl. Gandoang No.1, RT 01/RW 01', 'aktif'),
(2, 'Toko Kelontong Sejahtera', 2, 1, 'Jl. Gandoang No.5, RT 02/RW 01', 'aktif'),
(3, 'Bengkel Motor Jaya', 3, 1, 'Jl. Raya Cileungsi No.10', 'aktif'),
(4, 'Konveksi Mandiri', 4, 1, 'Jl. Gandoang No.15, RT 03/RW 02', 'aktif'),
(5, 'Keripik Singkong Ibu Ani', 5, 1, 'Jl. Jonggol No.8, RT 01/RW 03', 'aktif'),
(6, 'Salon Cantik Alami', 6, 12, 'Jl. Gandoang No.20, RT 04/RW 01', 'pending'),
(7, 'Ternak Lele Makmur', 7, 1, 'Jl. Gandoang No.25, RT 05/RW 02', 'aktif'),
(8, 'Jahit Rina Collection', 8, 1, 'Jl. Raya Cileungsi No.30', 'aktif'),
(9, 'Toko Bangunan Maju', 9, 1, 'Jl. Gandoang No.35, RT 02/RW 03', 'pending'),
(10, 'Kue Basah Bu Dewi', 10, 1, 'Jl. Jonggol No.12, RT 03/RW 01', 'aktif');

--
-- Triggers `umkm`
--
DELIMITER $$
CREATE TRIGGER `trg_umkm_update` BEFORE UPDATE ON `umkm` FOR EACH ROW INSERT INTO umkm_history (
    id_umkm,
    nama_umkm,
    id_user,
    id_validator,
    alamat,
    status,
    action_type
)
VALUES (
    OLD.id_umkm,
    OLD.nama_umkm,
    OLD.id_user,
    OLD.id_validator,
    OLD.alamat,
    OLD.status,
    CASE
        WHEN NEW.status = 'nonaktif' THEN 'SOFT_DELETE'
        ELSE 'UPDATE'
    END
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `umkm_history`
--

CREATE TABLE `umkm_history` (
  `id_history` int NOT NULL,
  `id_umkm` int DEFAULT NULL,
  `nama_umkm` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `id_validator` int DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','aktif','nonaktif') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `role` enum('umkm','admin') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `password`, `email`, `status`, `role`) VALUES
(1, 'Halim Pratama', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'halim@gmail.com', 'aktif', 'umkm'),
(2, 'Bajang Saputra', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bajang@gmail.com', 'aktif', 'umkm'),
(3, 'Dede Kurniawan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dede@gmail.com', 'aktif', 'umkm'),
(4, 'Aldi Firmansyah', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'aldi@gmail.com', 'aktif', 'umkm'),
(5, 'Arif Hidayat', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'arif@gmail.com', 'aktif', 'umkm'),
(6, 'Siti Nurhaliza', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siti@gmail.com', 'aktif', 'umkm'),
(7, 'Ahmad Fauzi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ahmad@gmail.com', 'aktif', 'umkm'),
(8, 'Rina Marlina', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rina@gmail.com', 'aktif', 'umkm'),
(9, 'Budi Santoso', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'budi@gmail.com', 'aktif', 'umkm'),
(10, 'Dewi Lestari', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dewi@gmail.com', 'aktif', 'umkm'),
(11, 'Budi', '$2y$10$t7SEnyb/90bnyoPaFyVyI.L70Yn2xr4LggQInO7/pZZ1LPmagSzzm', '2410631170034@student.unsika.ac.id', 'aktif', 'umkm'),
(12, 'Maman Racing', '$2y$10$t7SEnyb/90bnyoPaFyVyI.L70Yn2xr4LggQInO7/pZZ1LPmagSzzm', 'contoh0031@gmail.com', 'aktif', 'admin'),
(13, 'Martinah', '$2y$10$htSz1qWHfkrai37/YE/oUOwI9t8a.21E6XkZauxHjZweXamn/6af.', 'contoh0030@gmail.com', 'aktif', 'umkm'),
(14, 'Bahlil Ganteng', '$2y$10$HwV11g1jYDg1PGTjJeQY6eQpw3I3oamysrRN4ZF7DEaoQI0pbEKmu', 'contoh0004@gmail.com', 'aktif', 'umkm'),
(15, 'Bahlil Jamsut', '$2y$10$vEzyPY6.U5UUxGhAkDzso.D6ZJ6zj3ao3KZTlMjX2BBcHA1AKI2Ky', 'contoh0006@gmail.com', 'aktif', 'umkm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bantuan`
--
ALTER TABLE `bantuan`
  ADD PRIMARY KEY (`id_kebutuhan`),
  ADD KEY `id_umkm` (`id_umkm`),
  ADD KEY `fk_bantuan_validator` (`id_validator`);

--
-- Indexes for table `bantuan_history`
--
ALTER TABLE `bantuan_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indexes for table `journey`
--
ALTER TABLE `journey`
  ADD PRIMARY KEY (`id_journey`),
  ADD KEY `id_umkm` (`id_umkm`);

--
-- Indexes for table `journey_history`
--
ALTER TABLE `journey_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_umkm` (`id_umkm`);

--
-- Indexes for table `produk_history`
--
ALTER TABLE `produk_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `profile_history`
--
ALTER TABLE `profile_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id_umkm`),
  ADD KEY `id_user` (`id_user`,`id_validator`);

--
-- Indexes for table `umkm_history`
--
ALTER TABLE `umkm_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bantuan`
--
ALTER TABLE `bantuan`
  MODIFY `id_kebutuhan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bantuan_history`
--
ALTER TABLE `bantuan_history`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `journey`
--
ALTER TABLE `journey`
  MODIFY `id_journey` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `journey_history`
--
ALTER TABLE `journey_history`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `produk_history`
--
ALTER TABLE `produk_history`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `profile_history`
--
ALTER TABLE `profile_history`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id_umkm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `umkm_history`
--
ALTER TABLE `umkm_history`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bantuan`
--
ALTER TABLE `bantuan`
  ADD CONSTRAINT `bantuan_ibfk_2` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bantuan_validator` FOREIGN KEY (`id_validator`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `journey`
--
ALTER TABLE `journey`
  ADD CONSTRAINT `journey_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
