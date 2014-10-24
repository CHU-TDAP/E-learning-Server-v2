<?php 
/**
 * UserGroupAdmin.php
 */

namespace UElearning\User;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/User/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 管理使用者權限群組的操作
 * 
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */ 
class UserGroupAdmin {
    
    /**
     * 建立群組
     * 
     * 建立使用者群組範例:
     * 
     *     try {
     *         $groupAdmin = new User\UserGroupAdmin();
     *         $groupAdmin->create(
     *             array( 'group_id' => 'student',
     *                    'name' => '學生',
     *                    'memo' => null,
     *                    'auth_server_admin' => false,
     *                    'auth_client_admin' => false
     *         ));
     *     
     *     }
     *     // 若已有重複群組ID
     *     catch (Exception\GroupIdExistException $e) {
     *         echo 'Is exist group: ',  $e->getGroupId();
     *     }
     * 
     * @param array $groupArray 使用者群組資訊陣列，格式為:
     *     array( 'group_id'              => 'student',
     *            'name'                  => '學生',
     *            'memo'                  => null,     // (optional) 預設為null
     *            'auth_server_admin'     => false,    // (optional) 預設為false
     *            'auth_client_admin'     => false )   // (optional) 預設為false
     * @throw UElearning\Exception\GroupIdExistException
     * @since 2.0.0
     */ 
    public function create($groupArray) {
        
        // 檢查有無填寫
        if(isset($groupArray)) {
            
            // 若必填項目無填寫
            if( !isset($groupArray['group_id']) ) {
                throw new Exception\NoDataException();
            }
            // 若此id已存在
            else if( $this->isExist($groupArray['group_id']) ) {
                throw new Exception\GroupIdExistException(
                    $groupArray['group_id'] );
            }
            // 沒有問題
            else {
                
                // 處理未帶入的資料
                if( !isset($groupArray['name']) ){
                    $groupArray['name'] = null;
                }
                // 處理未帶入的資料
                if( !isset($groupArray['memo']) ){
                    $groupArray['memo'] = null;
                }
                if( !isset($groupArray['auth_server_admin']) ){
                    $groupArray['auth_server_admin'] = false;
                }
                if( !isset($groupArray['auth_client_admin']) ){
                    $groupArray['auth_client_admin'] = false;
                }
                
                // 新增一筆使用者資料進資料庫
                $db = new Database\DBUser();
                $db->insertGroup(
                    array( 'group_id'         => $groupArray['group_id'],
                           'name'             => $groupArray['name'],
                           'memo'             => $groupArray['memo'],
                           'auth_admin'       => $groupArray['auth_server_admin'],
                           'auth_clientAdmin' => $groupArray['auth_client_admin']
                    )
                );
            }
        }
        else throw Exception\NoDataException();
    }
    
    /**
     * 是否已有相同名稱的帳號名稱
     * 
     * @param string $group_id 群組ID
     * @return bool 已有相同的群組ID
     * @since 2.0.0
     */ 
    public function isExist($group_id) {
        
        $db = new Database\DBUser();
        $info = $db->queryGroup($group_id);
        
        if( $info != null ) return true;
        else return false;
    }
    
    /**
     * 移除此群組
     * 
     * 範例: 
     * 
     *     try {
     *         $groupAdmin = new User\UserGroupAdmin();
     *         $groupAdmin->remove('test_student');
     *     
     *     }
     *     catch (Exception\GroupNoFoundException $e) {
     *         echo 'No Found group: ',  $e->getGroupId(); 
     *     }
     *
     * @param string $group_id 群組ID
     * @throw UElearning\Exception\GroupNoFoundException
     * @since 2.0.0
     */ 
    public function remove($group_id) {
        
        // 若有此使用者
        if($this->isExist($group_id)) {
            
            // TODO: 檢查所有關聯的資料，確認是否可以移除
            
            // 移除資料庫中的使用者
            $db = new Database\DBUser();
            $db->deleteGroup($group_id);
        }
        // 若沒有這位使用者
        else {
            throw new Exception\GroupNoFoundException($group_id);
        }
        
    }

}