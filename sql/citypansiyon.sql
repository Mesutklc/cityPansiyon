-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 28 May 2025, 15:58:09
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
-- Tablo için tablo yapısı `cash_transactions`
--

CREATE TABLE `cash_transactions` (
  `id` int(11) NOT NULL,
  `kasa_tipi` enum('nakit','banka','kredi_karti') NOT NULL,
  `hareket_tipi` enum('gelir','gider') NOT NULL,
  `tutar` decimal(10,2) NOT NULL CHECK (`tutar` >= 0),
  `aciklama` text DEFAULT NULL,
  `islem_tarihi` datetime NOT NULL DEFAULT current_timestamp(),
  `olusturan_kullanici` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `cash_transactions`
--

INSERT INTO `cash_transactions` (`id`, `kasa_tipi`, `hareket_tipi`, `tutar`, `aciklama`, `islem_tarihi`, `olusturan_kullanici`, `created_at`) VALUES
(1, 'kredi_karti', 'gelir', 10.00, 'Rezervasyon ID: 55 için ödeme', '2025-05-28 12:49:51', NULL, '2025-05-28 09:49:51');

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
(8, 'hamdi adam', '123456789', 'deneme@deneme.com', '1111111111', 6000.00, '2025-05-26 19:21:08'),
(10, 'yedi kişi', '54815418484', 'yeni@deneme.com', '12314494848', 0.00, '2025-05-26 20:06:01'),
(11, 'adam adam', '4543535435', 'adam@adam.com', '34343434343', 0.00, '2025-05-27 09:26:25'),
(12, 'kemal kılıç', '123456789', 'kemal@gmaill.com', '1234568959', 0.00, '2025-05-27 09:53:59'),
(13, 'nihal kutlu', '05453214568', 'nihalkutlu@gmail.com', '65412354891', 50.00, '2025-05-28 09:27:47');

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
(54, 6, '2025-05-28', '2025-05-31', '', '123456789', 'deneme@deneme.com', '1111111111', '', '2025-05-27 19:56:45', 3333, 'hamdi adam', 4, 'ödenmedi', 0.00, NULL, NULL, NULL),
(55, 3, '2025-05-28', '2025-05-31', '', '05453214568', 'nihalkutlu@gmail.com', '65412354891', '', '2025-05-28 09:27:47', 10, 'nihal kutlu', 4, 'ödenmedi', 30.00, 'kredi_karti', NULL, 13),
(56, 8, '2025-05-28', '2025-05-30', 'rezerve', '123456789', 'kemal@gmaill.com', '1234568959', '', '2025-05-28 12:39:36', 1500, 'kemal kılıç', 3, 'ödenmedi', 0.00, NULL, NULL, 8),
(57, 7, '2025-05-28', '2025-05-30', 'rezerve', '123456789', 'kemal@gmaill.com', '1234568959', '', '2025-05-28 12:40:30', 500, 'kemal kılıç', 3, 'ödenmedi', 0.00, NULL, NULL, 8);

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
(3, '1', NULL, 10.00, 'boş', 4),
(5, '2', NULL, 1000.00, 'boş', 2),
(6, '3', NULL, 2000.00, 'boş', 3),
(7, '4', NULL, 500.00, 'boş', 2),
(8, '5', NULL, 1500.00, 'boş', 1),
(9, '6', NULL, NULL, 'boş', 3),
(10, '7', NULL, NULL, 'boş', 4);

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
-- Tablo için indeksler `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

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
-- Tablo için AUTO_INCREMENT değeri `cash_transactions`
--
ALTER TABLE `cash_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

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
-- Tablo kısıtlamaları `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE cash_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,  -- örn: Nakit, Kart, Havale, ETS
    balance DECIMAL(10,2) DEFAULT 0
);

ALTER TABLE cash_transactions
ADD COLUMN cash_account_id INT,
ADD FOREIGN KEY (cash_account_id) REFERENCES cash_accounts(id);

USE citypansiyon;
ALTER TABLE cash_transactions
ADD COLUMN cash_account_id INT,
ADD FOREIGN KEY (cash_account_id) REFERENCES cash_accounts(id);

CREATE TABLE cash_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,  -- örn: Nakit, Kart, Havale, ETS
    balance DECIMAL(10,2) DEFAULT 0
);

ALTER TABLE cash_transactions
ADD COLUMN cash_account_id INT,
ADD FOREIGN KEY (cash_account_id) REFERENCES cash_accounts(id);

ALTER TABLE cash_transactions DROP COLUMN kasa_tipi;
ALTER TABLE cash_transactions ADD COLUMN customer_id INT NULL;
CREATE TABLE kiosk_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL
);
CREATE TABLE kiosk_sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    cash_account_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES kiosk_products(id),
    FOREIGN KEY (cash_account_id) REFERENCES cash_accounts(id)
);

use citypansiyon;
CREATE TABLE kiosk_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL
);
CREATE TABLE kiosk_sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    cash_account_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES kiosk_products(id),
    FOREIGN KEY (cash_account_id) REFERENCES cash_accounts(id)
);
CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    cash_account_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cash_account_id) REFERENCES cash_accounts(id)
);
