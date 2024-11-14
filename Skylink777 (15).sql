-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 14 2024 г., 22:44
-- Версия сервера: 5.7.39-log
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Skylink777`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Flights`
--

CREATE TABLE `Flights` (
  `flight_id` int(11) NOT NULL,
  `departure_location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arrival_location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `total_seats` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Flights`
--

INSERT INTO `Flights` (`flight_id`, `departure_location`, `arrival_location`, `duration`, `price`, `date`, `departure_time`, `arrival_time`, `total_seats`, `available_seats`) VALUES
(1, 'Москва, Россия', 'Техас, США', 240, '500.00', '2024-10-15', '10:00:00', '14:00:00', 4, 1),
(2, 'Москва, Россия', 'Нью-Йорк, США', 300, '550.00', '2024-10-15', '11:00:00', '15:00:00', 5, 5),
(3, 'Москва, Россия', 'Лондон, Великобритания', 180, '600.00', '2024-10-15', '12:00:00', '16:00:00', 5, 5),
(4, 'Техас, США', 'Москва, Россия', 240, '500.00', '2024-10-20', '15:00:00', '19:00:00', 3, 3),
(5, 'Нью-Йорк, США', 'Москва, Россия', 300, '550.00', '2024-10-20', '16:00:00', '20:00:00', 3, 3),
(6, 'Лондон, Великобритания', 'Москва, Россия', 180, '600.00', '2024-10-20', '17:00:00', '21:00:00', 3, 3),
(7, 'Москва', 'Атланта', 240, '1000.00', '2024-11-06', '20:12:00', '15:13:00', 10, 4),
(8, 'Кабачок', 'Табачок', 190, '10000.00', '2024-11-15', '14:09:00', '16:11:00', 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `Newsletter_Subscriptions`
--

CREATE TABLE `Newsletter_Subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `status` enum('subscribed','unsubscribed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'subscribed',
  `subscription_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Newsletter_Subscriptions`
--

INSERT INTO `Newsletter_Subscriptions` (`subscription_id`, `passenger_id`, `status`, `subscription_date`) VALUES
(2, 22, 'subscribed', '2024-11-12 19:00:32'),
(3, 28, 'unsubscribed', '2024-11-14 01:22:30');

-- --------------------------------------------------------

--
-- Структура таблицы `Orders`
--

CREATE TABLE `Orders` (
  `order_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `trip_class` enum('economy','business','first') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Orders`
--

INSERT INTO `Orders` (`order_id`, `flight_id`, `passenger_id`, `amount`, `price`, `trip_class`, `status`, `order_date`) VALUES
(1, 3, 14, 1, '0.00', 'economy', 'pending', '2024-10-28 17:10:49'),
(2, 3, 14, 1, '0.00', 'economy', 'pending', '2024-10-28 17:11:28'),
(4, 1, 22, 1, '0.00', 'economy', 'canceled', '2024-11-05 17:21:27'),
(7, 5, 22, 100, '0.00', 'economy', 'canceled', '2024-11-05 18:14:43'),
(8, 7, 22, 1, '0.00', 'economy', 'canceled', '2024-11-05 18:32:34'),
(9, 7, 22, 1, '0.00', 'economy', 'pending', '2024-11-06 19:33:11'),
(10, 2, 22, 1, '0.00', 'economy', 'pending', '2024-11-12 14:06:04'),
(13, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 15:09:04'),
(14, 7, 22, 1, '1000.00', 'economy', 'completed', '2024-11-12 17:33:53'),
(15, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 17:47:17'),
(16, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 19:00:05'),
(17, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 21:08:44'),
(18, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 21:17:12'),
(19, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 21:22:40'),
(20, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 21:23:53'),
(21, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 21:28:09'),
(22, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 21:33:00'),
(23, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 21:37:40'),
(24, 7, 22, 1, '1000.00', 'business', 'canceled', '2024-11-12 21:39:14'),
(25, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 21:43:01'),
(26, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-12 21:46:38'),
(32, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 22:24:03'),
(33, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 22:42:18'),
(34, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 22:50:19'),
(35, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 22:51:24'),
(36, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-12 23:11:23'),
(37, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 01:45:19'),
(38, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 01:47:51'),
(39, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 01:52:28'),
(40, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 01:54:36'),
(41, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 01:54:57'),
(42, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 01:55:27'),
(43, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 01:55:43'),
(44, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 02:01:31'),
(45, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 02:03:05'),
(46, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 02:03:44'),
(47, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-13 02:11:31'),
(48, 1, 27, 1, '500.00', 'economy', 'pending', '2024-11-13 02:13:22'),
(49, 1, 27, 1, '500.00', 'economy', 'pending', '2024-11-13 02:14:22'),
(50, 7, 27, 1, '1000.00', 'economy', 'pending', '2024-11-13 02:15:37'),
(51, 7, 27, 1, '1000.00', 'economy', 'pending', '2024-11-13 02:16:08'),
(52, 7, 27, 1, '1000.00', 'economy', 'canceled', '2024-11-13 02:19:36'),
(53, 7, 22, 1, '1000.00', 'economy', 'canceled', '2024-11-13 22:10:16'),
(54, 3, 22, 1, '1200.00', 'economy', 'canceled', '2024-11-14 01:15:32'),
(57, 7, 22, 1, '1000.00', 'economy', 'pending', '2024-11-14 17:38:35'),
(58, 8, 22, 1, '10000.00', 'economy', 'pending', '2024-11-14 18:14:40'),
(59, 3, 31, 1, '100.00', 'first', 'pending', '2024-11-14 18:29:45'),
(60, 7, 33, 1, '1000.00', 'economy', 'canceled', '2024-11-14 20:29:15');

-- --------------------------------------------------------

--
-- Структура таблицы `OrderSeats`
--

CREATE TABLE `OrderSeats` (
  `order_seat_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `seat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `OrderSeats`
--

INSERT INTO `OrderSeats` (`order_seat_id`, `order_id`, `seat_id`) VALUES
(48, 1, 1),
(45, 51, 9),
(46, 52, 12),
(47, 53, 13),
(51, 57, 14),
(52, 58, 16),
(53, 60, 5),
(54, 60, 6),
(55, 60, 7),
(56, 60, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `Passengers`
--

CREATE TABLE `Passengers` (
  `passenger_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Passengers`
--

INSERT INTO `Passengers` (`passenger_id`, `name`, `surname`, `age`, `gender`, `email`, `phone`, `country`, `login`, `password_hash`, `role`, `avatar_path`) VALUES
(14, 'Даниил', 'Кабачок', 19, 'male', 'alex45@mail.con', '+9 (999) 999-99-99', 'Россия', 'Dany1', '$2y$10$vfoMoAhCv9AOeWKNewnrQeMAryKFPGLiwfVq5JB/0y4YlwUCTENh6', 'user', ''),
(22, 'Алекс', 'Алекс', 19, 'male', 'alex@gmail.com', '+1 (234) 532-31-41', 'Россия', 'Alex', '$2y$10$BBPhxyM6.Yxzo8r8a7pAG.ykQ3j1fNFHwIR2vyReYXRUKHX2m90by', 'admin', 'avatars/photo_2024-10-21_19-15-38.jpg'),
(24, 'ОООвава', 'ААА', 41, 'male', 'denis@mail.ru', '+9 (999) 999-99-91', 'Россия', 'Denis', '$2y$10$rHVH.8IiZpzzBky5Vsj.wu0VSW7EJyoTaHbFUYTXLpRkEMFV/O1rm', 'user', ''),
(26, 'Алексей', 'Крутой', 22, 'female', 'denis111@mail.ru', '+9 (999) 999-11-11', 'Россия', 'Denis100', '$2y$10$wyX1NiDs6LNpNdKAAcOUUOh06G8CQWKt3lTBmj84oNm.G4lIQpgeS', 'user', ''),
(27, 'Крутой', 'Крутой', 30, 'male', 'krut@mail.com', '+9 (230) 002-44-41', 'Россия', 'Krut', '$2y$10$pS9aJZIrTLswxhkiru4uP..MUsp4T5R.Sm4JBMKYyeah2sIBZ13NG', 'user', NULL),
(28, 'Kld', 'Kld', 29, 'male', 'krd@mail.com', '+2 (465) 174-25-55', 'Россия', 'Krd', '$2y$10$ZpK1HrbaZHZfd7dvCFmcNOzizTtnJur.sfR5xtWfmvv29J7bk.xlu', 'user', NULL),
(31, 'Маленький', 'Принц', 11, 'male', 'LittlePrince@gmail.com', '+1 (909) 999-99-91', 'Россия', 'LittlePrince', '$2y$10$HM0KjFkR7.QXXnECviP94uqBidSAlkbZIUqSv8suNzFT85FzLXj3m', 'user', ''),
(33, 'Дмитрий', 'Дмитрий', 22, 'male', 'dmitry@mpt.ry', '+9 (274) 287-42-81', 'Россия', 'Dmitry', '$2y$10$IRiaIKl0UN5PkO0yPRcE6eGyBAiJNgtNf.s5EwLYb6/n.cCVcvMhW', 'user', 'avatars/Вариации логотипа SkyLink.png'),
(34, 'Леха', 'Леха', 20, 'male', 'leha@leha.ru', '+9 (989) 999-99-91', 'Россия', 'Lexa', '$2y$10$aWc7nZJoz/WRjMWXZ1mbJejNDAAwyrb4ghZpQqMfFcs9KaO6.QJjC', 'user', ''),
(35, 'ААААААА', 'БББББББ', 20, 'male', 'Dany1245@dany.ru', '+1 (256) 354-36-25', 'Россия', 'Dmitry111', '$2y$10$R1zsGvIbWkh1qIxEPMOmbODBbUY9zlWabvnY5/eVuizd5Ubepyj7q', 'user', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `Seats`
--

CREATE TABLE `Seats` (
  `seat_id` int(11) NOT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `seat_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_booked` tinyint(1) NOT NULL DEFAULT '0',
  `class` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Seats`
--

INSERT INTO `Seats` (`seat_id`, `flight_id`, `seat_number`, `is_booked`, `class`) VALUES
(1, 1, '1A', 0, 'economy'),
(2, 1, '1B', 1, 'economy'),
(3, 1, '1C', 1, 'economy'),
(4, 1, '10A', 1, 'economy'),
(5, 7, 'Ж1', 1, 'economy'),
(6, 7, 'Ж2', 1, 'economy'),
(7, 7, 'Ж3', 1, 'economy'),
(8, 7, 'Ж4', 1, 'economy'),
(9, 7, 'Ж5', 0, 'economy'),
(10, 7, 'Д1', 1, 'business'),
(11, 7, 'Д2', 1, 'business'),
(12, 7, 'ЖК-1', 0, 'business'),
(13, 7, 'ЖК-2', 0, 'business'),
(14, 7, 'Первый Класс 1А', 0, 'first'),
(16, 8, 'Первый Класс 2А', 0, 'first'),
(17, 2, 'Эконом 1', 0, 'economy'),
(18, 3, 'Эконом 1', 0, 'economy'),
(19, 4, 'Эконом 1', 0, 'economy'),
(20, 5, 'Эконом 1', 0, 'economy'),
(21, 6, 'Эконом 1', 0, 'economy'),
(22, 2, 'Бизнес 1', 0, 'business'),
(23, 3, 'Бизнес 1', 0, 'business'),
(24, 4, 'Бизнес 1', 0, 'business'),
(25, 5, 'Бизнес 1', 0, 'business'),
(26, 6, 'Бизнес 1', 0, 'business'),
(27, 2, 'Первый 1', 0, 'first'),
(28, 3, 'Первый 1', 0, 'first'),
(29, 4, 'Первый 1', 0, 'first'),
(30, 5, 'Первый 1', 0, 'first'),
(31, 6, 'Первый 1', 0, 'first'),
(32, 2, 'Эконом 2', 0, 'economy'),
(33, 2, 'Эконом 3', 0, 'economy'),
(34, 3, 'Эконом 2', 0, 'economy'),
(35, 3, 'Эконом 3', 0, 'economy'),
(36, 8, 'Эконом 1', 0, 'economy');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Flights`
--
ALTER TABLE `Flights`
  ADD PRIMARY KEY (`flight_id`);

--
-- Индексы таблицы `Newsletter_Subscriptions`
--
ALTER TABLE `Newsletter_Subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `passenger_id` (`passenger_id`);

--
-- Индексы таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `flight_id` (`flight_id`),
  ADD KEY `passenger` (`passenger_id`);

--
-- Индексы таблицы `OrderSeats`
--
ALTER TABLE `OrderSeats`
  ADD PRIMARY KEY (`order_seat_id`),
  ADD UNIQUE KEY `order_id` (`order_id`,`seat_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Индексы таблицы `Passengers`
--
ALTER TABLE `Passengers`
  ADD PRIMARY KEY (`passenger_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `Seats`
--
ALTER TABLE `Seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Flights`
--
ALTER TABLE `Flights`
  MODIFY `flight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `Newsletter_Subscriptions`
--
ALTER TABLE `Newsletter_Subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `OrderSeats`
--
ALTER TABLE `OrderSeats`
  MODIFY `order_seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT для таблицы `Passengers`
--
ALTER TABLE `Passengers`
  MODIFY `passenger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `Seats`
--
ALTER TABLE `Seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Newsletter_Subscriptions`
--
ALTER TABLE `Newsletter_Subscriptions`
  ADD CONSTRAINT `newsletter_subscriptions_ibfk_1` FOREIGN KEY (`passenger_id`) REFERENCES `Passengers` (`passenger_id`);

--
-- Ограничения внешнего ключа таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `Flights` (`flight_id`),
  ADD CONSTRAINT `passenger` FOREIGN KEY (`passenger_id`) REFERENCES `Passengers` (`passenger_id`);

--
-- Ограничения внешнего ключа таблицы `OrderSeats`
--
ALTER TABLE `OrderSeats`
  ADD CONSTRAINT `orderseats_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `orderseats_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `Seats` (`seat_id`);

--
-- Ограничения внешнего ключа таблицы `Seats`
--
ALTER TABLE `Seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `Flights` (`flight_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
