<?php
/**
 * Exception.php
 */

namespace UElearning\Exception;

// TODO: 將以下類別濃縮

/**
 * 沒有找到此標的進出記錄
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class StudyNoFoundException extends \UnexpectedValueException {
    /**
     * 指定的學習活動ID
     * @type int
     */
    private $id;

    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */
    public function __construct($id) {
        $this->id = $id;
        parent::__construct('No Study: '.$this->id);
    }

    /**
     * 取得輸入的標的ID
     * @return int 標的ID
     */
    public function getId() {
        return $this->id;
    }
}

/**
 * 正在學習點內學習中例外（難道使用者有分身？）
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class InLearningException extends \UnexpectedValueException {

    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */
    public function __construct() {
        parent::__construct('Learning');
    }
}

/**
 * 不在學習點內的例外
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class NoInLearningException extends \UnexpectedValueException {

    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */
    public function __construct() {
        parent::__construct('NoInLearning');
    }
}

/**
 * 沒有找到此活動
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class StudyActivityNoFoundException extends \UnexpectedValueException {
    /**
     * 指定的學習活動ID
     * @type int
     */
    private $id;

    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */
    public function __construct($id) {
        $this->id = $id;
        parent::__construct('No Activity: '.$this->id);
    }

    /**
     * 取得輸入的標的ID
     * @return int 標的ID
     */
    public function getId() {
        return $this->id;
    }
}

/**
 * 此活動已結束
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class StudyActivityFinishedException extends \UnexpectedValueException {
    /**
     * 指定的學習活動ID
     * @type int
     */
    private $id;

    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */
    public function __construct($id) {
        $this->id = $id;
        parent::__construct('No Activity: '.$this->id);
    }

    /**
     * 取得輸入的標的ID
     * @return int 標的ID
     */
    public function getId() {
        return $this->id;
    }
}

/**
 * 沒有此預約
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class StudyActivityWillNoFoundException extends \UnexpectedValueException {
    /**
     * 指定的學習活動ID
     * @type int
     */
    private $id;

    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */
    public function __construct($id) {
        $this->id = $id;
        parent::__construct('No Activity: '.$this->id);
    }

    /**
     * 取得輸入的標的ID
     * @return int 標的ID
     */
    public function getId() {
        return $this->id;
    }
}

/**
 * 沒有找到此主題
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class ThemeNoFoundException extends \UnexpectedValueException {
    /**
     * 指定的學習活動ID
     * @type int
     */
    private $id;

    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */
    public function __construct($id) {
        $this->id = $id;
        parent::__construct('No Activity: '.$this->id);
    }

    /**
     * 取得輸入的標的ID
     * @return int 標的ID
     */
    public function getId() {
        return $this->id;
    }
}
