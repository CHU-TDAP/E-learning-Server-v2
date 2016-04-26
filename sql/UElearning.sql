-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 26, 2016 at 02:20 AM
-- Server version: 5.7.10
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uelearning`
--

-- --------------------------------------------------------

--
-- Table structure for table `learn_area`
--

CREATE TABLE `learn_area` (
  `AID` int(10) UNSIGNED NOT NULL COMMENT '區域編號',
  `HID` int(10) DEFAULT NULL COMMENT '屬於哪個廳',
  `AFloor` int(3) DEFAULT NULL COMMENT '區域所在樓層',
  `ANum` int(11) DEFAULT NULL COMMENT '區域地圖上的編號',
  `AName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '區域名稱',
  `AMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '區域地圖編號',
  `AIntroduction` tinytext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的所在的區域分類';

--
-- Dumping data for table `learn_area`
--

INSERT INTO `learn_area` (`AID`, `HID`, `AFloor`, `ANum`, `AName`, `AMapID`, `AIntroduction`) VALUES
(1, 1, 1, 1, '眾妙之門', NULL, NULL),
(2, 1, 1, 2, '生命的起源', NULL, NULL),
(3, 1, 1, 3, '生命登上陸地', NULL, NULL),
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
-- Table structure for table `learn_hall`
--

CREATE TABLE `learn_hall` (
  `HID` int(10) NOT NULL,
  `HName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '廳的名稱',
  `HMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '廳的地圖編號',
  `HIntroduction` tinytext COLLATE utf8_unicode_ci COMMENT '廳的簡介'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='區域所在的廳分類';

--
-- Dumping data for table `learn_hall`
--

INSERT INTO `learn_hall` (`HID`, `HName`, `HMapID`, `HIntroduction`) VALUES
(1, '生命科學廳', NULL, '人類從何而來？與自然的關係為何？而自然又是如何發展它的生命？諸多疑惑，自古以來，未曾停歇。\r\n\r\n本廳以大自然的奧祕為總主題，利用13個展示區分別呈現大自然的現象及演化的動態。從');

-- --------------------------------------------------------

--
-- Table structure for table `learn_map`
--

CREATE TABLE `learn_map` (
  `MapID` int(11) NOT NULL,
  `TID` int(11) NOT NULL,
  `Sort` int(10) NOT NULL,
  `Url` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `learn_path`
--

CREATE TABLE `learn_path` (
  `Ti` int(11) NOT NULL,
  `Tj` int(11) NOT NULL,
  `MoveTime` int(4) NOT NULL COMMENT '移動時間(分鐘)',
  `Distance` int(11) NOT NULL COMMENT '距離(M)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的和標的之間';

--
-- Dumping data for table `learn_path`
--

INSERT INTO `learn_path` (`Ti`, `Tj`, `MoveTime`, `Distance`) VALUES
(0, 1, 2, 2),
(0, 2, 2, 2),
(0, 3, 2, 2),
(0, 4, 2, 2),
(0, 5, 2, 2),
(0, 6, 2, 2),
(0, 7, 2, 2),
(0, 8, 2, 2),
(0, 9, 2, 2),
(0, 10, 2, 2),
(0, 11, 2, 2),
(0, 12, 2, 2),
(0, 13, 2, 2),
(0, 14, 2, 2),
(0, 15, 2, 2),
(0, 16, 2, 2),
(1, 2, 2, 2),
(1, 3, 2, 2),
(1, 4, 2, 2),
(1, 5, 2, 2),
(1, 6, 2, 2),
(1, 7, 2, 2),
(1, 8, 2, 2),
(1, 9, 2, 2),
(1, 10, 2, 2),
(1, 11, 2, 2),
(1, 12, 2, 2),
(1, 13, 2, 2),
(1, 14, 2, 2),
(1, 15, 2, 2),
(1, 16, 2, 2),
(2, 3, 2, 2),
(2, 4, 2, 2),
(2, 5, 2, 2),
(2, 6, 2, 2),
(2, 7, 2, 2),
(2, 8, 2, 2),
(2, 9, 2, 2),
(2, 10, 2, 2),
(2, 11, 2, 2),
(2, 12, 2, 2),
(2, 13, 2, 2),
(2, 14, 2, 2),
(2, 15, 2, 2),
(2, 16, 2, 2),
(3, 4, 2, 2),
(3, 5, 2, 2),
(3, 6, 2, 2),
(3, 7, 2, 2),
(3, 8, 2, 2),
(3, 9, 2, 2),
(3, 10, 2, 2),
(3, 11, 2, 2),
(3, 12, 2, 2),
(3, 13, 2, 2),
(3, 14, 2, 2),
(3, 15, 2, 2),
(3, 16, 2, 2),
(4, 5, 2, 2),
(4, 6, 2, 2),
(4, 7, 2, 2),
(4, 8, 2, 2),
(4, 9, 2, 2),
(4, 10, 2, 2),
(4, 11, 2, 2),
(4, 12, 2, 2),
(4, 13, 2, 2),
(4, 14, 2, 2),
(4, 15, 2, 2),
(4, 16, 2, 2),
(5, 6, 2, 2),
(5, 7, 2, 2),
(5, 8, 2, 2),
(5, 9, 2, 2),
(5, 10, 2, 2),
(5, 11, 2, 2),
(5, 12, 2, 2),
(5, 13, 2, 2),
(5, 14, 2, 2),
(5, 15, 2, 2),
(5, 16, 2, 2),
(6, 7, 2, 2),
(6, 8, 2, 2),
(6, 9, 2, 2),
(6, 10, 2, 2),
(6, 11, 2, 2),
(6, 12, 2, 2),
(6, 13, 2, 2),
(6, 14, 2, 2),
(6, 15, 2, 2),
(6, 16, 2, 2),
(7, 8, 2, 2),
(7, 9, 2, 2),
(7, 10, 2, 2),
(7, 11, 2, 2),
(7, 12, 2, 2),
(7, 13, 2, 2),
(7, 14, 2, 2),
(7, 15, 2, 2),
(7, 16, 2, 2),
(8, 9, 2, 2),
(8, 10, 2, 2),
(8, 11, 2, 2),
(8, 12, 2, 2),
(8, 13, 2, 2),
(8, 14, 2, 2),
(8, 15, 2, 2),
(8, 16, 2, 2),
(9, 10, 2, 2),
(9, 11, 2, 2),
(9, 12, 2, 2),
(9, 13, 2, 2),
(9, 14, 2, 2),
(9, 15, 2, 2),
(9, 16, 2, 2),
(10, 11, 2, 2),
(10, 12, 2, 2),
(10, 13, 2, 2),
(10, 14, 2, 2),
(10, 15, 2, 2),
(10, 16, 2, 2),
(11, 12, 2, 2),
(11, 13, 2, 2),
(11, 14, 2, 2),
(11, 15, 2, 2),
(11, 16, 2, 2),
(12, 13, 2, 2),
(12, 14, 2, 2),
(12, 15, 2, 2),
(12, 16, 2, 2),
(13, 14, 2, 2),
(13, 15, 2, 2),
(13, 16, 2, 2),
(14, 15, 2, 2),
(14, 16, 2, 2),
(15, 16, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `learn_target`
--

CREATE TABLE `learn_target` (
  `TID` int(10) UNSIGNED NOT NULL COMMENT '標的內部編號',
  `AID` int(10) DEFAULT NULL COMMENT '標的所在的區域編號',
  `TNum` int(10) DEFAULT NULL COMMENT '標的地圖上的編號',
  `TName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '標的名稱',
  `TMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地圖圖檔名稱',
  `TLearnTime` int(4) UNSIGNED NOT NULL COMMENT '預估此標的應該學習的時間',
  `PLj` int(11) UNSIGNED NOT NULL COMMENT '學習標的的人數限制',
  `Mj` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '目前人數',
  `S` int(11) UNSIGNED DEFAULT NULL COMMENT '學習標的飽和率上限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的資訊';

--
-- Dumping data for table `learn_target`
--

INSERT INTO `learn_target` (`TID`, `AID`, `TNum`, `TName`, `TMapID`, `TLearnTime`, `PLj`, `Mj`, `S`) VALUES
(0, 1, NULL, '入口', '1F.gif', 0, 1000, 0, NULL),
(1, 2, NULL, '歲月的軌跡', 'map_01_02_03.png', 7, 5, 0, 1),
(2, 2, NULL, '岩石中的紀錄', 'map_01_02_03.png', 3, 3, 0, 1),
(3, 2, NULL, '地球的岩石', 'map_01_02_03_04.jpg', 3, 4, 0, 1),
(4, 2, NULL, '細胞', 'map_01_02_03_04.jpg', 3, 5, 0, 1),
(5, 3, NULL, '原始的陸生植物', 'map_05.jpg', 7, 6, 0, 1),
(6, 4, NULL, '沙漠植物', 'map_06_07.jpg', 5, 4, 0, 1),
(7, 4, NULL, '禾本科植物', 'map_06_07.jpg', 4, 3, 0, 1),
(8, 3, NULL, '動物的繁殖', 'map_08.jpg', 5, 5, 0, 1),
(9, 3, NULL, '呼吸系統', 'map_09.jpg', 5, 7, 0, 1),
(10, 3, NULL, '古代的兩棲類', 'map_05.jpg', 5, 4, 0, 1),
(11, 5, NULL, '恐龍時代', 'map_06_07.jpg', 6, 9, 0, 1),
(12, 5, NULL, '竊蛋龍', 'map_09.jpg', 4, 6, 0, 1),
(13, 5, NULL, '巨龍的腳印', 'map_10.jpg', 4, 2, 0, 1),
(14, 6, NULL, '始祖鳥與帶有羽毛的恐龍', 'map_11.jpg', 8, 7, 0, 1),
(15, 9, NULL, '阿法南猿', 'map_12.jpg', 4, 5, 0, 1),
(16, 9, NULL, '探索人類的過去', 'map_13.jpg', 5, 9, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `learn_topic`
--

CREATE TABLE `learn_topic` (
  `ThID` int(10) UNSIGNED NOT NULL,
  `ThName` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '主題名稱',
  `ThLearnTime` int(4) NOT NULL COMMENT '學習此主題要花的總時間(m)',
  `StartTID` int(10) NOT NULL COMMENT '此主題的標的起始點',
  `ThIntroduction` tinytext COLLATE utf8_unicode_ci COMMENT '介紹',
  `ThBuildTime` datetime NOT NULL,
  `ThModifyTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='主題';

--
-- Dumping data for table `learn_topic`
--

INSERT INTO `learn_topic` (`ThID`, `ThName`, `ThLearnTime`, `StartTID`, `ThIntroduction`, `ThBuildTime`, `ThModifyTime`) VALUES
(1, '生命科學', 40, 0, NULL, '2014-10-23 17:21:03', '2014-10-23 17:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `learn_topic_belong`
--

CREATE TABLE `learn_topic_belong` (
  `ThID` int(10) NOT NULL COMMENT '主題編號',
  `TID` int(10) NOT NULL COMMENT '標的編號',
  `Weights` int(3) NOT NULL COMMENT '當次學習主題的某一個學習標的之權重'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='標的和主題之間';

--
-- Dumping data for table `learn_topic_belong`
--

INSERT INTO `learn_topic_belong` (`ThID`, `TID`, `Weights`) VALUES
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
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `MID` int(10) UNSIGNED NOT NULL COMMENT '教材內部編號',
  `TID` int(10) UNSIGNED NOT NULL COMMENT '標的內部編號',
  `MEntity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否為實體教材',
  `MMode` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '教材模式',
  `MUrl` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT '教材檔案路徑'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='教材';

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`MID`, `TID`, `MEntity`, `MMode`, `MUrl`) VALUES
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
-- Table structure for table `material_kind`
--

CREATE TABLE `material_kind` (
  `MkID` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `MkName` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `material_kind`
--

INSERT INTO `material_kind` (`MkID`, `MkName`) VALUES
('normal', '一般教材');

-- --------------------------------------------------------

--
-- Table structure for table `place_info`
--

CREATE TABLE `place_info` (
  `IID` int(11) NOT NULL,
  `IName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `IContent` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `place_info`
--

INSERT INTO `place_info` (`IID`, `IName`, `IContent`) VALUES
(1, '開館時間', '10:00~16:00<br>'),
(2, '票價', '成人票 : 100元<br>兒童票 : 50元<br>'),
(3, '商店', '精品區 : 各式紀念品<br>');

-- --------------------------------------------------------

--
-- Table structure for table `place_map`
--

CREATE TABLE `place_map` (
  `PID` int(11) NOT NULL,
  `PName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `PURL` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `place_map`
--

INSERT INTO `place_map` (`PID`, `PName`, `PURL`) VALUES
(1, '1F', '1F.gif'),
(2, '2F', '2F.gif'),
(3, '1F+2F', '1F+2F.gif');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
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
  `UMemo` tinytext COLLATE utf8_unicode_ci COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者帳號';

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `SaID` int(10) NOT NULL COMMENT '學習活動流水編號',
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
  `MMode` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT '教材模式'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='學習活動';

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_will`
--

CREATE TABLE `user_activity_will` (
  `SwID` int(10) NOT NULL COMMENT '預約學習活動流水編號',
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
  `ModifyTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='預約學習活動';

-- --------------------------------------------------------

--
-- Table structure for table `user_auth_group`
--

CREATE TABLE `user_auth_group` (
  `GID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `GName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `GMemo` tinytext COLLATE utf8_unicode_ci,
  `GBuildTime` datetime NOT NULL,
  `GModifyTime` datetime NOT NULL COMMENT '權限群組資訊修改時間',
  `GAuth_Admin` tinyint(1) NOT NULL,
  `GAuth_ClientAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者群組權限管理分類';

--
-- Dumping data for table `user_auth_group`
--

INSERT INTO `user_auth_group` (`GID`, `GName`, `GMemo`, `GBuildTime`, `GModifyTime`, `GAuth_Admin`, `GAuth_ClientAdmin`) VALUES
('admin', '管理員', NULL, '2014-10-07 16:38:03', '2014-10-23 13:33:32', 0, 0),
('student', '學生', NULL, '2014-10-07 16:38:03', '2014-10-23 13:33:32', 0, 0),
('teacher', '老師', NULL, '2014-10-07 16:38:03', '2014-10-23 13:33:32', 0, 0),
('user', '一般使用者', NULL, '2014-10-24 04:14:52', '2014-10-24 04:14:52', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_class`
--

CREATE TABLE `user_class` (
  `CID` int(11) NOT NULL,
  `CName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CMemo` tinytext COLLATE utf8_unicode_ci,
  `CBuildTime` datetime NOT NULL,
  `CModifyTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者班級分類';

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

CREATE TABLE `user_history` (
  `SID` int(10) NOT NULL,
  `SaID` int(10) NOT NULL,
  `TID` int(10) NOT NULL COMMENT '標的編號',
  `IsEntity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否為實際抵達學習點',
  `In_TargetTime` datetime NOT NULL COMMENT '進入標的時間',
  `Out_TargetTime` datetime DEFAULT NULL COMMENT '離開標的時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者經過哪些標的的紀錄';

-- --------------------------------------------------------

--
-- Table structure for table `user_history_question`
--

CREATE TABLE `user_history_question` (
  `ID` int(11) NOT NULL,
  `SaID` int(10) NOT NULL,
  `TID` int(10) NOT NULL,
  `QDate` datetime NOT NULL,
  `ADate` datetime NOT NULL,
  `QID` int(11) NOT NULL,
  `Ans` int(11) NOT NULL,
  `Correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_history_recommand`
--

CREATE TABLE `user_history_recommand` (
  `SaID` int(10) NOT NULL,
  `Date` datetime NOT NULL,
  `TID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `LID` int(11) NOT NULL,
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Date` datetime NOT NULL,
  `SaID` int(10) DEFAULT NULL,
  `TID` int(10) DEFAULT NULL,
  `ActionGroup` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Encode` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `QID` int(10) DEFAULT NULL,
  `Aswer` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Other` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE `user_session` (
  `UsID` int(10) UNSIGNED NOT NULL,
  `UToken` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '此登入階段的token',
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `UAgent` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用哪個裝置登入',
  `ULoginDate` datetime NOT NULL COMMENT '登入時間',
  `ULogoutDate` datetime DEFAULT NULL COMMENT '登出時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='使用者登入紀錄';

-- --------------------------------------------------------

--
-- Table structure for table `user_target_choose`
--

CREATE TABLE `user_target_choose` (
  `TID` int(3) NOT NULL COMMENT '標的內部編號',
  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者帳號',
  `gradation` int(11) NOT NULL COMMENT '系統推薦標地順序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='推薦';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `learn_area`
--
ALTER TABLE `learn_area`
  ADD PRIMARY KEY (`AID`);

--
-- Indexes for table `learn_hall`
--
ALTER TABLE `learn_hall`
  ADD PRIMARY KEY (`HID`);

--
-- Indexes for table `learn_map`
--
ALTER TABLE `learn_map`
  ADD PRIMARY KEY (`MapID`),
  ADD KEY `TID` (`TID`);

--
-- Indexes for table `learn_path`
--
ALTER TABLE `learn_path`
  ADD PRIMARY KEY (`Ti`,`Tj`);

--
-- Indexes for table `learn_target`
--
ALTER TABLE `learn_target`
  ADD PRIMARY KEY (`TID`);

--
-- Indexes for table `learn_topic`
--
ALTER TABLE `learn_topic`
  ADD PRIMARY KEY (`ThID`);

--
-- Indexes for table `learn_topic_belong`
--
ALTER TABLE `learn_topic_belong`
  ADD PRIMARY KEY (`TID`,`ThID`),
  ADD KEY `TID` (`TID`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`MID`);

--
-- Indexes for table `material_kind`
--
ALTER TABLE `material_kind`
  ADD PRIMARY KEY (`MkID`);

--
-- Indexes for table `place_info`
--
ALTER TABLE `place_info`
  ADD PRIMARY KEY (`IID`);

--
-- Indexes for table `place_map`
--
ALTER TABLE `place_map`
  ADD PRIMARY KEY (`PID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UID`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`SaID`);

--
-- Indexes for table `user_activity_will`
--
ALTER TABLE `user_activity_will`
  ADD PRIMARY KEY (`SwID`);

--
-- Indexes for table `user_auth_group`
--
ALTER TABLE `user_auth_group`
  ADD PRIMARY KEY (`GID`);

--
-- Indexes for table `user_class`
--
ALTER TABLE `user_class`
  ADD PRIMARY KEY (`CID`);

--
-- Indexes for table `user_history`
--
ALTER TABLE `user_history`
  ADD PRIMARY KEY (`SID`);

--
-- Indexes for table `user_history_question`
--
ALTER TABLE `user_history_question`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_history_recommand`
--
ALTER TABLE `user_history_recommand`
  ADD PRIMARY KEY (`SaID`,`Date`,`TID`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`LID`);

--
-- Indexes for table `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`UsID`),
  ADD UNIQUE KEY `UToken` (`UToken`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `learn_area`
--
ALTER TABLE `learn_area`
  MODIFY `AID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '區域編號', AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `learn_hall`
--
ALTER TABLE `learn_hall`
  MODIFY `HID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `learn_map`
--
ALTER TABLE `learn_map`
  MODIFY `MapID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `learn_topic`
--
ALTER TABLE `learn_topic`
  MODIFY `ThID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `MID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '教材內部編號', AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `SaID` int(10) NOT NULL AUTO_INCREMENT COMMENT '學習活動流水編號';
--
-- AUTO_INCREMENT for table `user_activity_will`
--
ALTER TABLE `user_activity_will`
  MODIFY `SwID` int(10) NOT NULL AUTO_INCREMENT COMMENT '預約學習活動流水編號';
--
-- AUTO_INCREMENT for table `user_class`
--
ALTER TABLE `user_class`
  MODIFY `CID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_history`
--
ALTER TABLE `user_history`
  MODIFY `SID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_history_question`
--
ALTER TABLE `user_history_question`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `LID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_session`
--
ALTER TABLE `user_session`
  MODIFY `UsID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
