-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 05 2013 г., 10:41
-- Версия сервера: 5.5.32
-- Версия PHP: 5.4.17-1~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `kreddy`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_bottom_tabs`
--

CREATE TABLE IF NOT EXISTS `tbl_bottom_tabs` (
  `tab_id` int(11) NOT NULL AUTO_INCREMENT,
  `tab_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tab_title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tab_content` text COLLATE utf8_unicode_ci NOT NULL,
  `tab_order` int(11) NOT NULL DEFAULT '999999999',
  PRIMARY KEY (`tab_id`),
  UNIQUE KEY `tab_name` (`tab_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_client`
--

CREATE TABLE IF NOT EXISTS `tbl_client` (
  `client_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` char(10) DEFAULT NULL COMMENT 'Телефон',
  `job_phone` char(10) DEFAULT NULL COMMENT 'Рабочий телефон',
  `telecoms_operator` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Мобильный оператор',
  `first_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Имя',
  `last_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Фамилия',
  `third_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Отчество',
  `sex` tinyint(1) unsigned DEFAULT NULL COMMENT 'Пол',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT 'День рождения',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Email',
  `description` text NOT NULL COMMENT 'Комментарии',
  `passport_series` char(4) NOT NULL DEFAULT '' COMMENT 'Серия паспорта',
  `passport_number` char(6) NOT NULL DEFAULT '' COMMENT 'Номер паспорта',
  `passport_issued` varchar(255) NOT NULL COMMENT 'Кем выдан паспорт',
  `passport_code` char(7) NOT NULL COMMENT 'Код подразделения',
  `passport_date` date NOT NULL COMMENT 'Дата выдачи паспорта',
  `document` varchar(100) NOT NULL COMMENT 'Второй документ',
  `document_number` varchar(30) NOT NULL COMMENT 'Номер второго документа',
  `address_reg_region` varchar(100) NOT NULL COMMENT 'Регион',
  `address_reg_city` varchar(100) NOT NULL COMMENT 'Населенный пункт',
  `address_reg_address` varchar(255) NOT NULL COMMENT 'Адрес',
  `relatives_one_fio` varchar(255) NOT NULL COMMENT 'Контактное лицо',
  `relatives_one_phone` char(10) NOT NULL COMMENT 'Телефон контактного лица',
  `friends_fio` varchar(255) NOT NULL COMMENT 'ФИО друга',
  `friends_phone` char(10) NOT NULL COMMENT 'Телефон друга',
  `job_company` varchar(100) NOT NULL COMMENT 'Компания',
  `job_position` varchar(100) NOT NULL COMMENT 'Должность',
  `job_time` varchar(20) NOT NULL COMMENT 'Стаж работы',
  `job_monthly_income` varchar(30) NOT NULL COMMENT 'Месячный доход',
  `job_monthly_outcome` varchar(30) NOT NULL COMMENT 'Месячный расход',
  `have_past_credit` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Раньше были кредиты',
  `secret_question` tinyint(1) NOT NULL COMMENT 'Секретный вопрос',
  `secret_answer` varchar(255) NOT NULL COMMENT 'Ответ на вопрос',
  `numeric_code` int(11) NOT NULL COMMENT 'Цифровой код',
  `product` tinyint(1) NOT NULL,
  `get_way` tinyint(1) NOT NULL,
  `options` text NOT NULL COMMENT 'Сериализованные дополнительные данные',
  `complete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Флаг успешного заполнения анкеты',
  `dt_add` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата добавления',
  `dt_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата обновления',
  `flag_identified` tinyint(1) NOT NULL COMMENT 'Клиент прошел видеоидентификацию',
  `flag_sms_confirmed` tinyint(1) NOT NULL COMMENT 'Телефон подтвержден по СМС',
  `flag_processed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Обработан системой Кредди',
  `flag_archived` tinyint(1) NOT NULL COMMENT 'Флаг архивации записи',
  PRIMARY KEY (`client_id`),
  KEY `phone` (`phone`),
  KEY `passport` (`passport_series`,`passport_number`),
  KEY `sex` (`sex`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Анкетные данные клиента' AUTO_INCREMENT=205 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_footer_links`
--

CREATE TABLE IF NOT EXISTS `tbl_footer_links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_order` int(11) NOT NULL,
  `link_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `link_title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `link_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link_content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`link_id`),
  UNIQUE KEY `link_name` (`link_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_pages`
--

CREATE TABLE IF NOT EXISTS `tbl_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID страницы',
  `page_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Имя страницы для вызова по имени',
  `page_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Заголовок страницы для <title>',
  `page_content` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Контент страницы',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_name` (`page_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
