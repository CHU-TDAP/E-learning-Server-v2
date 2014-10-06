<?php
/**
 * DBUserTest
 *
 * @package         UElearning
 * @author          Yuan Chiu <chyuaner@gmail.com>
 */
namespace UElearning;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';
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
     * 測試查詢使用者
     * 
     * @dataProvider userDataProvider
     */
    public function testQueryUser($uId, $uPassword, $gId, $cId, $enable,
                                   $l_mode, $m_mode, 
                                   $nickName, $realName, $email, $memo){
        
        // 查詢使用者
        $info = $this->db->queryUser($uId);
        
        // 比對資料是否吻合
        $this->assertEquals($info['user_id'],         $uId);
        $this->assertEquals($info['password'],        $uPassword);
        $this->assertEquals($info['group_id'],        $gId);
        $this->assertEquals($info['class_id'],        $cId);
        $this->assertEquals($info['enable'],          $enable);
        $this->assertEquals($info['learnStyle_mode'], $l_mode);
        $this->assertEquals($info['material_mode'],   $m_mode);
        $this->assertEquals($info['nickname'],        $nickName);
        $this->assertEquals($info['realname'],        $realName);
        $this->assertEquals($info['email'],           $email);
        $this->assertEquals($info['memo'],            $memo);
    }
    
    /**
     * 測試查詢所有使用者
     * 
     * 僅測試是否能成功執行，不驗證結果
     */
    public function testQueryAllUser(){
        
        // 查詢使用者
        $infoAll = $this->db->queryAllUser();
    }
    
    /**
     * 測試更改使用者資料
     * 
     * @dataProvider userDataProvider
     */
    public function testChangeUser($uId, $uPassword, $gId, $cId, $enable,
                                   $l_mode, $m_mode, 
                                   $nickName, $realName, $email, $memo){
        
        $afterData = 'sfisjojjoij';
        
        // 記下更改前的資料
        $info = $this->db->queryUser($uId);
        $beforeData = $info['memo'];
        
        // 更改資料
        $this->db->changeUserData($uId, 'memo', $afterData);
        
        // 檢查更改後的結果
        $info = $this->db->queryUser($uId);
        $this->assertEquals($info['memo'], $afterData);
        
        // 改回來
        $this->db->changeUserData($uId, 'memo', $beforeData);
        
        // 檢查有沒有改回來
        $info = $this->db->queryUser($uId);
        $this->assertEquals($info['memo'], $beforeData);
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
            array('yuan_unittest', 'pass123', 'admin', null, true,
                  'harf-line-learn', 1, 
                  '元兒～', 'Yuan Chiu', 'chyuaner@gmail.com', null),
            
            array('eee_unittest', 'qqqssss', 'admin', null, 1, 
                  'harf-line-learn', '1', 
                  'sss', 'Yuan Chiu', 'chyuanesr@gmail.com', null)
        );
    }
    
    // ========================================================================
    
    /**
     * 測試建立群組
     * 
     * @dataProvider groupDataProvider
     */
    public function testCreateGroup($gId, $name, $memo, $auth_admin, $auth_clientAdmin){
        
        $this->db->insertGroup($gId, $name, $memo, $auth_admin, $auth_clientAdmin);
    }
    
    /**
     * 測試查詢群組
     * 
     * @dataProvider groupDataProvider
     */
    public function testQueryGroup($gId, $name, $memo, $auth_admin, $auth_clientAdmin){
        // 查詢使用者
        $info = $this->db->queryGroup($gId);
        
        // 比對資料是否吻合
        $this->assertEquals($info['group_id'],         $gId);
        $this->assertEquals($info['name'],             $name);
        $this->assertEquals($info['memo'],             $memo);
        $this->assertEquals($info['auth_admin'],       $auth_admin);
        $this->assertEquals($info['auth_clientAdmin'], $auth_clientAdmin);
    }
    
    /**
     * 測試移除使用者
     * 
     * @dataProvider groupDataProvider
     */
    public function testDeleteGroup($gId) {
        $this->db->deleteGroup($gId);
    }
    
    /**
     * 測試時要填的資料
     */ 
    public function groupDataProvider(){
        return array(
            array('testG_a', '測試用群組a', null, '1', '0'),
            array('testG_b', '測試用群組b', 'testhahaha Groups', '0', '1')
        );
    }
}