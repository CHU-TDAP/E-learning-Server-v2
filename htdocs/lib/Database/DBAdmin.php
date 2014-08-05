<?php
/**
 * 整體資料庫操作
 *
 * 此檔案針對整體資料庫的功能，像是建立此資料庫、建立表格、清空...等等
 */

namespace UElearning\Database;

require_once UELEARNING_LIB_ROOT.'Database/Database.php';
require_once UELEARNING_LIB_ROOT.'Database/Exceptions.php';

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
 *     } catch (Database\Exception\DatabaseNoSupportException $e) {
 *         echo 'No Support in ',  $e->getType();
 *     } catch (Exception $e) {
 *         echo 'Caught other exception: ',  $e->getMessage();
 *     }
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         3.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBAdmin extends Database {
    
    /**
     * 建立資料庫
     * 
     * 在資料庫系統內建立一個資料庫。
     * （注意！需有建立資料庫的權限）
     * 
     */ 
    public function createDB() {
        // TODO: Fill code in
        
    }
    
    /**
     * 建立所有所需的資料表
     * 
     */ 
    public function createAllTable() {
        // TODO: Fill code in
        
    }
}