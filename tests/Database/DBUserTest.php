<?php
/**
 * DBUserTest
 *
 * @package         UElearning
 * @author          Yuan Chiu <chyuaner@gmail.com>
 */
namespace UElearning;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exceptions.php';
use UElearning\Database\DBUser;
use UElearning\Database\Exception;

class DBUserTest extends \PHPUnit_Framework_TestCase
{
    
    protected $db;
    
    protected function setUp(){
        try {
            // 建立資料庫管理物件
            $this->db = new DBUser();

        } 
        // 若設定的DBMS不被支援 則丟出例外
        catch (Database\Exception\DatabaseNoSupportException $e) {
            throw $e;
        } 
    }
    
    /**
     * 測試建立使用者
     * 
     * @dataProvider userDataProvider
     */
    public function testCreateUser($uId, $uPassword, $gId, $cId, $enable,
                                   $l_mode, $m_mode, 
                                   $nickName, $realName, $email, $memo){
        
        $this->db->insertUser($uId, $uPassword, $gId, $cId, $enable,
                              $l_mode, $m_mode, 
                              $nickName, $realName, $email, $memo);
    }
    
    /**
     * 測試移除使用者
     * 
     * @dataProvider userDataProvider
     */
    public function testDeleteUser($uId) {
        $this->db->deleteUser($uId);
    }
    
    /**
     * 測試時要填的資料
     */ 
    public function userDataProvider(){
        return array(
            array('yuan_unittest', 'pass123', 'admin', null, true, 'harf-line-learn', 1, '元兒～', 'Yuan Chiu', 'chyuaner@gmail.com', null),
            array('eee_unittest', 'qqqssss', 'admin', null, 1, 'harf-line-learn', '1', 'sss', 'Yuan Chiu', 'chyuanesr@gmail.com', null)
        );
    }
}