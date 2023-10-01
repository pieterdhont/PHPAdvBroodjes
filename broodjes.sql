-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 29 sep 2023 om 22:50
-- Serverversie: 10.4.28-MariaDB
-- PHP-versie: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `broodjes`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `fillings`
--

CREATE TABLE `fillings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `fillings`
--

INSERT INTO `fillings` (`id`, `name`, `price`) VALUES
(1, 'Kaas', 1.00),
(2, 'Ham', 1.00),
(3, 'Kipfilet', 1.50),
(4, 'Tonijnsalade', 1.80),
(5, 'Eiersalade', 1.50),
(6, 'Salami', 1.20),
(7, 'Rozemarijn Kip', 1.70),
(8, 'Zalm', 2.00),
(9, 'Vegetarisch', 1.30),
(10, 'Vegan', 1.50),
(11, 'Bacon', 1.50),
(12, 'Pindakaas', 0.90),
(13, 'Honing Mosterd', 0.80),
(14, 'Pesto', 1.00),
(15, 'Geitenkaas', 1.70),
(16, 'Ossenworst', 1.60),
(17, 'Kipkerrie salade', 1.80),
(18, 'Brie', 1.50),
(19, 'Roomkaas', 1.20),
(20, 'Hüttenkäse', 1.10),
(21, 'Filet Americain', 1.90),
(22, 'Martino', 2.00),
(23, 'Serranoham', 2.10),
(24, 'Carpaccio', 2.20),
(25, 'Gerookte kalkoen', 1.70),
(26, 'Krabsalade', 1.80),
(27, 'Mozzarella', 1.40),
(28, 'Hummus', 1.30),
(29, 'Tapenade', 1.20),
(30, 'Rauwkost', 0.90);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_filling`
--

CREATE TABLE `order_filling` (
  `order_sandwich_id` int(11) NOT NULL,
  `filling_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_sandwich`
--

CREATE TABLE `order_sandwich` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `sandwich_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sandwiches`
--

CREATE TABLE `sandwiches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `sandwiches`
--

INSERT INTO `sandwiches` (`id`, `name`, `price`) VALUES
(1, 'Klein Grof', 1.50),
(2, 'Groot Grof', 2.00),
(3, 'Klein Wit', 1.50),
(4, 'Groot Wit', 2.00),
(5, 'Ciabatta', 2.50),
(6, 'Baguette', 2.50),
(7, 'Volkoren', 2.00),
(8, 'Meergranen', 2.20),
(9, 'Pistolet', 1.70),
(10, 'Turks Brood', 3.00),
(11, 'Speltbrood', 2.50),
(12, 'Test Sandwich', 2.50),
(13, 'Panini', 3.00),
(14, 'Brioche', 2.70),
(15, 'Boerenbrood', 2.60),
(16, 'Italiaanse bol', 2.70),
(17, 'Kaiserbroodje', 1.80),
(18, 'Zadenbrood', 2.40),
(19, 'Zuurdesembrood', 2.50),
(20, 'Croissant', 1.70),
(21, 'Focaccia', 3.10),
(22, 'Notenbrood', 2.80);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `fillings`
--
ALTER TABLE `fillings`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `order_filling`
--
ALTER TABLE `order_filling`
  ADD PRIMARY KEY (`order_sandwich_id`,`filling_id`),
  ADD KEY `filling_id` (`filling_id`);

--
-- Indexen voor tabel `order_sandwich`
--
ALTER TABLE `order_sandwich`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `sandwich_id` (`sandwich_id`);

--
-- Indexen voor tabel `sandwiches`
--
ALTER TABLE `sandwiches`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `fillings`
--
ALTER TABLE `fillings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `order_sandwich`
--
ALTER TABLE `order_sandwich`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `sandwiches`
--
ALTER TABLE `sandwiches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Beperkingen voor tabel `order_filling`
--
ALTER TABLE `order_filling`
  ADD CONSTRAINT `order_filling_ibfk_1` FOREIGN KEY (`order_sandwich_id`) REFERENCES `order_sandwich` (`id`),
  ADD CONSTRAINT `order_filling_ibfk_2` FOREIGN KEY (`filling_id`) REFERENCES `fillings` (`id`);

--
-- Beperkingen voor tabel `order_sandwich`
--
ALTER TABLE `order_sandwich`
  ADD CONSTRAINT `order_sandwich_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_sandwich_ibfk_2` FOREIGN KEY (`sandwich_id`) REFERENCES `sandwiches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
