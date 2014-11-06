<?php
/**
 * Exception.php
 */

namespace UElearning\Exception;

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
 * @package         UElearning
 * @subpackage      User
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
 * @package         UElearning
 * @subpackage      User
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
 * @package         UElearning
 * @subpackage      User
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
 * @package         UElearning
 * @subpackage      User
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

// ============================================================================

/**
 * 使用者群組例外
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */
abstract class GroupException extends \UnexpectedValueException {

    /**
     * 指定的使用者群組ID
     * @type string
     */
    private $groupId;

    /**
     * 使用者帳號例外
     * @param string $groupId 輸入的使用者群組ID
     * @param string $description 描述
     */
    public function __construct($groupId, $description) {
        $this->groupId = $groupId;
        parent::__construct($description);
    }

    /**
     * 取得輸入的資料庫系統名稱
     * @return string 錯誤訊息內容
     */
    public function getGroupId() {
        return $this->groupId;
    }
}

/**
 * 已有重複的使用者群組ID
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class GroupIdExistException extends GroupException {
    /**
     * 已有重複的使用者名稱
     * @param string $groupId 輸入的使用者群組ID
     */
    public function __construct($groupId) {
        parent::__construct($groupId, 'GroupId: "'.$groupId.'" is exist.');
    }
}

/**
 * 沒有找到此使用者群組ID
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class GroupNoFoundException extends GroupException {
    /**
     * 沒有找到此帳號
     * @param string $groupId 輸入的使用者群組ID
     */
    public function __construct($groupId) {
        parent::__construct($groupId, 'Group: "'.$groupId.'" is no found.');
    }
}

// ============================================================================

/**
 * 已有重複的使用者群組ID
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class ClassIdExistException extends GroupException {
    /**
     * 已有重複的使用者名稱
     * @param string $groupId 輸入的使用者群組ID
     */
    public function __construct($groupId) {
        parent::__construct($groupId, 'ClassId: "'.$groupId.'" is exist.');
    }
}

/**
 * 沒有找到此使用者群組ID
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class ClassNoFoundException extends GroupException {
    /**
     * 沒有找到此帳號
     * @param string $groupId 輸入的使用者群組ID
     */
    public function __construct($groupId) {
        parent::__construct($groupId, 'Class Group: "'.$groupId.'" is no found.');
    }
}

// ============================================================================

/**
 * 沒有此權限例外
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class PermissionNoFoundException extends \UnexpectedValueException {

    /**
     * 指定的使用者群組ID
     * @type string
     */
    private $permissionName;

    /**
     * 使用者帳號例外
     * @param string $groupId 輸入的使用者群組ID
     * @param string $description 描述
     */
    public function __construct($permissionName) {
        $this->permissionName = $permissionName;
        parent::__construct('No Found Permission: '.$this->permissionName);
    }

    /**
     * 取得輸入的資料庫系統名稱
     * @return string 錯誤訊息內容
     */
    public function getName() {
        return $this->permissionName;
    }

}

// ============================================================================

/**
 * 找不到此登入階段
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class LoginTokenNoFoundException extends \UnexpectedValueException {

    /**
     * 登入階段Token
     * @type string
     */
    private $token;

    /**
     * 找不到此登入階段例外
     * @param string $token 登入階段Token
     */
    public function __construct($token) {
        $this->token = $token;
        parent::__construct('No Found Login Token: '.$this->token);
    }

    /**
     * 取得輸入的登入階段Token
     * @return string 登入階段Token
     */
    public function getToken() {
        return $this->token;
    }
}
