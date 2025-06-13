-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 13 Haz 2025, 07:25:17
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `citypansiyon`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cash_accounts`
--

CREATE TABLE `cash_accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `cash_accounts`
--

INSERT INTO `cash_accounts` (`id`, `name`, `balance`) VALUES
(1, 'NAKİT', 1250.00),
(2, 'KREDİ KARTI', -50400.00),
(3, 'BANKA', 0.00),
(4, 'ETS', 60.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cash_transactions`
--

CREATE TABLE `cash_transactions` (
  `id` int(11) NOT NULL,
  `hareket_tipi` enum('gelir','gider') NOT NULL,
  `tutar` decimal(10,2) NOT NULL CHECK (`tutar` >= 0),
  `aciklama` text DEFAULT NULL,
  `islem_tarihi` datetime NOT NULL DEFAULT current_timestamp(),
  `olusturan_kullanici` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cash_account_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `cash_transactions`
--

INSERT INTO `cash_transactions` (`id`, `hareket_tipi`, `tutar`, `aciklama`, `islem_tarihi`, `olusturan_kullanici`, `created_at`, `cash_account_id`, `customer_id`) VALUES
(1, 'gelir', 10.00, 'Rezervasyon ID: 55 için ödeme', '2025-05-28 12:49:51', NULL, '2025-05-28 09:49:51', NULL, NULL),
(2, 'gelir', 10.00, 'Rezervasyon ID: 73 için ödeme', '2025-05-30 22:17:19', NULL, '2025-05-30 19:17:19', NULL, NULL),
(3, 'gelir', 200.00, 'Rezervasyon ID: 74 için ödeme', '2025-05-30 22:27:46', NULL, '2025-05-30 19:27:46', 1, NULL),
(4, 'gelir', 100.00, 'kemal kılıç - Oda 4 - 2025-05-30 rezervasyonu için ödeme', '2025-05-30 22:40:22', NULL, '2025-05-30 19:40:22', 1, NULL),
(5, 'gelir', 30.00, 'kemal kılıç - Oda 4 - 2025-05-30 rezervasyonu için ödeme', '2025-05-30 22:45:05', NULL, '2025-05-30 19:45:05', 1, 12),
(6, 'gelir', 10.00, 'kemal kılıç - Oda 4 - 2025-05-30 rezervasyonu için ödeme', '2025-05-30 23:09:25', NULL, '2025-05-30 20:09:25', 1, 12),
(7, 'gelir', 150.00, 'Büfe satışı: TOST x1', '2025-05-30 23:38:10', NULL, '2025-05-30 20:38:10', 1, NULL),
(8, 'gider', 40.00, 'su alındı', '2025-06-01 22:24:26', NULL, '2025-06-01 20:24:26', 1, NULL),
(9, 'gider', 500.00, 'MAAŞ', '2025-06-01 22:28:46', NULL, '2025-06-01 20:28:46', 2, NULL),
(10, 'gider', 100.00, 'Transfer: ', '2025-06-02 21:53:22', NULL, '2025-06-02 18:53:22', 1, NULL),
(11, 'gelir', 100.00, 'Transfer: ', '2025-06-02 21:53:22', NULL, '2025-06-02 18:53:22', 2, NULL),
(12, 'gelir', 10.00, 'oda için', '2025-06-02 21:14:50', NULL, '2025-06-02 19:14:50', 1, 12),
(13, 'gelir', 10.00, 'sd', '2025-06-02 21:15:08', NULL, '2025-06-02 19:15:08', 1, 12),
(14, 'gider', 5.00, 'ss', '2025-06-02 21:15:16', NULL, '2025-06-02 19:15:16', 1, 12),
(15, 'gelir', 10.00, '32', '2025-06-02 21:17:39', NULL, '2025-06-02 19:17:39', 1, 12),
(16, 'gelir', 10.00, '10+', '2025-06-02 21:19:17', NULL, '2025-06-02 19:19:17', 1, 12),
(17, 'gider', 95.00, 'para verdi', '2025-06-02 21:20:36', NULL, '2025-06-02 19:20:36', 1, 12),
(18, 'gelir', 10.00, '2', '2025-06-02 21:24:01', NULL, '2025-06-02 19:24:01', 1, 12),
(19, 'gelir', 10.00, '1', '2025-06-02 21:24:15', NULL, '2025-06-02 19:24:15', 1, 12),
(20, 'gelir', 10.00, '10', '2025-06-02 21:41:16', NULL, '2025-06-02 19:41:16', 1, 12),
(21, 'gelir', 10.00, '22', '2025-06-02 21:41:27', NULL, '2025-06-02 19:41:27', 1, 12),
(22, 'gider', 10.00, '2', '2025-06-02 21:42:54', NULL, '2025-06-02 19:42:54', 1, 12),
(23, 'gider', 10.00, '2', '2025-06-02 21:43:13', NULL, '2025-06-02 19:43:13', 1, 12),
(24, 'gider', 10.00, '11', '2025-06-02 21:43:25', NULL, '2025-06-02 19:43:25', 1, 12),
(25, 'gelir', 50.00, '22', '2025-06-02 21:43:42', NULL, '2025-06-02 19:43:42', 1, 12),
(26, 'gelir', 50.00, '22', '2025-06-02 21:43:51', NULL, '2025-06-02 19:43:51', 1, 12),
(27, 'gelir', 5000.00, 'yedi kişi - Oda 4 - 2025-06-04 rezervasyonu için ödeme', '2025-06-02 22:48:19', NULL, '2025-06-02 19:48:19', 1, 10),
(28, 'gider', 21500.00, '', '2025-06-02 22:07:51', NULL, '2025-06-02 20:07:51', 3, 10),
(29, 'gelir', 43000.00, 'Manuel işlem: yedi kişi için gelir', '2025-06-02 22:26:16', 0, '2025-06-02 19:26:16', 3, 10),
(30, 'gelir', 43000.00, 'Manuel işlem: yedi kişi için gelir', '2025-06-02 22:26:50', 0, '2025-06-02 19:26:50', 3, 10),
(31, 'gelir', 43000.00, 'Manuel işlem: yedi kişi için gelir', '2025-06-02 22:27:05', 0, '2025-06-02 19:27:05', 3, 10),
(32, 'gider', 860000.00, 'Manuel işlem: yedi kişi için gider', '2025-06-02 22:28:24', 0, '2025-06-02 19:28:24', 3, 10),
(33, 'gelir', 10.00, 'Cari ödeme alındı: kemal kılıç', '2025-06-02 22:30:49', 0, '2025-06-02 19:30:49', 4, 12),
(34, 'gelir', 20.00, 'Cari ödeme alındı: kemal kılıç', '2025-06-02 22:31:18', 0, '2025-06-02 19:31:18', 4, 12),
(35, 'gelir', 30.00, 'Cari ödeme alındı: kemal kılıç', '2025-06-02 22:31:32', 0, '2025-06-02 19:31:32', 4, 12),
(36, 'gelir', 774000.00, 'Cari ödeme alındı: yedi kişi', '2025-06-02 22:31:51', 0, '2025-06-02 19:31:51', 3, 10),
(37, 'gider', 26500.00, 'Cari ödeme yapıldı: yedi kişi', '2025-06-02 22:49:38', 0, '2025-06-02 19:49:38', 3, 10),
(38, 'gider', 5000.00, 'Transfer: ', '2025-06-02 23:50:21', NULL, '2025-06-02 20:50:21', 1, NULL),
(39, 'gelir', 5000.00, 'Transfer: ', '2025-06-02 23:50:21', NULL, '2025-06-02 20:50:21', 3, NULL),
(40, 'gelir', 1000.00, 'Cari ödeme alındı: nihal kutlu', '2025-06-04 21:02:06', 0, '2025-06-04 18:02:06', 1, 13),
(41, 'gider', 50000.00, 'fatura', '2025-06-04 21:13:30', NULL, '2025-06-04 19:13:30', 2, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tc` varchar(11) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `phone`, `email`, `tc`, `balance`, `created_at`) VALUES
(8, 'hamdi adamm', '123456789', 'deneme@deneme.com', '1111111111', 0.00, '2025-05-26 19:21:08'),
(10, 'yedi kişi', '54815418484', 'yeni@deneme.com', '12314494848', 0.00, '2025-05-26 20:06:01'),
(11, 'adam adam', '4543535435', 'adam@adam.com', '34343434343', 0.00, '2025-05-27 09:26:25'),
(12, 'kemal kılıç', '123456789', 'kemal@gmaill.com', '1234568959', -30.00, '2025-05-27 09:53:59'),
(13, 'nihal kutlu', '05453214568', 'nihalkutlu@gmail.com', '65412354891', -1000.00, '2025-05-28 09:27:47'),
(14, 'ibrahim açmaz', '214514848181', 'ibo@kal.com', '51561616151', 0.00, '2025-05-28 18:23:31'),
(15, 'kamil demir', '1561619819', 'dsadsad@dsfdf.com', '9198198', 0.00, '2025-06-02 19:03:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_type_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `cash_account_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `expenses`
--

INSERT INTO `expenses` (`id`, `expense_type_id`, `description`, `amount`, `cash_account_id`, `created_at`) VALUES
(1, 2, 'PORTAKAL ÖDEMESİ', 100.00, 1, '2025-06-01 18:50:13'),
(2, 3, 'elektrik ödemesi', 50.00, 1, '2025-06-01 18:54:01'),
(3, 2, 'su alındı', 40.00, 1, '2025-06-01 19:24:26'),
(4, 5, 'MAAŞ', 500.00, 2, '2025-06-01 19:28:46'),
(5, 3, 'fatura', 50000.00, 2, '2025-06-04 18:13:30');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `expense_types`
--

CREATE TABLE `expense_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `expense_types`
--

INSERT INTO `expense_types` (`id`, `name`) VALUES
(2, 'BÜFE GİDERLERİ'),
(3, 'ELEKTRİK FATURASI'),
(4, 'SU FATURASI'),
(5, 'ELEMAN MAAŞI');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kiosk_products`
--

CREATE TABLE `kiosk_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kiosk_products`
--

INSERT INTO `kiosk_products` (`id`, `name`, `unit_price`) VALUES
(1, 'TOST', 150.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kiosk_sales`
--

CREATE TABLE `kiosk_sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `cash_account_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kiosk_sales`
--

INSERT INTO `kiosk_sales` (`id`, `product_id`, `quantity`, `total_price`, `payment_method`, `cash_account_id`, `created_at`) VALUES
(1, 1, 1, 150.00, 'Büfe', 1, '2025-05-30 20:38:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('rezerve','dolu','iptal') DEFAULT 'rezerve',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tc` varchar(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` int(11) DEFAULT NULL,
  `customer_name` text DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT 1,
  `payment_status` enum('ödenmedi','kısmen','tamamlandı') NOT NULL DEFAULT 'ödenmedi',
  `payment_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `paid_days` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reservations`
--

INSERT INTO `reservations` (`id`, `room_id`, `start_date`, `end_date`, `status`, `phone`, `email`, `tc`, `note`, `created_at`, `price`, `customer_name`, `days`, `payment_status`, `payment_amount`, `payment_method`, `paid_days`, `customer_id`) VALUES
(73, 3, '2025-05-29', '2025-05-30', 'dolu', NULL, NULL, NULL, '', '2025-05-28 20:38:39', 10, NULL, 1, 'ödenmedi', 10.00, 'Nakit', NULL, 12),
(74, 7, '2025-05-30', '2025-05-31', 'dolu', NULL, NULL, NULL, '', '2025-05-30 19:25:45', 500, NULL, 1, 'ödenmedi', 340.00, NULL, NULL, 12);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('boş','dolu') DEFAULT 'boş',
  `capacity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `type`, `price`, `status`, `capacity`) VALUES
(3, '1', NULL, 500.00, 'boş', 5),
(5, '2', NULL, 1000.00, 'boş', 2),
(6, '3', NULL, 2000.00, 'boş', 3),
(7, '4', NULL, 500.00, 'boş', 2),
(8, '5', NULL, 1500.00, 'boş', 1),
(9, '6', NULL, 200.00, 'boş', 3),
(10, '7', NULL, 300.00, 'boş', 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cash_accounts`
--
ALTER TABLE `cash_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_account_id` (`cash_account_id`);

--
-- Tablo için indeksler `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_account_id` (`cash_account_id`),
  ADD KEY `expense_type_id` (`expense_type_id`);

--
-- Tablo için indeksler `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kiosk_products`
--
ALTER TABLE `kiosk_products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kiosk_sales`
--
ALTER TABLE `kiosk_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `cash_account_id` (`cash_account_id`);

--
-- Tablo için indeksler `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Tablo için indeksler `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `cash_accounts`
--
ALTER TABLE `cash_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `cash_transactions`
--
ALTER TABLE `cash_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Tablo için AUTO_INCREMENT değeri `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `kiosk_products`
--
ALTER TABLE `kiosk_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `kiosk_sales`
--
ALTER TABLE `kiosk_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Tablo için AUTO_INCREMENT değeri `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD CONSTRAINT `cash_transactions_ibfk_1` FOREIGN KEY (`cash_account_id`) REFERENCES `cash_accounts` (`id`);

--
-- Tablo kısıtlamaları `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`cash_account_id`) REFERENCES `cash_accounts` (`id`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`);

--
-- Tablo kısıtlamaları `kiosk_sales`
--
ALTER TABLE `kiosk_sales`
  ADD CONSTRAINT `kiosk_sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `kiosk_products` (`id`),
  ADD CONSTRAINT `kiosk_sales_ibfk_2` FOREIGN KEY (`cash_account_id`) REFERENCES `cash_accounts` (`id`);

--
-- Tablo kısıtlamaları `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
