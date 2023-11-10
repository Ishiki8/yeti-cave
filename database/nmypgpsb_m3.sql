-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 10 2023 г., 19:10
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `nmypgpsb_m3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Bet`
--

CREATE TABLE `Bet` (
  `id` int NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sum` int NOT NULL,
  `user_id` int NOT NULL,
  `lot_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Bet`
--

INSERT INTO `Bet` (`id`, `date`, `sum`, `user_id`, `lot_id`) VALUES
(1, '2023-11-08 13:17:22', 4450, 2, 9),
(2, '2023-11-10 13:17:32', 6000, 2, 11),
(3, '2023-11-08 13:17:40', 2500, 2, 10),
(4, '2023-11-10 13:17:52', 10000, 2, 4),
(5, '2023-11-10 13:18:15', 8000, 7, 15),
(6, '2023-11-08 13:18:24', 5000, 7, 9),
(7, '2023-11-10 13:18:37', 2500, 7, 7),
(8, '2023-11-10 13:18:49', 10000, 7, 17),
(9, '2023-11-10 13:19:57', 6000, 9, 16),
(10, '2023-11-10 13:20:10', 11111, 9, 4),
(11, '2023-11-08 13:20:24', 5500, 9, 9),
(12, '2023-11-10 13:20:43', 9000, 9, 18),
(13, '2023-11-10 13:20:52', 10100, 9, 17),
(14, '2023-11-10 13:21:09', 9333, 4, 18),
(15, '2023-11-10 13:21:34', 7000, 4, 11),
(16, '2023-11-10 13:22:00', 9666, 5, 18),
(17, '2023-11-10 13:22:27', 10000, 4, 18),
(18, '2023-11-10 13:22:44', 2500, 4, 12),
(19, '2023-11-10 13:24:42', 15000, 11, 18),
(20, '2023-11-10 13:24:54', 12000, 11, 17),
(21, '2023-11-10 13:25:08', 8300, 11, 14),
(22, '2023-11-08 13:25:18', 3000, 11, 10),
(23, '2023-11-10 13:25:35', 15400, 12, 18),
(24, '2023-11-10 13:25:45', 2400, 12, 13),
(25, '2023-11-10 13:25:51', 9000, 12, 14),
(26, '2023-11-08 13:25:59', 6000, 12, 9),
(27, '2023-11-10 13:26:20', 9000, 13, 15),
(28, '2023-11-10 13:26:27', 17000, 13, 18),
(29, '2023-11-10 13:26:45', 6666, 13, 8),
(30, '2023-11-10 13:26:52', 20000, 13, 5),
(31, '2023-11-10 13:27:55', 17333, 15, 18),
(32, '2023-11-10 13:28:06', 12100, 15, 17),
(33, '2023-11-10 13:28:11', 6300, 15, 16),
(34, '2023-11-10 13:28:21', 10000, 15, 15),
(35, '2023-11-10 13:28:28', 10000, 15, 14),
(36, '2023-11-10 13:28:33', 3000, 15, 13),
(37, '2023-11-10 13:28:39', 4000, 15, 12),
(38, '2023-11-10 13:28:45', 6000, 15, 2),
(39, '2023-11-10 13:29:11', 19000, 6, 18),
(40, '2023-11-10 13:29:20', 7000, 6, 16),
(41, '2023-11-10 13:29:35', 3000, 6, 1),
(42, '2023-11-08 13:29:59', 6500, 7, 9),
(43, '2023-11-10 13:30:28', 25000, 8, 18),
(44, '2023-11-10 13:30:57', 5000, 8, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `Category`
--

CREATE TABLE `Category` (
  `id` int NOT NULL,
  `title` varchar(25) NOT NULL,
  `code` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Category`
--

INSERT INTO `Category` (`id`, `title`, `code`) VALUES
(1, 'Доски и лыжи', 'boards'),
(2, 'Крепления', 'attachment'),
(3, 'Ботинки', 'boots'),
(4, 'Одежда', 'clothing'),
(5, 'Инструменты', 'tools'),
(6, 'Разное', 'other');

-- --------------------------------------------------------

--
-- Структура таблицы `Lot`
--

CREATE TABLE `Lot` (
  `id` int NOT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(50) NOT NULL,
  `start_price` int NOT NULL,
  `end_date` date NOT NULL,
  `step` int NOT NULL,
  `author_id` int NOT NULL,
  `winner_id` int DEFAULT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Lot`
--

INSERT INTO `Lot` (`id`, `create_date`, `title`, `description`, `image`, `start_price`, `end_date`, `step`, `author_id`, `winner_id`, `category_id`) VALUES
(1, '2023-11-10 12:51:23', 'Сноуборд 1', 'Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1Сноуборд 1', '/uploads/1699620683doska.jpg', 2500, '2023-11-12', 250, 1, NULL, 1),
(2, '2023-11-10 12:52:07', 'Сноуборд 2', 'СноубордСноубордСноубордСноубордСноубордСноуборд222222', '/uploads/1699620727doska2.jpg', 5000, '2023-11-13', 500, 1, NULL, 1),
(3, '2023-11-10 12:52:39', 'Лыжи 228', 'фываывпавфпвыавыафываывафывафыаываыфваыфваыфвафывафавы', '/uploads/1699620759lyzhi.jpg', 6000, '2023-11-17', 700, 1, NULL, 1),
(4, '2023-11-10 13:06:36', 'Одежда для сноуборда 1', 'Для сноуборда', '/uploads/1699621596odezhda.jpg', 9000, '2023-11-22', 950, 3, NULL, 4),
(5, '2023-11-10 13:07:04', 'Одежда для сноуборда 2', 'Сноуборд', '/uploads/1699621624odezhda2.jpg', 15000, '2023-11-23', 500, 3, NULL, 4),
(6, '2023-11-10 13:07:44', 'Инструменты для сноуборда 1', 'Набор инструментов', '/uploads/1699621664instrumenti.jpg', 1500, '2023-11-14', 200, 3, NULL, 5),
(7, '2023-11-10 13:08:37', 'Инструменты для сноуборда 2', 'Очень классный наборчик инструментиков', '/uploads/1699621717instrumenti2.jpg', 2300, '2023-11-15', 100, 3, NULL, 5),
(8, '2023-11-10 13:09:30', 'Сноуборд Samsung A500', 'Топ доска', '/uploads/1699621770doska3.jpg', 6000, '2023-11-23', 666, 4, NULL, 1),
(9, '2023-11-10 13:10:17', 'Лыжи Xiaomi Buddy', 'Палок в комплекте нет', '/uploads/1699621817lyzhi2.jpeg', 4000, '2023-11-09', 450, 4, 7, 1),
(10, '2023-11-10 13:11:07', 'Крепления для ботинок надежные', 'Правда надежные', '/uploads/1699621867krepleniya.jpg', 2000, '2023-11-09', 150, 4, 11, 2),
(11, '2023-11-10 13:11:52', 'Сноуборд Huawei Bomba', 'Очень классный', '/uploads/1699621912doska2.jpg', 5000, '2023-11-28', 700, 5, NULL, 1),
(12, '2023-11-10 13:12:30', 'Крепления для ботинок 5', 'Балдежные крепления', '/uploads/1699621950krepleniya2.jpg', 1400, '2023-11-21', 650, 5, NULL, 2),
(13, '2023-11-10 13:13:21', 'Крепления для ботинок Redmi', 'Красивые и крепкие', '/uploads/1699622001krepleniya3.jpg', 2000, '2023-11-25', 350, 9, NULL, 2),
(14, '2023-11-10 13:14:14', 'Лыжи огненные', 'Не сноуборд, но тоже ничего', '/uploads/1699622054lyzhi3.jpg', 7900, '2023-11-30', 400, 9, NULL, 1),
(15, '2023-11-10 13:15:26', 'Ботинки для сноуборда Apple', 'Apple', '/uploads/1699622126botinki2.jpg', 6500, '2023-11-16', 550, 8, NULL, 3),
(16, '2023-11-10 13:16:12', 'Ботинки для сноуборда Honor', 'Honor', '/uploads/1699622172botinki.jpg', 5000, '2023-11-11', 250, 8, NULL, 3),
(17, '2023-11-10 13:17:11', 'Набор инструментов для сноуборда', 'Инструменты хорошие, надежные', '/uploads/1699622231instrumenti2.jpg', 2500, '2023-11-11', 100, 2, NULL, 5),
(18, '2023-11-10 13:19:36', 'Лыжи старые', 'Но надежные', '/uploads/1699622376lyzhi3.jpg', 5000, '2023-11-15', 333, 7, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contacts` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `User`
--

INSERT INTO `User` (`id`, `reg_date`, `email`, `name`, `password`, `contacts`) VALUES
(1, '2023-11-10 12:50:03', '123@mail.ru', 'Алеша', '$2y$10$MDR/SfQlpJ11eI.4PlzPt.4uMKYDS9pdGwHbKsnk9hdhQCUNM8uRy', 'telegram: @bimbam'),
(2, '2023-11-10 13:00:56', '234@mail.ru', 'Виталик', '$2y$10$V.5WGxLoiI8SKQP9tZV6KO9rqmQLx2fXaPNWXvHj2/7N1AD7FVSy6', 'Не стоит со мной связываться'),
(3, '2023-11-10 13:01:16', '345@mail.ru', 'Иван', '$2y$10$esmGETto02amKp.LweJSauYAPNODGyTCH4DDhqhkrsD6c5BLpWGKS', '88005553535'),
(4, '2023-11-10 13:01:38', '456@mail.ru', 'Александр', '$2y$10$Aq3SSMuD/8F6mJls7zznWO1PDHE0Z.qHEJzKowLPALVzpjYPIC7O2', 'skype: bimbimbam'),
(5, '2023-11-10 13:02:24', '567@mail.ru', 'Петр', '$2y$10$TLh0I4hHtyhbCZxvF.1GD.sHO8tb05sGpOnxuoHj.0APgR07MePRm', 'Звонить с 7 до 9 по номеру +7999999999'),
(6, '2023-11-10 13:02:53', '678@mail.ru', 'Андрей', '$2y$10$3Ng9to0JqKFXNGh.x9Jo.OcO7qci/zsXFZFXzX4hS3ucVdie31kPO', '-'),
(7, '2023-11-10 13:03:34', '789@mail.ru', 'Юрий', '$2y$10$6dk5Xwf9l1zyqzoG/H/d7.ylQHboGKZgEcNVin05oPGmDT2G2x9Ry', 'yuri@mail.ru'),
(8, '2023-11-10 13:03:56', '890@mail.ru', 'Елена', '$2y$10$G6RGpCNLK3FZcpyRvdAXuOuFy7.fG07czVXDI4ADXBeP5h97aqT.a', 'Пишите на почту elena@mail.ru'),
(9, '2023-11-10 13:04:50', '900@mail.ru', 'Жанна', '$2y$10$0OAEE/nh6QG8tdmtWTLWve.S7DCWyB1TOIGCjAPtDHwMg7gIcqOCS', 'Почта: zhanna@gmail.com'),
(10, '2023-11-10 13:05:17', '111@mail.ru', 'Максим', '$2y$10$XUuqPR558dda6cfxenzqIuEzKiHlqU2jRi/xFNJKmnTDlzRCXHUeu', 'Telegram: @maskik'),
(11, '2023-11-10 13:23:20', '222@mail.ru', 'Владимир', '$2y$10$Z8dKYAXYapNg0uFxYNFW/eYYav7W2EdXf9llguqGP3kqtlPGr1RGu', 'Почта: volodya@yandex.ru'),
(12, '2023-11-10 13:23:44', '333@mail.ru', 'Евгений', '$2y$10$1oXpJ8/idnTrExygUkO0z.AwB3bQoOy0rpplCK.vmmcQO5/xbjHsa', 'Телега: zhenyok222'),
(13, '2023-11-10 13:24:09', '444@mail.ru', 'Евгения', '$2y$10$kC8hkkYSaWzzZbutA2GIvuhyXqoqUm4k8AsGlnzQIPk4Yr5G3qzTe', 'vk: id=zhenechka117'),
(14, '2023-11-10 13:24:28', '555@mail.ru', 'Андрей', '$2y$10$z6nliKLnZYfCn3SoTEezpewzboCpBwwKRIlMglEfLLFIp5qir04OO', '+7777777777'),
(15, '2023-11-10 13:27:37', '666@mail.ru', 'Валерия', '$2y$10$nKqXNFxpyx9DopRvkZ1rbu4LgH6kPRBJRIb9.S2R90s4JtgCvXu1u', 'inst: valeria777'),
(16, '2023-11-10 16:02:15', 'zhenya@mail.ru', 'Женчик', '$2y$10$TWhoVpPir/1Nrz44MT3iP.IpoxppZ1PCNOPCISXZ921hjuexnFD16', 'а чо');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Bet`
--
ALTER TABLE `Bet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lot_id` (`lot_id`);

--
-- Индексы таблицы `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Индексы таблицы `Lot`
--
ALTER TABLE `Lot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `winner_id` (`winner_id`),
  ADD KEY `category_id` (`category_id`);
ALTER TABLE `Lot` ADD FULLTEXT KEY `lot_ft_search` (`title`,`description`);

--
-- Индексы таблицы `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Bet`
--
ALTER TABLE `Bet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT для таблицы `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Lot`
--
ALTER TABLE `Lot`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Bet`
--
ALTER TABLE `Bet`
  ADD CONSTRAINT `bet_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bet_ibfk_2` FOREIGN KEY (`lot_id`) REFERENCES `Lot` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Lot`
--
ALTER TABLE `Lot`
  ADD CONSTRAINT `lot_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lot_ibfk_2` FOREIGN KEY (`winner_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lot_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `Category` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
