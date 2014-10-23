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
     * 測試建立使用者
     * 
     * @dataProvider userDataProvider
     */
    public function testCreateUser($uId, $uPassword, $gId, $cId, $enable,
                                   $l_mode, $m_mode, $enable_noAppoint,
                                   $nickName, $realName, $email, $memo)
    {
        
        try {
            // 建立資料庫管理物件
            $userAdmin = new UserAdmin();
            
            // 建立使用者
            $userAdmin->create(
                array( 'user_id'            => $uId,
                      'password'           => $uPassword,
                      'group_id'           => $gId,
                      'class_id'           => $cId,   
                      'enable'             => $enable,
                      'learnStyle_mode'    => $l_mode,
                      'material_mode'      => $m_mode,   
                      'enable_noAppoint'   => $enable_noAppoint,
                      'nickname'           => $nickName, 
                      'realname'           => $realName,      
                      'email'              => $email,
                      'memo'               => $memo              
            ));
        } 
        // 若設定的DBMS不被支援 則丟出例外
        catch (Database\Exception\DatabaseNoSupportException $e) {
            throw $e;
        }
    }
    
    /**
     * 檢查是否已確實建立
     * 
     * @dataProvider userDataProvider
     */ 
    public function testCheckExist($uId)
    {
        // 建立資料庫管理物件
        $userAdmin = new UserAdmin();
        
        // 檢查是否已確實建立
        $this->assertEquals($userAdmin->isExist($uId), true);
    }
    
    /**
     * 刪除建立的帳號（恢復原狀用）
     * 
     * @dataProvider userDataProvider
     */ 
    public function testDeleteUser($uId)
    {
        // 建立資料庫管理物件
        $userAdmin = new UserAdmin();
        
        // 移除此使用者
        $userAdmin->remove($uId); 
        
        // 檢查是否已確實建立
        $this->assertEquals($userAdmin->isExist($uId), false);
        
    }
    
    
    
    /**
     * 測試時要填的資料
     */ 
    public function userDataProvider(){
        return array(
            array('yuan_unittest', 'pass123', 'admin', null, true,
                  3, 'normal', true,
                  '元兒～', 'Yuan Chiu', 'chyuaner@gmail.com', null),
            
            array('eee_unittest', 'qqqssss', 'admin', null, 1, 
                  0, 'normal', false,
                  'sss', 'Yuan Chiu', 'chyuanesr@gmail.com', null)
        ); 
    }
    
}