<?php 
/**
 * 使用者群組管理類別檔案
 */

namespace UElearning\User;


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
     * 建立使用者範例:
     * 
     *     try {
     *         $userAdmin = new User\UserAdmin();
     *         $userAdmin->create(
     *             array( 'group_id' => 'student',
     *                    'name' => '學生',
     *                    'memo' => null,
     *                    'auth_server_admin' => false,
     *                    'auth_client_admin' => false
     *         ));
     *     
     *     }
     *     // 若已有重複帳號名稱
     *     catch (User\Exception\GroupIdExistException $e) {
     *         echo 'Is exist group: ',  $e->getGroupId();
     *     }
     * 
     * @param array $groupArray 使用者資訊陣列，格式為:
     *     array( 'group_id'              => 'student',
     *            'name'                  => '學生',
     *            'memo'                  => null,     // (optional) 預設為null
     *            'auth_server_admin'     => false,    // (optional) 預設為false
     *            'auth_client_admin'     => false )   // (optional) 預設為false
     * @throw UElearning\User\Exception\GroupIdExistException
     * @since 2.0.0
     */ 
    public function create($groupArray) {
        
        /*// 檢查必填項目有無填寫
        if(isset($userInfoArray)) {
            
            // 若無填寫
            if( !isset($userInfoArray['user_id'])  ||
                !isset($userInfoArray['password']) ||
                !isset($userInfoArray['group_id']) ) {
                throw new UElearning\Exception\NoDataException();
            }
            // 若此id已存在
            else if($this->isExist($userInfoArray['user_id'])) {
                throw new Exception\UserIdExistException(
                    $userInfoArray['user_id'] );
            }
            // 沒有問題
            else {
                
                // 處理未帶入的資料
                if( !isset($userInfoArray['class_id']) ){
                    $userInfoArray['class_id'] = null;
                }
                if( !isset($userInfoArray['enable']) ){
                    $userInfoArray['enable'] = true;
                }
                if( !isset($userInfoArray['learnStyle_mode']) ){
                    $userInfoArray['learnStyle_mode'] = null;
                }
                if( !isset($userInfoArray['material_mode']) ){
                    $userInfoArray['material_mode'] = null;
                }
                if( !isset($userInfoArray['nickname']) ){
                    $userInfoArray['nickname'] = null;
                }
                if( !isset($userInfoArray['realname']) ){
                    $userInfoArray['realname'] = null;
                }
                if( !isset($userInfoArray['email']) ){
                    $userInfoArray['email'] = null;
                }
                if( !isset($userInfoArray['memo']) ){
                    $userInfoArray['memo'] = null;
                }
                
                // 進行密碼加密
                $passUtil = new Util\Password();
                $passwdEncrypted = $passUtil->encrypt( $userInfoArray['password'] );
                
                // 新增一筆使用者資料進資料庫
                $db = new Database\DBUser();
                $db->insertUser(
                    $userInfoArray['user_id'], 
                    $passwdEncrypted, 
                    $userInfoArray['group_id'], 
                    $userInfoArray['class_id'], 
                    $userInfoArray['enable'], 
                    $userInfoArray['learnStyle_mode'], 
                    $userInfoArray['material_mode'], 
                    $userInfoArray['nickname'], 
                    $userInfoArray['realname'], 
                    $userInfoArray['email'], 
                    $userInfoArray['memo']
                );
            }
        }
        else throw Exception\NoDataException();*/
    }
    
    /**
     * 是否已有相同名稱的帳號名稱
     * 
     * @param string $group_id 群組ID
     * @return bool 已有相同的群組ID
     * @since 2.0.0
     */ 
    public function isExist($group_id) {
        
        /*$db = new Database\DBUser();
        $info = $db->queryUser($userName);
        
        if( $info != null ) return true;
        else return false;*/
    }
    
    /**
     * 移除此群組
     * 
     * @param string $group_id 群組ID
     * @throw UElearning\User\Exception\GroupNoFoundException
     * @since 2.0.0
     */ 
    public function remove($group_id) {
        /*
        // 若有此使用者
        if($this->isExist($userName)) {
            
            // TODO: 檢查所有關聯的資料，確認是否可以移除
            
            // 移除資料庫中的使用者
            $db = new Database\DBUser();
            $db->deleteUser($userName);
        }
        // 若沒有這位使用者
        else {
            throw new Exception\UserNoFoundException($userName);
        }
        */
    }

}