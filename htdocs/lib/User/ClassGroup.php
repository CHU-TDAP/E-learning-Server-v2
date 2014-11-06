<?php
/**
 * ClassGroup.php
 */

namespace UElearning\User;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/User/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 班級群組類別
 *
 * 一個物件即代表這一個班級
 *
 * 範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/User/ClassGroup.php'
 *     use UElearning\User;
 *
 *     try {
 *         $group = new User\ClassGroup(1);
 *         echo $group->getName();
 *         $group->setName('測試用');
 *         echo $group->getName();
 *     }
 *     catch (Exception\ClassNoFoundException $e) {
 *         echo 'No Found class: '. $e->getGroupId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class ClassGroup {

    /**
     * 群組ID
     * @type int
     */
    protected $cId;

    // ========================================================================

    /**
     * 查詢到此帳號的所有資訊的結果
     *
     * 由 $this->getQuery() 抓取資料表中所有資訊，並放在此陣列裡
     *
     * @type array
     */
    protected $queryResultArray;

    /**
     * 從資料庫取得此群組查詢
     *
     * @throw UElearning\Exception\ClassNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){

        // 從資料庫查詢群組
        $db        = new Database\DBUser();
        $groupInfo = $db->queryClassGroup($this->cId);

        // 判斷有沒有這個群組
        if( $groupInfo != null ) {
            $this->queryResultArray = $groupInfo;
        }
        else throw new Exception\ClassNoFoundException($this->cId);
    }

    /**
     * 從資料庫更新此群組設定
     *
     * @since 2.0.0
     */
    protected function setUpdate($field, $value){

        // 將新設定寫進資料庫裡
        $db = new Database\DBUser();
        $db->changeClassGroupData($this->cId, $field, $value);
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param int $inputCID 班級ID
     * @since 2.0.0
     */
    public function __construct($inputCID){
        $this->cId = $inputCID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得群組ID
     *
     * @return int 班級ID
     * @since 2.0.0
     */
    public function getID(){
        return $this->cId;
    }

    // ------------------------------------------------------------------------

    /**
     * 取得帳號建立時間
     *
     * @return string 建立時間
     * @since 2.0.0
     */
    public function getCreateTime(){
        return $this->queryResultArray['build_time'];
    }
    // ========================================================================

    /**
     * 取得群組顯示名稱
     *
     * @return string 群組名稱
     * @since 2.0.0
     */
    public function getName(){
        return $this->queryResultArray['name'];
    }

    /**
     * 設定群組顯示名稱
     *
     * @param string $name 群組名稱
     * @since 2.0.0
     */
    public function setName($name){
        $this->setUpdate('name', $name);
    }

    /**
     * 取得帳號備註資訊
     *
     * @return string 使用者帳號備註資訊
     * @since 2.0.0
     */
    public function getMemo(){
        return $this->queryResultArray['memo'];
    }

    /**
     * 修改帳號備註資訊
     *
     * @param string $input 新的帳號備註資訊
     * @since 2.0.0
     */
    public function setMemo($input){
        $this->setUpdate('memo', $input);
    }

}
