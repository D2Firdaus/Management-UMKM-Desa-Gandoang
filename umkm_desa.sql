-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Jun 2026 pada 11.02
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

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
-- Struktur dari tabel `bantuan`
--

CREATE TABLE `bantuan` (
  `id_kebutuhan` int(11) NOT NULL,
  `id_umkm` varchar(36) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `prioritas` enum('rendah','sedang','tinggi') NOT NULL,
  `status` enum('pending','disetujui','ditolak','dihapus') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_validasi` date DEFAULT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `id_validator` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bantuan`
--

INSERT INTO `bantuan` (`id_kebutuhan`, `id_umkm`, `jenis`, `prioritas`, `status`, `catatan`, `deskripsi`, `tanggal_validasi`, `tanggal_pengajuan`, `id_validator`) VALUES
(1, '84d1cc3f-60bc-11f1-bf39-00e01e54316e', 'Modal Usaha', 'tinggi', 'disetujui', 'Disetujui untuk renovasi', 'Butuh modal untuk renovasi warung', '2025-02-01', '2025-01-15', NULL),
(2, '84d1da34-60bc-11f1-bf39-00e01e54316e', 'Peralatan', 'sedang', 'disetujui', 'Disetujui 1 unit etalase', 'Butuh etalase baru untuk display', '2025-02-10', '2025-01-20', NULL),
(3, '84d1dc5f-60bc-11f1-bf39-00e01e54316e', 'Peralatan', 'tinggi', 'pending', '', 'Butuh kompresor angin baru', NULL, '2025-03-01', NULL),
(4, '84d1ea1d-60bc-11f1-bf39-00e01e54316e', 'Modal Usaha', 'sedang', 'disetujui', 'oke', 'Modal untuk beli mesin jahit baru', '2026-05-25', '2025-03-05', 12),
(5, '84d1ebb5-60bc-11f1-bf39-00e01e54316e', 'Pemasaran', 'rendah', 'disetujui', 'Bantuan desain kemasan', 'Butuh bantuan branding kemasan', '2025-03-15', '2025-03-01', NULL),
(6, '84d1ecd8-60bc-11f1-bf39-00e01e54316e', 'Peralatan', 'sedang', 'ditolak', 'Belum memenuhi syarat', 'Butuh kursi salon tambahan', '2025-03-20', '2025-03-10', NULL),
(7, '84d1eddd-60bc-11f1-bf39-00e01e54316e', 'Modal Usaha', 'tinggi', 'pending', '', 'Perluasan kolam lele', NULL, '2025-04-01', NULL),
(8, '84d1eedf-60bc-11f1-bf39-00e01e54316e', 'Pelatihan', 'rendah', 'disetujui', 'Jadwal pelatihan bulan depan', 'Pelatihan desain fashion', '2025-04-10', '2025-04-01', NULL),
(9, '84d1efe4-60bc-11f1-bf39-00e01e54316e', 'Modal Usaha', 'tinggi', 'disetujui', 'oke', 'Tambah stok material bangunan', '2026-05-25', '2025-04-15', 12),
(10, '84d1f110-60bc-11f1-bf39-00e01e54316e', 'Pemasaran', 'sedang', 'disetujui', 'Dapat slot bazar desa', 'Ikut bazar desa bulan depan', '2025-05-01', '2025-04-20', NULL),
(11, '84d1f110-60bc-11f1-bf39-00e01e54316e', 'pakan', 'sedang', 'pending', NULL, 'a', NULL, '2026-05-20', NULL),
(12, '84d1cc3f-60bc-11f1-bf39-00e01e54316e', 'pakan', 'rendah', 'disetujui', 'oke', '1', '2026-05-20', '2026-05-20', 12),
(13, '84d1f110-60bc-11f1-bf39-00e01e54316e', 'asqwasdas', 'tinggi', 'pending', NULL, 'asdas', NULL, '2026-05-25', NULL),
(14, '84d1cc3f-60bc-11f1-bf39-00e01e54316e', 'Trasnport', 'rendah', 'pending', NULL, 'Pengantaran Ke Desa Sebelah', NULL, '2026-05-25', NULL);

--
-- Trigger `bantuan`
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
-- Struktur dari tabel `bantuan_history`
--

CREATE TABLE `bantuan_history` (
  `id_history` int(11) NOT NULL,
  `id_kebutuhan` int(11) DEFAULT NULL,
  `id_umkm` int(11) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `prioritas` enum('rendah','sedang','tinggi') DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','dihapus') DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_validasi` date DEFAULT NULL,
  `tanggal_pengajuan` date DEFAULT NULL,
  `id_validator` int(11) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bantuan_history`
--

INSERT INTO `bantuan_history` (`id_history`, `id_kebutuhan`, `id_umkm`, `jenis`, `prioritas`, `status`, `catatan`, `deskripsi`, `tanggal_validasi`, `tanggal_pengajuan`, `id_validator`, `action_type`, `action_time`) VALUES
(1, 14, 1, 'asqwasdas', 'rendah', 'pending', NULL, 'asd', NULL, '2026-05-25', NULL, 'UPDATE', '2026-06-04 15:11:48'),
(2, 1, 1, 'Modal Usaha', 'tinggi', 'disetujui', 'Disetujui untuk renovasi', 'Butuh modal untuk renovasi warung', '2025-02-01', '2025-01-15', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(3, 2, 2, 'Peralatan', 'sedang', 'disetujui', 'Disetujui 1 unit etalase', 'Butuh etalase baru untuk display', '2025-02-10', '2025-01-20', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(4, 3, 3, 'Peralatan', 'tinggi', 'pending', '', 'Butuh kompresor angin baru', NULL, '2025-03-01', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(5, 4, 4, 'Modal Usaha', 'sedang', 'disetujui', 'oke', 'Modal untuk beli mesin jahit baru', '2026-05-25', '2025-03-05', 12, 'UPDATE', '2026-06-05 08:57:02'),
(6, 5, 5, 'Pemasaran', 'rendah', 'disetujui', 'Bantuan desain kemasan', 'Butuh bantuan branding kemasan', '2025-03-15', '2025-03-01', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(7, 6, 6, 'Peralatan', 'sedang', 'ditolak', 'Belum memenuhi syarat', 'Butuh kursi salon tambahan', '2025-03-20', '2025-03-10', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(8, 7, 7, 'Modal Usaha', 'tinggi', 'pending', '', 'Perluasan kolam lele', NULL, '2025-04-01', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(9, 8, 8, 'Pelatihan', 'rendah', 'disetujui', 'Jadwal pelatihan bulan depan', 'Pelatihan desain fashion', '2025-04-10', '2025-04-01', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(10, 9, 9, 'Modal Usaha', 'tinggi', 'disetujui', 'oke', 'Tambah stok material bangunan', '2026-05-25', '2025-04-15', 12, 'UPDATE', '2026-06-05 08:57:02'),
(11, 10, 10, 'Pemasaran', 'sedang', 'disetujui', 'Dapat slot bazar desa', 'Ikut bazar desa bulan depan', '2025-05-01', '2025-04-20', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(12, 11, 10, 'pakan', 'sedang', 'pending', NULL, 'a', NULL, '2026-05-20', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(13, 12, 1, 'pakan', 'rendah', 'disetujui', 'oke', '1', '2026-05-20', '2026-05-20', 12, 'UPDATE', '2026-06-05 08:57:02'),
(14, 13, 10, 'asqwasdas', 'tinggi', 'pending', NULL, 'asdas', NULL, '2026-05-25', NULL, 'UPDATE', '2026-06-05 08:57:02'),
(15, 14, 1, 'Trasnport', 'rendah', 'pending', NULL, 'Pengantaran Ke Desa Sebelah', NULL, '2026-05-25', NULL, 'UPDATE', '2026-06-05 08:57:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `journey`
--

CREATE TABLE `journey` (
  `id_journey` int(11) NOT NULL,
  `id_umkm` varchar(36) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `deksripsi` text NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `journey`
--

INSERT INTO `journey` (`id_journey`, `id_umkm`, `foto`, `deksripsi`, `tanggal`) VALUES
(1, '84d1cc3f-60bc-11f1-bf39-00e01e54316d', 'journey1.jpg', 'Pembukaan warung pertama kali', '2024-01-15'),
(2, '84d1da34-60bc-11f1-bf39-00e01e54316c', 'journey2.jpg', 'Renovasi warung dan tambah menu', '2024-06-20'),
(3, '84d1dc5f-60bc-11f1-bf39-00e01e54316b', 'journey3.jpg', 'Grand opening toko kelontong', '2024-02-10'),
(4, '84d1ea1d-60bc-11f1-bf39-00e01e54316a', 'journey4.jpg', 'Mulai usaha bengkel dari garasi', '2023-08-05'),
(5, '84d1ebb5-60bc-11f1-bf39-00e01e54316e', 'journey5.jpg', 'Dapat orderan pertama 100 kaos', '2024-03-12'),
(6, '84d1ea1d-60bc-11f1-bf39-00e01e54316g', 'journey6.jpg', 'Produksi keripik pertama 50 bungkus', '2024-04-01'),
(7, '84d1da34-60bc-11f1-bf39-00e01e543163', 'journey7.jpg', 'Masuk marketplace online', '2024-09-15'),
(8, '84d1dc5f-60bc-11f1-bf39-00e01e543165', 'journey8.jpg', 'Panen lele pertama 500kg', '2024-05-20'),
(9, '84d1cc3f-60bc-11f1-bf39-00e01e543166', 'journey9.jpg', 'Buka kelas jahit untuk ibu-ibu', '2024-07-10'),
(10, '84d1ea1d-60bc-11f1-bf39-00e01e543168', 'journey10.jpg', 'Ikut bazar desa pertama kali', '2024-11-25');

--
-- Trigger `journey`
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
-- Struktur dari tabel `journey_history`
--

CREATE TABLE `journey_history` (
  `id_history` int(11) NOT NULL,
  `id_journey` int(11) DEFAULT NULL,
  `id_umkm` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `dekripsi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_umkm` varchar(36) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `status` enum('aktif','dihapus') NOT NULL DEFAULT 'aktif',
  `kategori` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `id_umkm`, `nama_produk`, `status`, `kategori`, `harga`, `deskripsi`, `foto`) VALUES
(1, '84d1cc3f-60bc-11f1-bf39-00e01e54316e', 'Nasi Goreng Spesial', 'aktif', 'Makanan', 15000, 'Nasi goreng dengan telur dan ayam', 'nasgor.jpg'),
(2, '84d1cc3f-60bc-11f1-bf39-00e01e54316e', 'Mie Ayam Bakso', 'aktif', 'Makanan', 12000, 'Mie ayam dengan bakso sapi', 'mieayam.jpg'),
(3, '84d1da34-60bc-11f1-bf39-00e01e54316e', 'Beras Premium 5kg', 'aktif', 'Sembako', 65000, 'Beras kualitas premium', 'beras.jpg'),
(4, '84d1da34-60bc-11f1-bf39-00e01e54316e', 'Minyak Goreng 2L', 'aktif', 'Sembako', 32000, 'Minyak goreng kemasan 2 liter', 'minyak.jpg'),
(5, '84d1dc5f-60bc-11f1-bf39-00e01e54316e', 'Service Ringan Motor', 'aktif', 'Jasa', 50000, 'Ganti oli dan tune up', 'service.jpg'),
(6, '84d1ea1d-60bc-11f1-bf39-00e01e54316e', 'Kaos Polos', 'aktif', 'Pakaian', 45000, 'Kaos cotton combed 30s', 'kaos.jpg'),
(7, '84d1ebb5-60bc-11f1-bf39-00e01e54316e', 'Keripik Singkong Original', 'aktif', 'Makanan', 10000, 'Keripik singkong renyah 200gr', 'keripik.jpg'),
(8, '84d1ebb5-60bc-11f1-bf39-00e01e54316e', 'Keripik Singkong Pedas', 'aktif', 'Makanan', 12000, 'Keripik singkong pedas 200gr', 'keripik_pedas.jpg'),
(9, '84d1eddd-60bc-11f1-bf39-00e01e54316e', 'Lele Segar 1kg', 'aktif', 'Perikanan', 25000, 'Lele segar siap masak', 'lele.jpg'),
(10, '84d1f110-60bc-11f1-bf39-00e01e54316e', 'Kue Lapis Legit', 'aktif', 'Makanan', 85000, 'Kue lapis legit homemade', 'lapis.jpg'),
(11, '84d1ebb5-60bc-11f1-bf39-00e01e54316e', 'Singkong Rebus', 'aktif', 'Kuliner', 1000, 'Lezat Dan Bergizi', 'prod_6a2148bd225cd7.75935071.jpg');

--
-- Trigger `produk`
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
-- Struktur dari tabel `produk_history`
--

CREATE TABLE `produk_history` (
  `id_history` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_umkm` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk_history`
--

INSERT INTO `produk_history` (`id_history`, `id_produk`, `id_umkm`, `nama_produk`, `kategori`, `harga`, `deskripsi`, `foto`, `action_type`, `action_time`) VALUES
(1, 1, 1, 'Nasi Goreng Spesial', 'Makanan', 15000, 'Nasi goreng dengan telur dan ayam', 'nasgor.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(2, 2, 1, 'Mie Ayam Bakso', 'Makanan', 12000, 'Mie ayam dengan bakso sapi', 'mieayam.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(3, 3, 2, 'Beras Premium 5kg', 'Sembako', 65000, 'Beras kualitas premium', 'beras.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(4, 4, 2, 'Minyak Goreng 2L', 'Sembako', 32000, 'Minyak goreng kemasan 2 liter', 'minyak.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(5, 5, 3, 'Service Ringan Motor', 'Jasa', 50000, 'Ganti oli dan tune up', 'service.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(6, 6, 4, 'Kaos Polos', 'Pakaian', 45000, 'Kaos cotton combed 30s', 'kaos.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(7, 7, 5, 'Keripik Singkong Original', 'Makanan', 10000, 'Keripik singkong renyah 200gr', 'keripik.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(8, 8, 5, 'Keripik Singkong Pedas', 'Makanan', 12000, 'Keripik singkong pedas 200gr', 'keripik_pedas.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(9, 9, 7, 'Lele Segar 1kg', 'Perikanan', 25000, 'Lele segar siap masak', 'lele.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(10, 10, 10, 'Kue Lapis Legit', 'Makanan', 85000, 'Kue lapis legit homemade', 'lapis.jpg', 'UPDATE', '2026-06-05 08:57:02'),
(11, 11, 5, 'Singkong Rebus', 'Kuliner', 1000, 'Lezat Dan Bergizi', 'prod_6a2148bd225cd7.75935071.jpg', 'UPDATE', '2026-06-05 08:57:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profile`
--

CREATE TABLE `profile` (
  `id_profile` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nik` text NOT NULL,
  `no_hp` text NOT NULL,
  `no_kk` text NOT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `foto_kk` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `profile`
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
-- Trigger `profile`
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
-- Struktur dari tabel `profile_history`
--

CREATE TABLE `profile_history` (
  `id_history` int(11) NOT NULL,
  `id_profile` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nik` text DEFAULT NULL,
  `no_hp` text DEFAULT NULL,
  `no_kk` text DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `foto_kk` varchar(255) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `umkm`
--

CREATE TABLE `umkm` (
  `id_umkm` varchar(36) NOT NULL DEFAULT (uuid()),
  `nama_umkm` varchar(100) NOT NULL,
  `jenis_usaha` varchar(100) NOT NULL DEFAULT '',
  `id_user` int(11) NOT NULL,
  `id_validator` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `status` enum('pending','aktif','nonaktif') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `umkm`
--

INSERT INTO `umkm` (`id_umkm`, `nama_umkm`, `jenis_usaha`, `id_user`, `id_validator`, `alamat`, `status`) VALUES
('84d1cc3f-60bc-11f1-bf39-00e01e54316e', 'Warung Makan Barokah', 'kuliner', 1, 1, 'Jl. Gandoang No.1, RT 01/RW 01', 'aktif'),
('84d1da34-60bc-11f1-bf39-00e01e54316e', 'Toko Kelontong Sejahtera', 'perdagangan', 2, 1, 'Jl. Gandoang No.5, RT 02/RW 01', 'aktif'),
('84d1dc5f-60bc-11f1-bf39-00e01e54316e', 'Bengkel Motor Jaya', 'jasa', 3, 1, 'Jl. Raya Cileungsi No.10', 'aktif'),
('84d1ea1d-60bc-11f1-bf39-00e01e54316e', 'Konveksi Mandiri', 'konveksi', 4, 1, 'Jl. Gandoang No.15, RT 03/RW 02', 'aktif'),
('84d1ebb5-60bc-11f1-bf39-00e01e54316e', 'Keripik Singkong Ibu Ani', 'kuliner', 5, 1, 'Jl. Jonggol No.8, RT 01/RW 03', 'aktif'),
('84d1ecd8-60bc-11f1-bf39-00e01e54316e', 'Salon Cantik Alami', 'jasa', 6, 12, 'Jl. Gandoang No.20, RT 04/RW 01', 'pending'),
('84d1eddd-60bc-11f1-bf39-00e01e54316e', 'Ternak Lele Makmur', 'peternakan', 7, 1, 'Jl. Gandoang No.25, RT 05/RW 02', 'aktif'),
('84d1eedf-60bc-11f1-bf39-00e01e54316e', 'Jahit Rina Collection', 'konveksi', 8, 1, 'Jl. Raya Cileungsi No.30', 'aktif'),
('84d1efe4-60bc-11f1-bf39-00e01e54316e', 'Toko Bangunan Maju', 'perdagangan', 9, 1, 'Jl. Gandoang No.35, RT 02/RW 03', 'pending'),
('84d1f110-60bc-11f1-bf39-00e01e54316e', 'Kue Basah Bu Dewi', 'kuliner', 10, 1, 'Jl. Jonggol No.12, RT 03/RW 01', 'aktif'),
('84d1f212-60bc-11f1-bf39-00e01e54316e', 'Jawir', 'Jasa', 13, 12, 'warungbambu', 'pending'),
('9168485d-60bc-11f1-bf39-00e01e54316e', 'test', 'test', 13, 12, 'tst', 'pending');

--
-- Trigger `umkm`
--
DELIMITER $$
CREATE TRIGGER `trg_umkm_update` BEFORE UPDATE ON `umkm` FOR EACH ROW INSERT INTO umkm_history (
    id_umkm,
    nama_umkm,
    jenis_usaha,
    id_user,
    id_validator,
    alamat,
    status,
    action_type
)
VALUES (
    OLD.id_umkm,
    OLD.nama_umkm,
    OLD.jenis_usaha,
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
-- Struktur dari tabel `umkm_history`
--

CREATE TABLE `umkm_history` (
  `id_history` int(11) NOT NULL,
  `id_umkm` int(11) DEFAULT NULL,
  `nama_umkm` varchar(100) DEFAULT NULL,
  `jenis_usaha` varchar(100) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_validator` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('pending','aktif','nonaktif') DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `umkm_history`
--

INSERT INTO `umkm_history` (`id_history`, `id_umkm`, `nama_umkm`, `jenis_usaha`, `id_user`, `id_validator`, `alamat`, `status`, `action_type`, `action_time`) VALUES
(1, 1, 'Warung Makan Barokah', 'kuliner', 1, 1, 'Jl. Gandoang No.1, RT 01/RW 01', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(2, 2, 'Toko Kelontong Sejahtera', 'perdagangan', 2, 1, 'Jl. Gandoang No.5, RT 02/RW 01', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(3, 3, 'Bengkel Motor Jaya', 'jasa', 3, 1, 'Jl. Raya Cileungsi No.10', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(4, 4, 'Konveksi Mandiri', 'konveksi', 4, 1, 'Jl. Gandoang No.15, RT 03/RW 02', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(5, 5, 'Keripik Singkong Ibu Ani', 'kuliner', 5, 1, 'Jl. Jonggol No.8, RT 01/RW 03', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(6, 6, 'Salon Cantik Alami', 'jasa', 6, 12, 'Jl. Gandoang No.20, RT 04/RW 01', 'pending', 'UPDATE', '2026-06-05 08:57:02'),
(7, 7, 'Ternak Lele Makmur', 'peternakan', 7, 1, 'Jl. Gandoang No.25, RT 05/RW 02', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(8, 8, 'Jahit Rina Collection', 'konveksi', 8, 1, 'Jl. Raya Cileungsi No.30', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(9, 9, 'Toko Bangunan Maju', 'perdagangan', 9, 1, 'Jl. Gandoang No.35, RT 02/RW 03', 'pending', 'UPDATE', '2026-06-05 08:57:02'),
(10, 10, 'Kue Basah Bu Dewi', 'kuliner', 10, 1, 'Jl. Jonggol No.12, RT 03/RW 01', 'aktif', 'UPDATE', '2026-06-05 08:57:02'),
(11, 11, 'Jawir', 'Jasa', 13, 12, 'warungbambu', 'pending', 'UPDATE', '2026-06-05 08:57:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `role` enum('umkm','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user`
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
-- Indeks untuk tabel `bantuan`
--
ALTER TABLE `bantuan`
  ADD PRIMARY KEY (`id_kebutuhan`),
  ADD KEY `fk_bantuan_validator` (`id_validator`),
  ADD KEY `bantuan_ibfk_2` (`id_umkm`);

--
-- Indeks untuk tabel `bantuan_history`
--
ALTER TABLE `bantuan_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indeks untuk tabel `journey`
--
ALTER TABLE `journey`
  ADD PRIMARY KEY (`id_journey`);

--
-- Indeks untuk tabel `journey_history`
--
ALTER TABLE `journey_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `produk_ibfk_1` (`id_umkm`);

--
-- Indeks untuk tabel `produk_history`
--
ALTER TABLE `produk_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indeks untuk tabel `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `profile_history`
--
ALTER TABLE `profile_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indeks untuk tabel `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id_umkm`),
  ADD KEY `id_user` (`id_user`,`id_validator`);

--
-- Indeks untuk tabel `umkm_history`
--
ALTER TABLE `umkm_history`
  ADD PRIMARY KEY (`id_history`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bantuan`
--
ALTER TABLE `bantuan`
  MODIFY `id_kebutuhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `bantuan_history`
--
ALTER TABLE `bantuan_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `journey`
--
ALTER TABLE `journey`
  MODIFY `id_journey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `journey_history`
--
ALTER TABLE `journey_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `produk_history`
--
ALTER TABLE `produk_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `profile_history`
--
ALTER TABLE `profile_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `umkm_history`
--
ALTER TABLE `umkm_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bantuan`
--
ALTER TABLE `bantuan`
  ADD CONSTRAINT `bantuan_ibfk_2` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bantuan_validator` FOREIGN KEY (`id_validator`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
