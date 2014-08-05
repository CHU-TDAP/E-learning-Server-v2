<?php
/**
 * 使用者資料表
 *
 * 此檔案針對使用者資料表的功能。
 */

namespace UElearning\Database;

require_once UELEARNING_LIB_ROOT.'Database/Database.php';
require_once UELEARNING_LIB_ROOT.'Database/Exceptions.php';

/**
 * 使用者帳號資料表
 * 
 * 對資料庫中的使用者資料表進行操作。
 * 
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         3.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBUser extends Database {
    /**
     * 資料表名稱
     * @type string
     */ 
    private $form_name = 'user';
    
    
}