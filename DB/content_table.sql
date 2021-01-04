-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2021 年 1 月 04 日 02:26
-- サーバのバージョン： 10.4.17-MariaDB
-- PHP のバージョン: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gsacf_d07_13`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `content_table`
--

CREATE TABLE `content_table` (
  `id` int(100) NOT NULL,
  `userid` int(100) NOT NULL,
  `accountname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `favorite_userid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shopname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `evaluation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `freetext` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `getday` datetime NOT NULL,
  `class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` decimal(38,36) NOT NULL,
  `lng` decimal(38,35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `content_table`
--

INSERT INTO `content_table` (`id`, `userid`, `accountname`, `favorite_userid`, `shopname`, `area`, `evaluation`, `category`, `freetext`, `getday`, `class`, `lat`, `lng`) VALUES
(45, 3, 'testUser', '4', '深夜特急', '熊本', '★★★★★', 'ランチ', '熊本のカレー', '2021-01-01 23:20:03', 'color', '32.778099900000000000000000000000000000', '130.73017440000000000000000000000000000'),
(48, 4, 'Testuser2', '3', '八木カレー', '熊本', '★★★★★', 'ランチ', 'カレー', '2021-01-03 00:02:35', 'color', '32.803000400000000000000000000000000000', '130.69935450000000000000000000000000000'),
(50, 3, 'testUser', '4', 'ビショップ 博多店', '福岡', '★★★★★', 'ファッション', '古巣', '2021-01-03 13:27:42', 'color', '33.590007600000000000000000000000000000', '130.41969510000000000000000000000000000'),
(55, 4, 'Testuser2', '', '橙書店 orange', '熊本', '★★★★★', 'コーヒー', '本がいっぱい', '2021-01-03 19:34:46', '', '32.797817800000000000000000000000000000', '130.70381920000000000000000000000000000'),
(56, 4, 'Testuser2', '', 'test', '北海道', '★★★★', 'ランチ', 'test', '2021-01-04 10:05:59', '', '0.000000000000000000000000000000000000', '0.00000000000000000000000000000000000');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `content_table`
--
ALTER TABLE `content_table`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `content_table`
--
ALTER TABLE `content_table`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
