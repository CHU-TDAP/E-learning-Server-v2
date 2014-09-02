<?php
/**
 * 使用者帳號群組相關例外類別檔案
 */

namespace UElearning\User\Exception;

/**
 * 使用者帳號例外
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */ 
abstract class UserException extends \UnexpectedValueException {
    
    /**
     * 指定的使用者名稱
     * @type string
     */ 
    private $userId;
    
    /**
     * 使用者帳號例外
     * @param string $userId 輸入的使用者名稱
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
 * @since 2.0.0
 */ 
class UserNoFoundException extends UserException {
    /**
     * 沒有找到此帳號
     * @param string $userId 輸入的使用者名稱
     */ 
    public function __construct($userId) {
        parent::__construct($userId, 'User: "'.$userId.'" is no found.');
    }
}

/**
 * 使用者登入密碼錯誤
 * @since 2.0.0
 */ 
class UserPasswordErrException extends UserException {
    /**
     * 沒有找到此帳號
     * @param string $userId 輸入的使用者名稱
     */ 
    public function __construct($userId) {
        parent::__construct($userId, 'User: "'.$userId.'" password is wrong.');
    }
}

/**
 * 此帳號未啟用
 * @since 2.0.0
 */ 
class UserNoActivatedException extends UserException {
    /**
     * 此帳號未啟用
     * @param string $userId 輸入的使用者名稱
     */ 
    public function __construct($userId) {
        parent::__construct($userId, 'User: "'.$userId.'" is no activated.');
    }
}

// 建立使用者 ======================================================================
/**
 * 已有重複的使用者名稱
 * @since 2.0.0
 */ 
class UserIdExistException extends UserException {
    /**
     * 已有重複的使用者名稱
     * @param string $userId 輸入的使用者名稱
     */ 
    public function __construct($userId) {
        parent::__construct($userId, 'UserId: "'.$userId.'" is exist.');
    }
}
