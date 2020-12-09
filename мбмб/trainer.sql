-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 11 2020 г., 16:14
-- Версия сервера: 10.4.8-MariaDB
-- Версия PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `trainer`
--

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE `courses` (
  `SUB_TYPE` int(10) NOT NULL,
  `COURSE_NAME` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `COURSE_SUB_NAME` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `DESCRIPTION` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`SUB_TYPE`, `COURSE_NAME`, `COURSE_SUB_NAME`, `DESCRIPTION`) VALUES
(1, 'English', 'Language', '- Grammar\r\n- Vocabulary\r\n- Use of english'),
(2, 'Russian', 'Language', 'Вводный курс');

-- --------------------------------------------------------

--
-- Структура таблицы `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `theme` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `path` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
  `test_link` varchar(30) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lessons`
--

INSERT INTO `lessons` (`id`, `theme`, `title`, `path`, `test_link`) VALUES
(1, 'Технические и компьютерные термины', 'Технические термины', 'lessons/less_1_1.php', 'lessons/less_1_test.php'),
(2, 'Технические и компьютерные термины', 'Компьютерные термины', 'lessons/less_1_2.php', 'lessons/less_1_test.php');

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `test_path` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
  `test_answ` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tests`
--

INSERT INTO `tests` (`id`, `test_path`, `test_answ`) VALUES
(1, 'lessons/less_1_test.php', 123);

-- --------------------------------------------------------

--
-- Структура таблицы `test_results`
--

CREATE TABLE `test_results` (
  `user_id` int(11) NOT NULL,
  `test_id` int(10) NOT NULL,
  `score` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `test_results`
--

INSERT INTO `test_results` (`user_id`, `test_id`, `score`) VALUES
(0, 2, 0),
(1, 1, 0),
(4, 1, 15),
(11, 1, 10),
(18, 1, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `family_name` varchar(32) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `middle_name` varchar(32) NOT NULL,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `group_id` int(1) NOT NULL,
  `s_score` int(8) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `family_name`, `first_name`, `middle_name`, `login`, `password`, `group_id`, `s_score`) VALUES
(4, 'Студентов', 'Николай', 'Владимирович', 'student@mail.ru', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 3, 15),
(10, 'Лекторов', 'Владислав', 'Сергеевич', 'teach@mail.ru', 'c81e728d9d4c2f636f067f89cc14862c', 2, 0),
(11, 'Новый', 'Петр', 'Сергеевич', 'pro_stud@mail.ru', 'c81e728d9d4c2f636f067f89cc14862c', 3, 10),
(12, 'Администратор', 'Администратор', 'Администратор', 'admin@pochta.ru', 'c4ca4238a0b923820dcc509a6f75849b', 1, 0),
(22, 'Фиксик', 'Нолик', 'Папусов', 'stnew@mail.ru', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 3, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`SUB_TYPE`),
  ADD UNIQUE KEY `SUB_TYPE` (`SUB_TYPE`);

--
-- Индексы таблицы `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`user_id`) USING BTREE;

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
