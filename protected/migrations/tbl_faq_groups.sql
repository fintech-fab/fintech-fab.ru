-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 26 2013 г., 13:26
-- Версия сервера: 5.5.32
-- Версия PHP: 5.5.3-1+debphp.org~precise+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `kreddy`
--

--
-- Дамп данных таблицы `tbl_faq_groups`
--

INSERT INTO `tbl_faq_groups` (`id`, `title`, `sort_order`, `show_site1`, `show_site2`) VALUES
(1, 'Подключение сервиса', 2, 1, 1),
(2, 'О сервисе Кредди', 1, 1, 1),
(3, 'Повторный займ', 3, 1, 1),
(4, 'Возврат займа', 4, 1, 1),
(5, 'Личный кабинет', 5, 1, 1),
(6, 'Пакеты займов', 6, 1, 1),
(7, 'Решение по займу', 7, 1, 1),
(8, 'Безопасность и гарантии', 8, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
