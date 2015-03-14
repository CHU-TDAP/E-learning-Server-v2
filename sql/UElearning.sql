-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生日期: 2015 年 02 月 06 日 09:46
-- 伺服器版本: 5.6.13
-- PHP 版本: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫: `uelearning`
--
CREATE DATABASE IF NOT EXISTS `uelearning` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `uelearning`;

-- --------------------------------------------------------

--
-- 表的結構 `chu__AGroup`
--

CREATE TABLE IF NOT EXISTS `chu__AGroup` (
  `GID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `GName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `GMemo` tinytext COLLATE utf8_unicode_ci,
  `GBuildTime` datetime NOT NULL,
  `GModifyTime` datetime NOT NULL COMMENT '權限群組資訊修改時間',
  `GAuth_Admin` tinyint(1) NOT NULL,
  `GAuth_ClientAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`GID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者群組權限管理分類';

--
-- 轉存資料表中的資料 `chu__AGroup`
--

INSERT INTO `chu__AGroup` (`GID`, `GName`, `GMemo`, `GBuildTime`, `GModifyTime`, `GAuth_Admin`, `GAuth_ClientAdmin`) VALUES
('admin', '管理員', NULL, '2014-10-07 16:38:03', '2014-10-23 13:33:32', 0, 0),
('student', '學生', NULL, '2014-10-07 16:38:03', '2014-10-23 13:33:32', 0, 0),
('teacher', '老師', NULL, '2014-10-07 16:38:03', '2014-10-23 13:33:32', 0, 0),
('user', '一般使用者', NULL, '2014-10-24 04:14:52', '2014-10-24 04:14:52', 0, 1);

-- --------------------------------------------------------

--
-- 表的結構 `chu__Area`
--

CREATE TABLE IF NOT EXISTS `chu__Area` (
  `AID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '區域編號',
  `HID` int(10) DEFAULT NULL COMMENT '屬於哪個廳',
  `AFloor` int(3) DEFAULT NULL COMMENT '區域所在樓層',
  `ANum` int(11) DEFAULT NULL COMMENT '區域地圖上的編號',
  `AName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '區域名稱',
  `AMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '區域地圖編號',
  `AIntroduction` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`AID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的所在的區域分類' AUTO_INCREMENT=15 ;

--
-- 轉存資料表中的資料 `chu__Area`
--

INSERT INTO `chu__Area` (`AID`, `HID`, `AFloor`, `ANum`, `AName`, `AMapID`, `AIntroduction`) VALUES
(1, 1, 1, 1, '眾妙之門', NULL, NULL),
(2, 1, 1, 2, '生命的起源', NULL, NULL),
(3, 1, 1, 3, '生命上的陸地', NULL, NULL),
(4, 1, 1, 4, '植物的演化', NULL, NULL),
(5, 1, 1, 5, '恐龍時代', NULL, NULL),
(6, 1, 2, 1, '生命征服天空', NULL, NULL),
(7, 1, 2, 2, '滅絕', NULL, NULL),
(8, 1, 2, 3, '哺乳類的演化與適應', NULL, NULL),
(9, 1, 2, 4, '人類的故事', NULL, NULL),
(10, 1, 2, 5, '我們的身體一生老病', NULL, NULL),
(11, 1, -1, 1, '數與形', NULL, NULL),
(12, 1, -1, 2, '生彩色世界', NULL, NULL),
(13, 1, -1, 3, '大自然的聲音', NULL, NULL),
(14, 1, -1, 4, '多用途劇場', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的結構 `chu__CGroup`
--

CREATE TABLE IF NOT EXISTS `chu__CGroup` (
  `CID` int(11) NOT NULL AUTO_INCREMENT,
  `CName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CMemo` tinytext COLLATE utf8_unicode_ci,
  `CBuildTime` datetime NOT NULL,
  `CModifyTime` datetime NOT NULL,
  PRIMARY KEY (`CID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者班級分類' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的結構 `chu__Edge`
--

CREATE TABLE IF NOT EXISTS `chu__Edge` (
  `Ti` int(11) NOT NULL,
  `Tj` int(11) NOT NULL,
  `MoveTime` int(4) NOT NULL COMMENT '移動時間(分鐘)',
  `Distance` int(11) NOT NULL COMMENT '距離(M)',
  PRIMARY KEY (`Ti`,`Tj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的和標的之間';

--
-- 轉存資料表中的資料 `chu__Edge`
--

INSERT INTO `chu__Edge` (`Ti`, `Tj`, `MoveTime`, `Distance`) VALUES
(0, 1, 0, 2),
(0, 2, 1, 2),
(0, 3, 1, 2),
(0, 4, 1, 2),
(0, 5, 2, 3),
(0, 6, 2, 3),
(0, 7, 3, 3),
(0, 8, 3, 4),
(0, 9, 3, 4),
(0, 10, 4, 4),
(0, 11, 4, 5),
(0, 12, 5, 5),
(0, 13, 5, 6),
(0, 14, 6, 6),
(0, 15, 6, 7),
(1, 2, 1, 1),
(1, 3, 1, 1),
(1, 4, 1, 1),
(1, 5, 2, 1),
(1, 6, 2, 1),
(1, 7, 2, 1),
(1, 8, 2, 1),
(1, 9, 2, 1),
(1, 10, 3, 1),
(1, 11, 3, 1),
(1, 12, 4, 1),
(1, 13, 4, 1),
(1, 14, 4, 1),
(1, 15, 6, 1),
(2, 1, 1, 2),
(2, 3, 1, 2),
(2, 4, 1, 2),
(2, 5, 1, 2),
(2, 6, 1, 2),
(2, 7, 1, 2),
(2, 8, 1, 2),
(2, 9, 1, 2),
(2, 10, 2, 2),
(2, 11, 2, 2),
(2, 12, 3, 2),
(2, 13, 3, 2),
(2, 14, 3, 2),
(2, 15, 5, 2),
(3, 1, 1, 3),
(3, 2, 1, 3),
(3, 4, 1, 3),
(3, 5, 1, 3),
(3, 6, 1, 3),
(3, 7, 1, 3),
(3, 8, 1, 3),
(3, 9, 1, 3),
(3, 10, 2, 3),
(3, 11, 2, 3),
(3, 12, 3, 3),
(3, 13, 3, 3),
(3, 14, 3, 3),
(3, 15, 5, 3),
(4, 1, 1, 4),
(4, 2, 1, 4),
(4, 3, 1, 4),
(4, 5, 1, 4),
(4, 6, 1, 4),
(4, 7, 1, 4),
(4, 8, 1, 4),
(4, 9, 1, 4),
(4, 10, 2, 4),
(4, 11, 2, 4),
(4, 12, 3, 4),
(4, 13, 3, 4),
(4, 14, 3, 4),
(4, 15, 5, 4),
(5, 1, 2, 2),
(5, 2, 1, 2),
(5, 3, 1, 2),
(5, 4, 1, 2),
(5, 6, 1, 2),
(5, 7, 1, 2),
(5, 8, 1, 2),
(5, 9, 1, 2),
(5, 10, 1, 2),
(5, 11, 1, 2),
(5, 12, 2, 2),
(5, 13, 2, 2),
(5, 14, 2, 2),
(5, 15, 4, 2),
(6, 1, 2, 3),
(6, 2, 1, 3),
(6, 3, 1, 3),
(6, 4, 1, 3),
(6, 5, 1, 3),
(6, 7, 1, 3),
(6, 8, 1, 3),
(6, 9, 1, 3),
(6, 10, 1, 3),
(6, 11, 1, 3),
(6, 12, 2, 3),
(6, 13, 2, 3),
(6, 14, 2, 3),
(6, 15, 4, 3),
(7, 1, 2, 6),
(7, 2, 1, 6),
(7, 3, 1, 6),
(7, 4, 1, 6),
(7, 5, 1, 6),
(7, 6, 1, 6),
(7, 8, 1, 6),
(7, 9, 1, 6),
(7, 10, 1, 6),
(7, 11, 1, 6),
(7, 12, 2, 6),
(7, 13, 2, 6),
(7, 14, 2, 6),
(7, 15, 4, 6),
(8, 1, 2, 5),
(8, 2, 1, 5),
(8, 3, 1, 5),
(8, 4, 1, 5),
(8, 5, 1, 5),
(8, 6, 1, 5),
(8, 7, 1, 5),
(8, 9, 1, 5),
(8, 10, 1, 5),
(8, 11, 1, 5),
(8, 12, 2, 5),
(8, 13, 2, 5),
(8, 14, 2, 5),
(8, 15, 4, 5),
(9, 1, 2, 4),
(9, 2, 1, 4),
(9, 3, 1, 4),
(9, 4, 1, 4),
(9, 5, 1, 4),
(9, 6, 1, 4),
(9, 7, 1, 4),
(9, 8, 1, 4),
(9, 10, 1, 4),
(9, 11, 1, 4),
(9, 12, 2, 4),
(9, 13, 2, 4),
(9, 14, 2, 4),
(9, 15, 4, 4),
(10, 1, 3, 7),
(10, 2, 2, 7),
(10, 3, 2, 7),
(10, 4, 2, 7),
(10, 5, 1, 7),
(10, 6, 1, 7),
(10, 7, 1, 7),
(10, 8, 1, 7),
(10, 9, 1, 7),
(10, 11, 1, 7),
(10, 12, 1, 7),
(10, 13, 1, 7),
(10, 14, 1, 7),
(10, 15, 3, 7),
(11, 1, 3, 8),
(11, 2, 2, 8),
(11, 3, 2, 8),
(11, 4, 2, 8),
(11, 5, 1, 8),
(11, 6, 1, 8),
(11, 7, 1, 8),
(11, 8, 1, 8),
(11, 9, 1, 8),
(11, 10, 1, 8),
(11, 12, 1, 8),
(11, 13, 1, 8),
(11, 14, 1, 8),
(11, 15, 3, 8),
(12, 1, 4, 6),
(12, 2, 3, 6),
(12, 3, 3, 6),
(12, 4, 3, 6),
(12, 5, 2, 6),
(12, 6, 2, 6),
(12, 7, 2, 6),
(12, 8, 2, 6),
(12, 9, 2, 6),
(12, 10, 1, 6),
(12, 11, 1, 6),
(12, 13, 1, 6),
(12, 14, 1, 6),
(12, 15, 2, 6),
(13, 1, 4, 8),
(13, 2, 3, 8),
(13, 3, 3, 8),
(13, 4, 3, 8),
(13, 5, 2, 8),
(13, 6, 2, 8),
(13, 7, 2, 8),
(13, 8, 2, 8),
(13, 9, 2, 8),
(13, 10, 1, 8),
(13, 11, 1, 8),
(13, 12, 1, 8),
(13, 14, 1, 8),
(13, 15, 2, 8),
(14, 1, 4, 7),
(14, 2, 3, 7),
(14, 3, 3, 7),
(14, 4, 3, 7),
(14, 5, 2, 7),
(14, 6, 2, 7),
(14, 7, 2, 7),
(14, 8, 2, 7),
(14, 9, 2, 7),
(14, 10, 1, 7),
(14, 11, 1, 7),
(14, 12, 1, 7),
(14, 13, 1, 7),
(14, 15, 1, 7),
(15, 1, 6, 9),
(15, 2, 5, 9),
(15, 3, 5, 9),
(15, 4, 5, 9),
(15, 5, 4, 9),
(15, 6, 4, 9),
(15, 7, 4, 9),
(15, 8, 4, 9),
(15, 9, 4, 9),
(15, 10, 3, 9),
(15, 11, 3, 9),
(15, 12, 2, 9),
(15, 13, 2, 9),
(15, 14, 1, 9);

-- --------------------------------------------------------

--
-- 表的結構 `chu__Hall`
--

CREATE TABLE IF NOT EXISTS `chu__Hall` (
  `HID` int(10) NOT NULL AUTO_INCREMENT,
  `HName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '廳的名稱',
  `HMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '廳的地圖編號',
  `HIntroduction` tinytext COLLATE utf8_unicode_ci COMMENT '廳的簡介',
  PRIMARY KEY (`HID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='區域所在的廳分類' AUTO_INCREMENT=2 ;

--
-- 轉存資料表中的資料 `chu__Hall`
--

INSERT INTO `chu__Hall` (`HID`, `HName`, `HMapID`, `HIntroduction`) VALUES
(1, '生命科學廳', NULL, '人類從何而來？與自然的關係為何？而自然又是如何發展它的生命？諸多疑惑，自古以來，未曾停歇。\r\n\r\n本廳以大自然的奧祕為總主題，利用13個展示區分別呈現大自然的現象及演化的動態。從');

-- --------------------------------------------------------

--
-- 表的結構 `chu__Log`
--

CREATE TABLE IF NOT EXISTS `chu__Log` (
  `LID` int(11) NOT NULL AUTO_INCREMENT,
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Date` datetime NOT NULL,
  `SaID` int(10) DEFAULT NULL,
  `TID` int(10) DEFAULT NULL,
  `ActionGroup` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Encode` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `QID` int(10) DEFAULT NULL,
  `Aswer` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Other` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`LID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的結構 `chu__Material`
--

CREATE TABLE IF NOT EXISTS `chu__Material` (
  `MID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '教材內部編號',
  `TID` int(10) unsigned NOT NULL COMMENT '標的內部編號',
  `MEntity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否為實體教材',
  `MMode` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '教材模式',
  `MUrl` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT '教材檔案路徑',
  PRIMARY KEY (`MID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='教材' AUTO_INCREMENT=31 ;

--
-- 轉存資料表中的資料 `chu__Material`
--

INSERT INTO `chu__Material` (`MID`, `TID`, `MEntity`, `MMode`, `MUrl`) VALUES
(1, 1, 1, 'normal', '01.html'),
(2, 2, 1, 'normal', '02.html'),
(3, 3, 1, 'normal', '03.html'),
(4, 4, 1, 'normal', '04.html'),
(5, 5, 1, 'normal', '05.html'),
(6, 6, 1, 'normal', '06.html'),
(7, 7, 1, 'normal', '07.html'),
(8, 8, 1, 'normal', '08.html'),
(9, 9, 1, 'normal', '09.html'),
(10, 10, 1, 'normal', '10.html'),
(11, 11, 1, 'normal', '11.html'),
(12, 12, 1, 'normal', '12.html'),
(13, 13, 1, 'normal', '13.html'),
(14, 14, 1, 'normal', '14.html'),
(15, 15, 1, 'normal', '15.html'),
(16, 1, 0, 'normal', '16.html'),
(17, 2, 0, 'normal', '17.html'),
(18, 3, 0, 'normal', '18.html'),
(19, 4, 0, 'normal', '19.html'),
(20, 5, 0, 'normal', '20.html'),
(21, 6, 0, 'normal', '21.html'),
(22, 7, 0, 'normal', '22.html'),
(23, 8, 0, 'normal', '23.html'),
(24, 9, 0, 'normal', '24.html'),
(25, 10, 0, 'normal', '25.html'),
(26, 11, 0, 'normal', '26.html'),
(27, 12, 0, 'normal', '27.html'),
(28, 13, 0, 'normal', '28.html'),
(29, 14, 0, 'normal', '29.html'),
(30, 15, 0, 'normal', '30.html');

-- --------------------------------------------------------

--
-- 表的結構 `chu__MaterialKind`
--

CREATE TABLE IF NOT EXISTS `chu__MaterialKind` (
  `MkID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `MkName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`MkID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 轉存資料表中的資料 `chu__MaterialKind`
--

INSERT INTO `chu__MaterialKind` (`MkID`, `MkName`) VALUES
('normal', '一般教材');

-- --------------------------------------------------------

--
-- 表的結構 `chu__PlaceInfo`
--

CREATE TABLE IF NOT EXISTS `chu__PlaceInfo` (
  `IID` int(11) NOT NULL,
  `IName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `IContent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 轉存資料表中的資料 `chu__PlaceInfo`
--

INSERT INTO `chu__PlaceInfo` (`IID`, `IName`, `IContent`) VALUES
(1, '開館時間', '10:00~16:00<br>'),
(2, '票價', '成人票 : 100元<br>兒童票 : 50元<br>'),
(3, '商店', '精品區 : 各式紀念品<br>');

-- --------------------------------------------------------

--
-- 表的結構 `chu__PlaceMap`
--

CREATE TABLE IF NOT EXISTS `chu__PlaceMap` (
  `PID` int(11) NOT NULL,
  `PName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `PURL` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 轉存資料表中的資料 `chu__PlaceMap`
--

INSERT INTO `chu__PlaceMap` (`PID`, `PName`, `PURL`) VALUES
(1, '1F', '1F.gif'),
(2, '2F', '2F.gif'),
(3, '1F+2F', '1F+2F.gif');

-- --------------------------------------------------------

--
-- 表的結構 `chu__Recommand`
--

CREATE TABLE IF NOT EXISTS `chu__Recommand` (
  `TID` int(3) NOT NULL COMMENT '標的內部編號',
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者帳號',
  `gradation` int(11) NOT NULL COMMENT '系統推薦標地順序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='推薦';

-- --------------------------------------------------------

--
-- 表的結構 `chu__Study`
--

CREATE TABLE IF NOT EXISTS `chu__Study` (
  `SID` int(10) NOT NULL AUTO_INCREMENT,
  `SaID` int(10) NOT NULL,
  `TID` int(10) NOT NULL COMMENT '標的編號',
  `IsEntity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否為實際抵達學習點',
  `In_TargetTime` datetime NOT NULL COMMENT '進入標的時間',
  `Out_TargetTime` datetime DEFAULT NULL COMMENT '離開標的時間',
  PRIMARY KEY (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者經過哪些標的的紀錄' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的結構 `chu__StudyActivity`
--

CREATE TABLE IF NOT EXISTS `chu__StudyActivity` (
  `SaID` int(10) NOT NULL AUTO_INCREMENT COMMENT '學習活動流水編號',
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者ID',
  `ThID` int(10) NOT NULL COMMENT '主題編號',
  `StartTime` datetime NOT NULL COMMENT '開始學習時間',
  `EndTime` datetime DEFAULT NULL COMMENT '結束學習時間（學習中為NULL）',
  `LearnTime` int(4) NOT NULL COMMENT '預定學習所需時間',
  `Delay` int(11) NOT NULL DEFAULT '0' COMMENT '時間延長',
  `TimeForce` tinyint(1) NOT NULL DEFAULT '0' COMMENT '學習時間已過是否強制中止學習',
  `LMode` int(2) NOT NULL DEFAULT '1' COMMENT '學習導引模式',
  `LModeForce` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否拒絕前往非推薦點進行學習',
  `EnableVirtual` tinyint(1) NOT NULL DEFAULT '0',
  `MMode` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT '教材模式',
  PRIMARY KEY (`SaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='學習活動' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的結構 `chu__StudyWill`
--

CREATE TABLE IF NOT EXISTS `chu__StudyWill` (
  `SwID` int(10) NOT NULL AUTO_INCREMENT COMMENT '預約學習活動流水編號',
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `ThID` int(10) NOT NULL COMMENT '主題編號',
  `StartTime` datetime NOT NULL COMMENT '預約生效時間',
  `ExpiredTime` datetime DEFAULT NULL COMMENT '過期時間',
  `LearnTime` int(4) NOT NULL,
  `TimeForce` tinyint(1) NOT NULL DEFAULT '1' COMMENT '學習時間已過是否強制中止學習',
  `LMode` int(2) NOT NULL DEFAULT '1' COMMENT '學習導引模式',
  `LModeForce` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否拒絕前往非推薦點進行學習',
  `EnableVirtual` tinyint(1) NOT NULL DEFAULT '0',
  `MMode` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT '教材模式',
  `Lock` tinyint(1) NOT NULL DEFAULT '1' COMMENT '鎖定不讓學生更改',
  `BuildTime` datetime NOT NULL,
  `ModifyTime` datetime NOT NULL,
  PRIMARY KEY (`SwID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='預約學習活動' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的結構 `chu__Target`
--

CREATE TABLE IF NOT EXISTS `chu__Target` (
  `TID` int(10) unsigned NOT NULL COMMENT '標的內部編號',
  `AID` int(10) DEFAULT NULL COMMENT '標的所在的區域編號',
  `TNum` int(10) DEFAULT NULL COMMENT '標的地圖上的編號',
  `TName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '標的名稱',
  `TMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地圖圖檔名稱',
  `TLearnTime` int(4) unsigned NOT NULL COMMENT '預估此標的應該學習的時間',
  `PLj` int(11) unsigned NOT NULL COMMENT '學習標的的人數限制',
  `Mj` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '目前人數',
  `S` int(11) unsigned DEFAULT NULL COMMENT '學習標的飽和率上限',
  PRIMARY KEY (`TID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的資訊';

--
-- 轉存資料表中的資料 `chu__Target`
--

INSERT INTO `chu__Target` (`TID`, `AID`, `TNum`, `TName`, `TMapID`, `TLearnTime`, `PLj`, `Mj`, `S`) VALUES
(0, 1, NULL, '入口', '1F.gif', 0, 1000000000, 0, NULL),
(1, 1, NULL, '含有生物遺跡的岩石', 'map_01_02_03.png', 7, 2, 0, 1),
(2, 1, NULL, '岩石中的紀錄', 'map_01_02_03.png', 8, 2, 0, 1),
(3, 4, NULL, '生命在水中的演化', 'map_01_02_03.png', 3, 2, 0, 1),
(4, 4, NULL, '最早的森林', 'map_04.jpg', 3, 2, 0, 1),
(5, 3, NULL, '古代的兩棲類', 'map_05.jpg', 5, 2, 0, 1),
(6, 5, NULL, '恐龍時代', 'map_06.jpg', 6, 2, 0, 1),
(7, 5, NULL, '蒙古的恐龍', 'map_07.jpg', 4, 2, 0, 1),
(8, 5, NULL, '恐龍再現', 'map_08.jpg', 4, 2, 0, 1),
(9, 5, NULL, '竊蛋龍', 'map_09.jpg', 4, 2, 0, 1),
(10, 5, NULL, '巨龍的腳印', 'map_10.jpg', 4, 2, 0, 1),
(11, 6, NULL, '始祖鳥與帶有羽毛的恐龍', 'map_11.jpg', 8, 2, 0, 1),
(12, 8, NULL, '阿法南猿', 'map_12.jpg', 4, 2, 0, 1),
(13, 9, NULL, '探索人類的過去', 'map_13.jpg', 5, 1, 0, 1),
(14, 9, NULL, '周口店北京人', 'map_14.jpg', 3, 2, 0, 1),
(15, 10, NULL, '木乃伊', 'map_15.jpg', 8, 2, 0, 1);

-- --------------------------------------------------------

--
-- 表的結構 `chu__TBelong`
--

CREATE TABLE IF NOT EXISTS `chu__TBelong` (
  `ThID` int(10) NOT NULL COMMENT '主題編號',
  `TID` int(10) NOT NULL COMMENT '標的編號',
  `Weights` int(3) NOT NULL COMMENT '當次學習主題的某一個學習標的之權重',
  PRIMARY KEY (`TID`,`ThID`),
  KEY `TID` (`TID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的和主題之間';

--
-- 轉存資料表中的資料 `chu__TBelong`
--

INSERT INTO `chu__TBelong` (`ThID`, `TID`, `Weights`) VALUES
(1, 0, 0),
(1, 1, 1),
(1, 2, 2),
(1, 3, 4),
(1, 4, 7),
(1, 5, 4),
(1, 6, 5),
(1, 7, 2),
(1, 8, 6),
(1, 9, 7),
(1, 10, 9),
(1, 11, 6),
(1, 12, 4),
(1, 13, 5),
(1, 14, 5),
(1, 15, 9);

-- --------------------------------------------------------

--
-- 表的結構 `chu__Theme`
--

CREATE TABLE IF NOT EXISTS `chu__Theme` (
  `ThID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ThName` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '主題名稱',
  `ThLearnTime` int(4) NOT NULL COMMENT '學習此主題要花的總時間(m)',
  `StartTID` int(10) NOT NULL COMMENT '此主題的標的起始點',
  `ThIntroduction` tinytext COLLATE utf8_unicode_ci COMMENT '介紹',
  `ThBuildTime` datetime NOT NULL,
  `ThModifyTime` datetime NOT NULL,
  PRIMARY KEY (`ThID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='主題' AUTO_INCREMENT=3 ;

--
-- 轉存資料表中的資料 `chu__Theme`
--

INSERT INTO `chu__Theme` (`ThID`, `ThName`, `ThLearnTime`, `StartTID`, `ThIntroduction`, `ThBuildTime`, `ThModifyTime`) VALUES
(1, '生命科學', 40, 0, NULL, '2014-10-23 17:21:03', '2014-10-23 17:21:03');

-- --------------------------------------------------------

--
-- 表的結構 `chu__User`
--

CREATE TABLE IF NOT EXISTS `chu__User` (
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者帳號',
  `UPassword` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
  `GID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者群組',
  `CID` int(11) DEFAULT NULL COMMENT '使用者班級',
  `UEnabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '帳號啟用狀態',
  `UBuildTime` datetime NOT NULL COMMENT '帳號建立時間',
  `UModifyTime` datetime NOT NULL COMMENT '帳號資訊修改時間',
  `LMode` int(2) DEFAULT NULL COMMENT '學習導引模式',
  `MMode` varchar(25) COLLATE utf8_unicode_ci DEFAULT 'normal' COMMENT '教材模式',
  `UEnable_NoAppoint` tinyint(1) NOT NULL DEFAULT '1' COMMENT '開放非預約學習',
  `UNickname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暱稱',
  `URealName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真實姓名',
  `UEmail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用者email',
  `UMemo` tinytext COLLATE utf8_unicode_ci COMMENT '備註',
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者帳號';

-- --------------------------------------------------------

--
-- 表的結構 `chu__UserSession`
--

CREATE TABLE IF NOT EXISTS `chu__UserSession` (
  `UsID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UToken` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '此登入階段的token',
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `UAgent` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用哪個裝置登入',
  `ULoginDate` datetime NOT NULL COMMENT '登入時間',
  `ULogoutDate` datetime DEFAULT NULL COMMENT '登出時間',
  PRIMARY KEY (`UsID`),
  UNIQUE KEY `UToken` (`UToken`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者登入紀錄' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
