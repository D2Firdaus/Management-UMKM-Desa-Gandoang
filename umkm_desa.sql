-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 20 Bulan Mei 2026 pada 17.57
-- Versi server: 12.2.2-MariaDB
-- Versi PHP: 8.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `umkm_desa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bantuan`
--

CREATE TABLE `bantuan` (
  `id_kebutuhan` int(11) NOT NULL,
  `id_umkm` int(11) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `prioritas` enum('rendah','sedang','tinggi') NOT NULL,
  `status` enum('pending','disetujui','ditolak','dihapus') NOT NULL DEFAULT 'pending',
  `catatan` text NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_validasi` date DEFAULT NULL,
  `tanggal_pengajuan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `bantuan`
--

INSERT INTO `bantuan` (`id_kebutuhan`, `id_umkm`, `jenis`, `prioritas`, `status`, `catatan`, `deskripsi`, `tanggal_validasi`, `tanggal_pengajuan`) VALUES
(1, 1, 'Modal Usaha', 'tinggi', 'disetujui', 'Disetujui untuk renovasi', 'Butuh modal untuk renovasi warung', '2025-02-01', '2025-01-15'),
(2, 2, 'Peralatan', 'sedang', 'disetujui', 'Disetujui 1 unit etalase', 'Butuh etalase baru untuk display', '2025-02-10', '2025-01-20'),
(3, 3, 'Peralatan', 'tinggi', 'pending', '', 'Butuh kompresor angin baru', NULL, '2025-03-01'),
(4, 4, 'Modal Usaha', 'sedang', 'pending', '', 'Modal untuk beli mesin jahit baru', NULL, '2025-03-05'),
(5, 5, 'Pemasaran', 'rendah', 'disetujui', 'Bantuan desain kemasan', 'Butuh bantuan branding kemasan', '2025-03-15', '2025-03-01'),
(6, 6, 'Peralatan', 'sedang', 'ditolak', 'Belum memenuhi syarat', 'Butuh kursi salon tambahan', '2025-03-20', '2025-03-10'),
(7, 7, 'Modal Usaha', 'tinggi', 'pending', '', 'Perluasan kolam lele', NULL, '2025-04-01'),
(8, 8, 'Pelatihan', 'rendah', 'disetujui', 'Jadwal pelatihan bulan depan', 'Pelatihan desain fashion', '2025-04-10', '2025-04-01'),
(9, 9, 'Modal Usaha', 'tinggi', 'pending', '', 'Tambah stok material bangunan', NULL, '2025-04-15'),
(10, 10, 'Pemasaran', 'sedang', 'disetujui', 'Dapat slot bazar desa', 'Ikut bazar desa bulan depan', '2025-05-01', '2025-04-20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `journey`
--

CREATE TABLE `journey` (
  `id_journey` int(11) NOT NULL,
  `id_umkm` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `deksripsi` text NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `journey`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_umkm` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `status` enum('aktif','dihapus') NOT NULL DEFAULT 'aktif',
  `kategori` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `id_umkm`, `nama_produk`, `status`, `kategori`, `harga`, `deskripsi`, `foto`) VALUES
(1, 1, 'Nasi Goreng Spesial', 'dihapus', 'Makanan', 15000, 'Nasi goreng dengan telur dan ayam', 'nasgor.jpg'),
(2, 1, 'Mie Ayam Bakso', 'aktif', 'Makanan', 12000, 'Mie ayam dengan bakso sapi', 'mieayam.jpg'),
(3, 2, 'Beras Premium 5kg', 'aktif', 'Sembako', 65000, 'Beras kualitas premium', 'beras.jpg'),
(4, 2, 'Minyak Goreng 2L', 'aktif', 'Sembako', 32000, 'Minyak goreng kemasan 2 liter', 'minyak.jpg'),
(5, 3, 'Service Ringan Motor', 'aktif', 'Jasa', 50000, 'Ganti oli dan tune up', 'service.jpg'),
(6, 4, 'Kaos Polos', 'aktif', 'Pakaian', 45000, 'Kaos cotton combed 30s', 'kaos.jpg'),
(7, 5, 'Keripik Singkong Original', 'aktif', 'Makanan', 10000, 'Keripik singkong renyah 200gr', 'keripik.jpg'),
(8, 5, 'Keripik Singkong Pedas', 'aktif', 'Makanan', 12000, 'Keripik singkong pedas 200gr', 'keripik_pedas.jpg'),
(9, 7, 'Lele Segar 1kg', 'aktif', 'Perikanan', 25000, 'Lele segar siap masak', 'lele.jpg'),
(10, 10, 'Kue Lapis Legit', 'aktif', 'Makanan', 85000, 'Kue lapis legit homemade', 'lapis.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profile`
--

CREATE TABLE `profile` (
  `id_profile` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `foto_kk` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `profile`
--

INSERT INTO `profile` (`id_profile`, `id_user`, `nik`, `no_hp`, `no_kk`, `foto_kk`) VALUES
(1, 1, '3201010101010001', '081234567801', '3201010101010001', 'kk_halim.jpg'),
(2, 2, '3201010101010002', '081234567802', '3201010101010002', 'kk_bajang.jpg'),
(3, 3, '3201010101010003', '081234567803', '3201010101010003', 'kk_dede.jpg'),
(4, 4, '3201010101010004', '081234567804', '3201010101010004', 'kk_aldi.jpg'),
(5, 5, '3201010101010005', '081234567805', '3201010101010005', 'kk_arif.jpg'),
(6, 6, '3201010101010006', '081234567806', '3201010101010006', 'kk_siti.jpg'),
(7, 7, '3201010101010007', '081234567807', '3201010101010007', 'kk_ahmad.jpg'),
(8, 8, '3201010101010008', '081234567808', '3201010101010008', 'kk_rina.jpg'),
(9, 9, '3201010101010009', '081234567809', '3201010101010009', 'kk_budi.jpg'),
(10, 10, '3201010101010010', '081234567810', '3201010101010010', 'kk_dewi.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `umkm`
--

CREATE TABLE `umkm` (
  `id_umkm` int(11) NOT NULL,
  `nama_umkm` varchar(100) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_validator` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `status` enum('pending','aktif','nonaktif') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `umkm`
--

INSERT INTO `umkm` (`id_umkm`, `nama_umkm`, `id_user`, `id_validator`, `alamat`, `status`) VALUES
(1, 'Warung Makan Barokah', 1, 1, 'Jl. Gandoang No.1, RT 01/RW 01', 'aktif'),
(2, 'Toko Kelontong Sejahtera', 2, 1, 'Jl. Gandoang No.5, RT 02/RW 01', 'aktif'),
(3, 'Bengkel Motor Jaya', 3, 1, 'Jl. Raya Cileungsi No.10', 'aktif'),
(4, 'Konveksi Mandiri', 4, 1, 'Jl. Gandoang No.15, RT 03/RW 02', 'aktif'),
(5, 'Keripik Singkong Ibu Ani', 5, 1, 'Jl. Jonggol No.8, RT 01/RW 03', 'aktif'),
(6, 'Salon Cantik Alami', 6, 1, 'Jl. Gandoang No.20, RT 04/RW 01', 'pending'),
(7, 'Ternak Lele Makmur', 7, 1, 'Jl. Gandoang No.25, RT 05/RW 02', 'aktif'),
(8, 'Jahit Rina Collection', 8, 1, 'Jl. Raya Cileungsi No.30', 'aktif'),
(9, 'Toko Bangunan Maju', 9, 1, 'Jl. Gandoang No.35, RT 02/RW 03', 'pending'),
(10, 'Kue Basah Bu Dewi', 10, 1, 'Jl. Jonggol No.12, RT 03/RW 01', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `password`, `email`, `status`) VALUES
(1, 'Halim Pratama', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'halim@gmail.com', 'aktif'),
(2, 'Bajang Saputra', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bajang@gmail.com', 'aktif'),
(3, 'Dede Kurniawan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dede@gmail.com', 'aktif'),
(4, 'Aldi Firmansyah', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'aldi@gmail.com', 'aktif'),
(5, 'Arif Hidayat', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'arif@gmail.com', 'aktif'),
(6, 'Siti Nurhaliza', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siti@gmail.com', 'aktif'),
(7, 'Ahmad Fauzi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ahmad@gmail.com', 'aktif'),
(8, 'Rina Marlina', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rina@gmail.com', 'aktif'),
(9, 'Budi Santoso', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'budi@gmail.com', 'aktif'),
(10, 'Dewi Lestari', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dewi@gmail.com', 'aktif');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `bantuan`
--
ALTER TABLE `bantuan`
  ADD PRIMARY KEY (`id_kebutuhan`),
  ADD KEY `id_umkm` (`id_umkm`);

--
-- Indeks untuk tabel `journey`
--
ALTER TABLE `journey`
  ADD PRIMARY KEY (`id_journey`),
  ADD KEY `id_umkm` (`id_umkm`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_umkm` (`id_umkm`);

--
-- Indeks untuk tabel `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD UNIQUE KEY `nik` (`nik`,`no_hp`,`no_kk`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id_umkm`),
  ADD KEY `id_user` (`id_user`,`id_validator`);

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
  MODIFY `id_kebutuhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `journey`
--
ALTER TABLE `journey`
  MODIFY `id_journey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id_umkm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bantuan`
--
ALTER TABLE `bantuan`
  ADD CONSTRAINT `bantuan_ibfk_2` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `journey`
--
ALTER TABLE `journey`
  ADD CONSTRAINT `journey_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE;

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
