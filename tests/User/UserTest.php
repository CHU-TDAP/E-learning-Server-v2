<?php
/**
 * UserTest.php
 *
 * @package         UElearning
 * @author          Yuan Chiu <chyuaner@gmail.com>
 */
namespace UElearning;

require_once UELEARNING_LIB_ROOT.'/User/User.php';
require_once UELEARNING_LIB_ROOT.'/User/UserAdmin.php';
use UElearning\User;

class UserTest extends \PHPUnit_Framework_TestCase
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
            $userAdmin = new User\UserAdmin();
            
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
     * 測試取得資料
     * 
     * @dataProvider userDataProvider
     */
    public function testGetInfo($uId, $uPassword, $gId, $cId, $enable,
                                   $l_mode, $m_mode, $enable_noAppoint,
                                   $nickName, $realName, $email, $memo)
    {
        try {
            $user = new User\User($uId);
            
            // 密碼檢查
            $this->assertEquals($user->isPasswordCorrect($uPassword), true);
            $this->assertEquals($user->isPasswordCorrect($uPassword.'as1@#'), false);
            
            // 個人資料檢查
            $this->assertEquals($user->getNickName(), $nickName);
            $this->assertEquals($user->getRealName(), $realName);
            $this->assertEquals($user->getEmail(),    $email);
            $this->assertEquals($user->getMemo(),     $memo);
            
        }
        catch (User\Exception\UserNoFoundException $e) {
            echo 'No Found user: '. $e->getUserId();
        }
    }
    
    /**
     * 測試設定資料
     * 
     * @dataProvider userDataProvider
     */
    public function testSetInfo($uId, $uPassword, $gId, $cId, $enable,
                                   $l_mode, $m_mode, $enable_noAppoint,
                                   $nickName, $realName, $email, $memo)
    {
        try {
            $user = new User\User($uId);
            
            // 密碼檢查
            $user->changePassword('asdw');
            $this->assertEquals($user->isPasswordCorrect('asdw'), true);
            
            // 設定啟用檢查
            $user->setEnable(false);
            $this->assertEquals($user->isEnable(), false);
            
            // 個人資料檢查
            $user->setNickName('叉洛伊');
            $this->assertEquals($user->getNickName(), '叉洛伊');
            
            $user->setRealName('Eric Chiou');
            $this->assertEquals($user->getRealName(), 'Eric Chiou');
            
            $user->setEmail('sdj@example.moe');
            $this->assertEquals($user->getEmail(), 'sdj@example.moe');
            
            $user->setMemo('sacmldscmdlsvndlsknvkdsvne;vne;wnvoewzcmlsnwensc');
            $this->assertEquals($user->getMemo(), 
                           'sacmldscmdlsvndlsknvkdsvne;vne;wnvoewzcmlsnwensc');
            
        }
        catch (User\Exception\UserNoFoundException $e) {
            echo 'No Found user: '. $e->getUserId();
        }
    }
    
    /**
     * 刪除建立的帳號（恢復原狀用）
     * 
     * @dataProvider userDataProvider
     */ 
    public function testDeleteUser($uId)
    {
        // 建立資料庫管理物件
        $userAdmin = new User\UserAdmin();
        
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