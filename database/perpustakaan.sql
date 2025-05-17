-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Bulan Mei 2025 pada 11.34
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
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `books`
--

CREATE TABLE `books` (
  `id_book` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `id_genre` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`id_book`, `title`, `author`, `category`, `year`, `harga`, `id_genre`, `description`) VALUES
(2, 'harry potter', 'j.k. rowling', 'fiksi', 1991, 500000.00, 2, 'ajbaksjbd'),
(3, 'cara jadi kaya', 'otong', 'pendidikan', 2024, NULL, 3, 'tutor jadi kaya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `genres`
--

CREATE TABLE `genres` (
  `id_genre` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `genres`
--

INSERT INTO `genres` (`id_genre`, `name`) VALUES
(1, 'Fiksi'),
(2, 'Non-Fiksi'),
(5, 'Romance'),
(3, 'Teknologi');

--
-- Trigger `genres`
--
DELIMITER $$
CREATE TRIGGER `trg_before_delete_genre` BEFORE DELETE ON `genres` FOR EACH ROW BEGIN
    DECLARE genreDipakai INT;
    SELECT COUNT(*) INTO genreDipakai FROM books WHERE id_genre = OLD.id_genre;

    IF genreDipakai > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tidak bisa hapus genre: masih digunakan di tabel books.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_transaksi`
--

CREATE TABLE `log_transaksi` (
  `id_log` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ebook_id` int(11) DEFAULT NULL,
  `aksi` varchar(50) DEFAULT NULL,
  `waktu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_transaksi`
--

INSERT INTO `log_transaksi` (`id_log`, `user_id`, `ebook_id`, `aksi`, `waktu`) VALUES
(1, 4, 3, 'insert transaksi', '2025-05-17 17:17:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_ebook`
--

CREATE TABLE `transaksi_ebook` (
  `id_transaksi` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ebook_id` int(11) NOT NULL,
  `tanggal_pembelian` date NOT NULL DEFAULT curdate(),
  `harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` enum('transfer','e-wallet','kartu kredit','lainnya') NOT NULL,
  `status` enum('berhasil','gagal','pending') DEFAULT 'berhasil'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_ebook`
--

INSERT INTO `transaksi_ebook` (`id_transaksi`, `user_id`, `ebook_id`, `tanggal_pembelian`, `harga`, `metode_pembayaran`, `status`) VALUES
(1, 1, 2, '2025-05-17', 15000.00, 'lainnya', 'berhasil'),
(2, 4, 3, '2025-05-17', 5000.00, 'e-wallet', 'berhasil');

--
-- Trigger `transaksi_ebook`
--
DELIMITER $$
CREATE TRIGGER `trg_after_insert_transaksi` AFTER INSERT ON `transaksi_ebook` FOR EACH ROW BEGIN
    INSERT INTO log_transaksi (user_id, ebook_id, aksi)
    VALUES (NEW.user_id, NEW.ebook_id, 'insert transaksi');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_after_update_status` AFTER UPDATE ON `transaksi_ebook` FOR EACH ROW BEGIN
    IF OLD.status <> NEW.status THEN
        INSERT INTO log_transaksi (user_id, ebook_id, aksi)
        VALUES (NEW.user_id, NEW.ebook_id, CONCAT('update status: ', NEW.status));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'ucup', '$2y$10$8ZwzDWZQKrO/8r5VzBR1X./FZJXzJ6uHrDljonKZkPJRgV5sqC3ly', 'user'),
(3, 'otong', '$2y$10$NTSl3bEVTQLzfmFgVXgwaeR/GrZrnCZzFsFgLiTBImIZIAfSSPFdm', 'admin'),
(4, 'ipeh', '$2y$10$rMV1l6uh/N4/h0m/DkSHoO0a5kGrGumMMQb6JA81rvleDFsy7yuDe', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id_book`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `id_genre` (`id_genre`);

--
-- Indeks untuk tabel `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id_genre`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `log_transaksi`
--
ALTER TABLE `log_transaksi`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `transaksi_ebook`
--
ALTER TABLE `transaksi_ebook`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ebook_id` (`ebook_id`);

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
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `id_book` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `genres`
--
ALTER TABLE `genres`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `log_transaksi`
--
ALTER TABLE `log_transaksi`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi_ebook`
--
ALTER TABLE `transaksi_ebook`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`id_genre`) REFERENCES `genres` (`id_genre`);

--
-- Ketidakleluasaan untuk tabel `transaksi_ebook`
--
ALTER TABLE `transaksi_ebook`
  ADD CONSTRAINT `transaksi_ebook_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ebook_ibfk_2` FOREIGN KEY (`ebook_id`) REFERENCES `books` (`id_book`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
