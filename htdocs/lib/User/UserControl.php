<?php namespace UElearning\User;
/**
 * @file
 * 管理使用者的操作
 *
 * @package         UElearning
 * @subpackage      User
 */

/**
 * 使用者帳號管理
 * 
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         3.0
 * @package         UElearning
 * @subpackage      User
 */ 
class UserControl {
    
    /**
     * 建立使用者
     * @since 3.0.0
     */ 
    public function create() {
       // TODO: Fill code in 
    }
    
    /**
     * 是否已有相同名稱的帳號名稱
     * 
     * @param string $userName 帳號名稱
     * @return bool 已有相同的帳號名稱
     * @since 3.0.0
     */ 
    public function isExist($userName) {
        // TODO: Fill code in 
    }
    
    /**
     * 使用者登入
     * @param string $userId 帳號名稱
     * @param string $password 密碼
     * @return string 登入session token
     * @since 3.0.0
     */ 
    public function login($userId, $password) {
        // TODO: Fill code in 
        // 如果登入錯誤，就丟例外
    }
}