-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Фев 18 2026 г., 05:37
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `demo_service`
--

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id_service` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_service` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted` int(11) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'прием'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id_service`, `name`, `price`, `id_user`, `date_service`, `deleted`, `status`) VALUES
(1, 'Замена масла', 2000.00, 1, '2026-02-12 00:00:00', 0, 'завершен'),
(2, 'Замена масла', 2000.00, 3, '2026-02-12 00:00:00', 0, 'завершен'),
(3, 'Смена топливного фильтра', 400.00, 1, '2026-02-12 13:47:09', 0, 'прием'),
(4, 'Смена топливного фильтра', 400.00, 3, '2026-02-12 13:47:09', 0, 'прием'),
(5, 'Смена воздушного фильтра111', 311.00, 1, '2026-02-12 00:00:00', 0, 'завершен'),
(6, 'Смена воздушного фильтра', 300.00, 3, '2026-02-12 13:47:31', 1, 'прием'),
(7, 'Мойка кузова (с салоном)', 1200.52, 1, '2026-02-14 00:00:00', 1, 'прием'),
(8, 'Замена Вентилятора', 900.00, 3, '2026-02-17 00:00:00', 0, 'прием'),
(9, 'Смена коврика', 110.00, 3, '2026-02-17 00:00:00', 0, 'в работе'),
(10, 'Покраска кузова, цвет мокрый асфальт', 7000.00, 3, '2026-02-18 11:36:09', 0, 'отменен');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `fio` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `fio`, `email`, `password`, `role`) VALUES
(1, 'Иванов И.И', 'ivanov@mail.ru', '1234ivanov!', 'user'),
(2, 'Петров П.П.', 'petrov@mail.ru', '1234petrov!', 'editor'),
(3, 'Сидоров С.В.', 'sidorov@mail.ru', '1234sidorov!', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
