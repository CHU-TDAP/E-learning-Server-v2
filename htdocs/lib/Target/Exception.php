<?php
/**
 * 使用者帳號群組相關例外類別檔案
 */

namespace UElearning\Exception;

/**
 * 沒有找到此標的
 * @since 2.0.0
 * @package         UElearning
 * @subpackage      Target
 */ 
class TargetNoFoundException extends \UnexpectedValueException {
    /**
     * 指定的標的ID
     * @type int
     */ 
    private $id;
    
    /**
     * 使用者帳號例外
     * @param int $id 輸入的標的ID
     */ 
    public function __construct($id) {
        $this->id = $id;
        parent::__construct('No Target: '.$this->id);
    }
    
    /**
     * 取得輸入的標的ID
     * @return int 標的ID
     */ 
    public function getId() {
        return $this->id;
    }
}