<?php
/**
 * UserGroupAdminTest.php
 *
 * @package         UElearning
 * @author          Yuan Chiu <chyuaner@gmail.com>
 */
namespace UElearning;

require_once UELEARNING_LIB_ROOT.'/User/UserGroupAdmin.php';
use UElearning\User\UserGroupAdmin;

class UserGroupAdminTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * 測試建立群組
     * 
     * @dataProvider groupDataProvider
     */
    public function testCreateGroup($gId, $name, $memo, $auth_admin, $auth_clientAdmin){
        
        try {
            $groupAdmin = new User\UserGroupAdmin();
            $groupAdmin->create(
                array( 'group_id' => $gId,
                       'name' => $name,
                       'memo' => $memo,
                       'auth_server_admin' => $auth_admin,
                       'auth_client_admin' => $auth_clientAdmin
            ));

        }
        // 若已有重複帳號名稱
        catch (User\Exception\GroupIdExistException $e) {
            throw $e;
        }
        
    }
    
    /**
     * 測試查詢群組
     * 
     * @dataProvider groupDataProvider
     */
    public function testCheckExist($gId){
        
        $groupAdmin = new User\UserGroupAdmin();
        
        // 比對資料是否吻合
        $this->assertEquals($groupAdmin->isExist($gId), true);
    }
    
    /**
     * 測試移除使用者
     * 
     * @dataProvider groupDataProvider
     */
    public function testDeleteGroup($gId) {
        
        try {
            $groupAdmin = new User\UserGroupAdmin();
            $groupAdmin->remove($gId);
            
            $this->assertEquals($groupAdmin->isExist($gId), false);
        }
        catch (User\Exception\GroupNoFoundException $e) {
            throw $e;
        }
        
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