<?php 
/**
 * UserAdmin.php
 */

namespace UElearning\User;

/**
 * 管理使用者的操作
 * 
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */ 
class UserAdmin {
    
    /**
     * 建立使用者
     * 
     * @param array $userInfoArray 使用者資訊陣列，格式為:
     *     array( 'userId' => 'root',
     *            'password' => 'pass123',
     *            'password_encrypt' => null, // (optional) 預設為null
     *            'groupId' => 'user',
     *            'classId' => '5-2', // (optional)
     *            'enable' => true, // (optional) 預設為true
     *            'learnStyle_mode' => 'harf-line-learn', // (optional)
     *            'material_mode' => 1, // (optional)
     *            'nickname' => 'eric', // (optional)
     *            'realname' => 'Eric Chiu', // (optional)
     *            'email' => 'eric@example.tw', // (optional)
     *            'memo' => '' ) // (optional)
     * @since 2.0.0
     */ 
    public function create($userInfoArray) {
       // TODO: Fill code in 
    }
    
    /**
     * 是否已有相同名稱的帳號名稱
     * 
     * @param string $userName 帳號名稱
     * @return bool 已有相同的帳號名稱
     * @since 2.0.0
     */ 
    public function isExist($userName) {
        // TODO: Fill code in 
    }

}