<?php 
/**
* 使用者登入階段管理類別檔案
*/

namespace UElearning\User;

/**
 * 使用者登入階段管理
 * 
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */ 
class UserSession {
        
    /**
     * 使用者登入
     * @param string $userId 帳號名稱
     * @param string $password 密碼
     * @return string 登入session token
     * @since 2.0.0
     */ 
    public function login($userId, $password) {
        // TODO: Fill code in
        // 如果登入錯誤，就丟例外
    }
    
    // ========================================================================
    
    /**
     * 使用者登出
     * @param string $token 登入階段token
     * @since 2.0.0
     */ 
    public function logout($token) {
        // TODO: Fill code in
    }
    
    /**
     * 將其他已登入的裝置登出
     * @param string $token 登入階段token
     * @since 2.0.0
     */ 
    public function logoutOtherSession($token) {
        // TODO: Fill code in
    }
    
    
    /**
     * 取得使用者物件
     * @param string $token 登入階段token
     * @return User 使用者物件
     * @since 2.0.0
     */ 
    public function getUser($token) {
        // TODO: Fill code in
    }
    
    /**
     * 取得登入資訊
     * @param string $token 登入階段token
     * @return Array 此登入階段資訊
     * @since 2.0.0
     */ 
    public function getTokenInfo($token) {
        // TODO: Fill code in
    }
    
    // ========================================================================
    
    /**
     * 取得所有此使用者已登入的登入階段資訊
     * @param string $userId 使用者帳號名稱
     * @return Array 已登入的所有登入階段資訊
     * @since 2.0.0
     */ 
    public function getUserLoginInfo($userId) {
        // TODO: Fill code in
    }
    
    /**
     * 取得此使用者登入的裝置數
     * @param string $userId 使用者帳號名稱
     * @return int 所有以登入的數量
     * @since 2.0.0
     */ 
    public function getUserLoginTotal($userId) {
        // TODO: Fill code in 
    }
    
    /**
     * 取得所有此使用者全部的登入階段資訊
     * 
     * 用於查詢登入紀錄的時候使用
     * @param string $userId 使用者帳號名稱
     * @return Array 已登入的所有登入階段資訊
     * @since 2.0.0
     */ 
    public function getUserAllInfo($userId) {
        // TODO: Fill code in
    }
    
    /**
     * 將此使用者全部登入階段登出
     * @param string $userId 使用者帳號名稱
     * @since 2.0.0
     */ 
    public function logoutUserAllSession($userId) {
        // TODO: Fill code in
    }
}