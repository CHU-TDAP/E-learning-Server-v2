<?php
/**
 * DBAdmin.php
 *
 * 此檔案針對整體資料庫的功能，像是建立此資料庫、建立表格、清空...等等
 */

namespace UElearning\Database;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

/**
 * 資料庫整體管理
 *
 * 對資料庫的管理操作。
 *
 * 建立資料庫連結，若直接使用`config.php`設定的參數，使用以下即可:
 *
 *     require_once __DIR__.'/config.php';
 *     require_once UELEARNING_LIB_ROOT.'Database/DBAdmin.php';
 *     use UElearning\Database;
 *     use UElearning\Exception;
 *     $db = new Database\DBAdmin();
 *
 * 若要自行指定連結參數，請使用:
 *
 *     use UElearning\Database;
 *     $db = new Database\DBAdmin(array(
 *         'type' => 'mysql',
 *         'host' => 'localhost',
 *         'port' => '3306',
 *         'user' => 'user',
 *         'password' => '123456',
 *         'dbname' => 'chu-elearning',
 *         'prefix' => 'chu_'
 *         ));
 *
 * 可參考以下範例:
 *
 *     require_once __DIR__.'/config.php';
 *     require_once UELEARNING_LIB_ROOT.'Database/DBAdmin.php';
 *     use UElearning\Database;
 *
 *     try {
 *         $db = new Database\DBAdmin();
 *     } catch (Database\DatabaseNoSupportException $e) {
 *         echo 'No Support in ',  $e->getType();
 *     } catch (Exception $e) {
 *         echo 'Caught other exception: ',  $e->getMessage();
 *     }
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBAdmin extends Database {

//    /**
//     * 建立所有所需的資料表
//     *
//     * @since 2.0.0
//     */
//    public function createAllTable() {
//
//        // 使用的資料庫系統為MySQL
//        if($this->db_type == 'mysql') {
//
//            // 所有要跑的SQL指令陣列
//            $execSql = array(
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."ABelong` (
//  `TID` int(10) unsigned NOT NULL,
//  `AID` int(10) unsigned NOT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."AGroup` (
//  `GID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
//  `GName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
//  `GMemo` tinytext COLLATE utf8_unicode_ci,
//  PRIMARY KEY (`GID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."Area` (
//  `AID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '區域編號',
//  `AName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '區域名稱',
//  `AMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '區域地圖編號',
//  `AIntroduction` tinytext COLLATE utf8_unicode_ci,
//  PRIMARY KEY (`AID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."CGroup` (
//  `CID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
//  `CName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
//  `CMemo` tinytext COLLATE utf8_unicode_ci,
//  PRIMARY KEY (`CID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."Edge` (
//  `Ti` int(11) NOT NULL,
//  `Tj` int(11) NOT NULL,
//  `MoveTime` int(4) NOT NULL COMMENT '移動時間(分鐘)',
//  `Destance` int(11) NOT NULL COMMENT '距離(M)'
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."LearnActivity` (
//  `LsID` int(10) NOT NULL,
//  `ThID` int(10) NOT NULL COMMENT '主題編號',
//  `CID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '班級名稱',
//  `StartTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '開始時間',
//  `Delay` int(11) NOT NULL COMMENT '實際狀態延誤(分)',
//  PRIMARY KEY (`LsID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."Material` (
//  `MID` int(10) unsigned NOT NULL COMMENT '教材內部編號',
//  `TID` int(10) unsigned NOT NULL COMMENT '標的內部編號',
//  `MMode` int(1) NOT NULL DEFAULT '0' COMMENT '教材模式',
//  `MUrl` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT '教材檔案路徑',
//  PRIMARY KEY (`MID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."Recommand` (
//  `TID` int(3) NOT NULL COMMENT '標的內部編號',
//  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者帳號',
//  `gradation` int(11) NOT NULL COMMENT '系統推薦標地順序'
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."Study` (
//  `SID` int(10) NOT NULL AUTO_INCREMENT,
//  `TID` int(10) NOT NULL COMMENT '標的內部編號',
//  `UID` int(30) NOT NULL COMMENT '使用者名稱',
//  `LMode` int(11) NOT NULL COMMENT '學習導引模式',
//  `MMode` int(11) NOT NULL COMMENT '教材模式',
//  `In_TargetTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '進入標的時間',
//  `Out_TargetTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '離開標的時間',
//  PRIMARY KEY (`SID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."StudyQuestion` (
//  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
//  `TID` int(10) NOT NULL,
//  `QID` int(11) NOT NULL,
//  `UAns` int(11) NOT NULL,
//  `CAns` int(11) NOT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."Target` (
//  `TID` int(10) unsigned NOT NULL COMMENT '標的內部編號',
//  `TNum` int(10) DEFAULT NULL COMMENT '標的地圖上的編號',
//  `TName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '標的名稱',
//  `TMapID` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地圖圖檔名稱',
//  `TLearnTime` int(4) unsigned NOT NULL COMMENT '預估此標的應該學習的時間',
//  `PLj` int(11) unsigned NOT NULL COMMENT '學習標的的人數限制',
//  `Mj` int(11) unsigned DEFAULT NULL COMMENT '目前人數',
//  `S` int(11) unsigned DEFAULT NULL COMMENT '學習標的飽和率上限',
//  `Fi` int(11) DEFAULT NULL COMMENT '學習標的滿額指標',
//  PRIMARY KEY (`TID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."TBelong` (
//  `TID` int(10) NOT NULL,
//  `ThID` int(10) NOT NULL,
//  `Weights` int(3) NOT NULL COMMENT '當次學習主題的某一個學習標的之權重'
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."Theme` (
//  `ThID` int(10) unsigned NOT NULL AUTO_INCREMENT,
//  `ThName` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '主題名稱',
//  `ThLearnTotal` int(4) NOT NULL COMMENT '學習此主題要花的總時間(m)',
//  `ThIntroduction` tinytext COLLATE utf8_unicode_ci COMMENT '介紹',
//  PRIMARY KEY (`ThID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."User` (
//  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者帳號',
//  `UPassword` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
//  `GID` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者群組',
//  `CID` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用者班級',
//  `UEnabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '帳號啟用狀態',
//  `UBuild_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '帳號建立時間',
//  `LMode` enum('line-learn','harf-line-learn','non-line-learn') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '學習導引模式',
//  `MMode` int(11) DEFAULT NULL COMMENT '教材模式',
//  `UNickname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暱稱',
//  `UReal_Name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真實姓名',
//  `UEmail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用者email',
//  `UMemo` tinytext COLLATE utf8_unicode_ci COMMENT '備註',
//  PRIMARY KEY (`UID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
//
//"CREATE TABLE IF NOT EXISTS `".$this->db_prefix."UserSession` (
//  `UsID` int(10) unsigned NOT NULL AUTO_INCREMENT,
//  `UToken` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '此登入階段的token',
//  `UID` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
//  `UAgent` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用哪個裝置登入',
//  `ULoginDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登入時間',
//  `ULogoutDate` timestamp NULL DEFAULT NULL COMMENT '登出時間',
//  PRIMARY KEY (`UsID`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;"
//            );
//
//            // 執行此SQL指令
//            foreach ($execSql as $value ){
//                $this->connDB->exec($value);
//            };
//        }
//        else {
//            throw new Exception\DatabaseNoSupportException($this->db_type);
//        }
//    }
}
