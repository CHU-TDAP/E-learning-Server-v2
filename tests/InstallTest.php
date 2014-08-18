<?php
///**
// * InstallTest
// *
// * @package         UElearning
// * @author          Yuan Chiu <chyuaner@gmail.com>
// */
//namespace UElearning;
//
//require_once UELEARNING_LIB_ROOT.'/Database/DBAdmin.php';
//
//class InstallTest extends \PHPUnit_Framework_TestCase
//{
//    
//    /**
//     * 測試安裝初始化資料庫
//     */
//    public function testInstallDatabase()
//    {
//        
//        try {
//            // 建立資料庫管理物件
//            $dbAdmin = new Database\DBAdmin();
//            
//            // 建立所有所需的資料表
//            $dbAdmin->createAllTable();
//        } 
//        // 若設定的DBMS不被支援 則丟出例外
//        catch (Database\Exception\DatabaseNoSupportException $e) {
//            throw $e;
//        }
//    }
//}