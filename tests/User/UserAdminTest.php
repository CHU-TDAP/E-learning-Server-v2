<?php
/**
 * UserAdminTest.php
 *
 * @package         UElearning
 * @author          Yuan Chiu <chyuaner@gmail.com>
 */
namespace UElearning;

require_once UELEARNING_LIB_ROOT.'/User/UserAdmin.php';
use UElearning\User\UserAdmin;

class UserAdminTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * 測試安裝初始化資料庫
     */
    public function testCreateUser()
    {
        
        try {
            // 建立資料庫管理物件
            $dbAdmin = new UserAdmin();
            
            // TODO: 建立使用者
        } 
        // 若設定的DBMS不被支援 則丟出例外
        catch (Database\Exception\DatabaseNoSupportException $e) {
            throw $e;
        }
    }
}