<?php namespace UElearning\User\Exception;
/**
 * @file
 * 設定檔的例外
 * 
 * @package         UElearning
 * @subpackage      User
 * 
 */

/**
 * 使用者帳號例外
 * @since 3.0.0
 */ 
abstract class UserException extends \UnexpectedValueException {
    
    /**
     * 指定的使用者名稱
     * @type string
     */ 
    private $userId;
    
    /**
     * 使用者帳號例外
     * @param array $userId 輸入的使用者名稱
     * @param string $description 描述
     */ 
    public function __construct($userId, $description) {
        $this->userId = $userId;
        parent::__construct($description);
    }
    
    /**
     * 取得輸入的資料庫系統名稱
     * @return string 錯誤訊息內容
     */ 
    public function getUserId() {
        return $this->userId;
    }
}

// 使用者登入 ======================================================================
/**
 * 沒有找到此帳號
 * @since 3.0.0
 */ 
class UserNoFoundException extends UserException {
    public function __construct($userId) {
        parent::__construct($userId, 'User: "'.$this->type.'" is no found.');
    }
}

/**
 * 使用者登入密碼錯誤
 * @since 3.0.0
 */ 
class UserPasswordErrException extends UserException {
    public function __construct($userId) {
        parent::__construct($userId, 'User: "'.$this->type.'" password is wrong.');
    }
}

/**
 * 此帳號未啟用
 * @since 3.0.0
 */ 
class UserNoActivatedException extends UserException {
    public function __construct($userId) {
        parent::__construct($userId, 'User: "'.$this->type.'" is no activated.');
    }
}

// 建立使用者 ======================================================================
/**
 * 已有重複的使用者名稱
 * @since 3.0.0
 */ 
class UserIdExistException extends UserException {
    public function __construct($userId) {
        parent::__construct($userId, 'UserId: "'.$this->type.'" is exist.');
    }
}
