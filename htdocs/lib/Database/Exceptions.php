<?php namespace UElearning\Database;
/**
 * @file
 * 設定檔的例外
 * 
 * @package         UElearning
 * @subpackage      Database
 */

/**
 * 沒有支援的資料庫系統例外
 */ 
class DatabaseNoSupportException extends \UnexpectedValueException {
    private $type;
    
    
    /**
     * 沒有支援的資料庫系統
     * @param array $type 資料庫系統名稱
     */ 
    public function __construct($type) {
        $this->type = $type;
        parent::__construct('No support: '.$this->type);
    }
    
    /**
     * 取得輸入的資料庫系統名稱
     * @return string 錯誤訊息內容
     */ 
    public function getType() {
        return $this->type;
    }
}

