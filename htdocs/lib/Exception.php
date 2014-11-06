<?php
/**
 * Exception.php
 *
 * 通用例外類別檔案
 */

namespace UElearning\Exception;

/**
 * 沒填入資料例外
 * @since 2.0.0
 * @package         UElearning
 */
class NoDataException extends \UnexpectedValueException {

    /**
     * 指定的使用者名稱
     * @type string|array 欄位名稱
     */
    private $fieldName;

    /**
     * 未填資料例外
     * @param string|array $fieldName 欄位名稱
     */
    public function __construct() {
        if(func_num_args() == 1){
            $args = func_get_args();
            $fieldName = $args[0];

            $this->fieldName = $fieldName;
            parent::__construct();
        }
        else {
            $this->fieldName = array();
        }
    }

    /**
     * 新增一項未輸入的欄位名稱
     */
    public function addFieldName($fieldName) {
        $this->fieldName += array($fieldName);
    }

    /**
     * 取得未輸入的欄位名稱
     * @return string|array 欄位名稱
     */
    public function getFieldName() {
        return $this->fieldName;
    }
}
