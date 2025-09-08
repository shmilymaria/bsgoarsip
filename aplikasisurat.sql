-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Sep 2025 pada 14.00
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplikasisurat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_keluar_eks`
--

CREATE TABLE `surat_keluar_eks` (
  `id` int(11) NOT NULL,
  `penerima` varchar(100) NOT NULL,
  `perihal` varchar(200) NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `waktu` date NOT NULL,
  `diupload_oleh` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surat_keluar_eks`
--

INSERT INTO `surat_keluar_eks` (`id`, `penerima`, `perihal`, `no_surat`, `file_surat`, `waktu`, `diupload_oleh`) VALUES
(1, 'BKAD Prov. Sulut', 'Konfirmasi Perpanjangan Deposito Berjangka', '001/A/KCU/VII/2025', 'uploads/1756376587_771.jpg', '2025-09-03', 1),
(2, 'contoh', 'contoh', '001/A/KCU/VII/2025', NULL, '2025-09-03', 2),
(3, 'FT. UNSRAT', 'blabla', 'ada', NULL, '2025-09-04', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_keluar_in`
--

CREATE TABLE `surat_keluar_in` (
  `id` int(11) NOT NULL,
  `penerima` varchar(100) NOT NULL,
  `perihal` varchar(200) NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `waktu` date NOT NULL,
  `diupload_oleh` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surat_keluar_in`
--

INSERT INTO `surat_keluar_in` (`id`, `penerima`, `perihal`, `no_surat`, `file_surat`, `waktu`, `diupload_oleh`) VALUES
(3, 'Div. HC', 'Permohonan BSGdirect Cab. Utama', '001/B/KCU/VII/2025', 'uploads/1756378918_160.jpg', '2025-09-03', 1),
(4, 'contoh', 'contoh', '111', NULL, '2025-09-03', 1),
(5, 'Div. HC', 'Persetujuan Istirahat Sakit Berkepanjangan Pegawai', '7690/UN12.2.7.5/LL/2025', NULL, '2025-09-03', 2),
(6, 'Div. PBJ', 'Permohonan data', '222', NULL, '2025-09-04', 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_masuk_eks`
--

CREATE TABLE `surat_masuk_eks` (
  `id` int(11) NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `perihal` varchar(200) NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `waktu` date NOT NULL,
  `diupload_oleh` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surat_masuk_eks`
--

INSERT INTO `surat_masuk_eks` (`id`, `pengirim`, `perihal`, `no_surat`, `file_surat`, `waktu`, `diupload_oleh`) VALUES
(1, 'FT UNSRAT', 'Permohonan Magang', '3794/UN12.2./LL/2025', 'uploads/1756376237_564.pdf', '2025-09-03', 1),
(2, 'FT UNSRAT', 'BA Penyerahan Mahasiswa Magang', '7690/UN12.2.7.5/LL/2025', 'uploads/1756378281_847.jpg', '2025-09-03', 1),
(5, 'CV. Sukses Jaya', 'Pengajuan Proposal', '001/A/KCU/VII/2025', NULL, '2025-09-03', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_masuk_in`
--

CREATE TABLE `surat_masuk_in` (
  `id` int(11) NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `perihal` varchar(200) NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `waktu` date NOT NULL,
  `diupload_oleh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surat_masuk_in`
--

INSERT INTO `surat_masuk_in` (`id`, `pengirim`, `perihal`, `no_surat`, `file_surat`, `waktu`, `diupload_oleh`) VALUES
(3, 'Div. HC', 'Persetujuan Istirahat Sakit Berkepanjangan Pegawai', '211/B/HC/VII/2025', 'uploads/1756376702_968.jpg', '2025-09-03', 1),
(4, 'Cabang Ranotana', 'Permintaan Data', '123/B/RNT/VIII/2025', NULL, '2025-09-03', 1),
(18, 'contoh1', 'contoh1', 'contoh1', NULL, '2025-09-08', 2),
(19, 'contoh2', 'contoh2', 'contoh2', NULL, '2025-09-02', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$jdljDvWn4JNxBw8mA3NdOOeZu7uhqUNjTxjKfYt47fwYjWWWnuP8K', 'admin'),
(2, 'shmily', '$2y$10$aA9LeBSy2QpOhcB.gEhiZ.3dCaPFzsfek8RBnnW.gXKcZbJc9ddhm', 'admin'),
(14, 'user', '$2y$10$vzc0GmpkmA0q6F2x.71zgO/xlEMY8rNoIAzrN.21QhuhO.i.L8MI2', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `surat_keluar_eks`
--
ALTER TABLE `surat_keluar_eks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sk_eks_user` (`diupload_oleh`);

--
-- Indeks untuk tabel `surat_keluar_in`
--
ALTER TABLE `surat_keluar_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sk_in_user` (`diupload_oleh`);

--
-- Indeks untuk tabel `surat_masuk_eks`
--
ALTER TABLE `surat_masuk_eks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sm_eks_user` (`diupload_oleh`);

--
-- Indeks untuk tabel `surat_masuk_in`
--
ALTER TABLE `surat_masuk_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sm_in_user` (`diupload_oleh`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `surat_keluar_eks`
--
ALTER TABLE `surat_keluar_eks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `surat_keluar_in`
--
ALTER TABLE `surat_keluar_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `surat_masuk_eks`
--
ALTER TABLE `surat_masuk_eks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `surat_masuk_in`
--
ALTER TABLE `surat_masuk_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `surat_keluar_eks`
--
ALTER TABLE `surat_keluar_eks`
  ADD CONSTRAINT `fk_sk_eks_user` FOREIGN KEY (`diupload_oleh`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `surat_keluar_in`
--
ALTER TABLE `surat_keluar_in`
  ADD CONSTRAINT `fk_sk_in_user` FOREIGN KEY (`diupload_oleh`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `surat_masuk_eks`
--
ALTER TABLE `surat_masuk_eks`
  ADD CONSTRAINT `fk_sm_eks_user` FOREIGN KEY (`diupload_oleh`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
