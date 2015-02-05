<?php
/**
 * Study.php
 */

namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBStudy.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Target/Target.php';
use UElearning\Database;
use UElearning\Target;
use UElearning\Exception;

/**
 * 學習點進出記錄
 *
 * 一個物件即代表此學習點進出記錄
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class Study {

    /**
     * 主題ID
     * @type int
     */
    protected $id;

    // ------------------------------------------------------------------------

    /**
     * 查詢到所有資訊的結果
     *
     * 由 $this->getQuery() 抓取資料表中所有資訊，並放在此陣列裡
     * @type array
     */
    protected $queryResultArray;

    /**
     * 從資料庫取得查詢
     *
     * @throw \UElearning\Exception\ThemeNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){

        // 從資料庫查詢
        $db = new Database\DBStudy();
        $info = $db->queryById($this->id);

        // 判斷有沒有這個
        if( $info != null ) {
            $this->queryResultArray = $info;
        }
        else throw new Exception\StudyNoFoundException($this->id);
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param int $inputTID 主題ID
     * @since 2.0.0
     */
    public function __construct($inputID){
        $this->id = $inputID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得本進出記錄編號
     *
     * @return int 進出記錄編號
     * @since 2.0.0
     */
    public function getId(){
        return $this->id;
    }

    /**
     * 取得本次活動編號
     *
     * @return int 學習活動編號
     * @since 2.0.0
     */
    public function getActivityId(){
        return $this->queryResultArray['activity_id'];
    }

    /**
     * 取得標的編號
     *
     * @return int 標的編號
     * @since 2.0.0
     */
    public function getTargetId(){
        return $this->queryResultArray['target_id'];
    }

//    /**
//     * 取得標的名稱
//     *
//     * @return int 標的名稱
//     * @since 2.0.0
//     */
//    public function getTargetName(){
//        return $this->queryResultArray['target_id'];
//    }

//    /**
//     * 取得此紀錄的使用者ID
//     *
//     * @return string 使用者ID
//     * @since 2.0.0
//     */
//    public function getUserId(){
//        return $this->queryResultArray['target_id'];
//    }

    /**
     * 是否為實體（實際抵達學習點）
     *
     * @return bool 是否為實體
     * @since 2.0.0
     */
    public function isEntity(){
        return $this->queryResultArray['is_entity'];
    }

    /**
     * 取得進入學習點時間
     *
     * @return string 進入學習點時間
     * @since 2.0.0
     */
    public function isIn(){
        // TODO: 尚未測試
        if($this->queryResultArray['out_target_time'] == null) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 取得進入學習點時間
     *
     * @return string 進入學習點時間
     * @since 2.0.0
     */
    public function getInTime(){
        return $this->queryResultArray['in_target_time'];
    }

    /**
     * 取得離開學習點時間
     *
     * @return string 離開學習點時間
     * @since 2.0.0
     */
    public function getOutTime(){
        return $this->queryResultArray['out_target_time'];
    }

    /**
     * 離開學習點
     *
     * @since 2.0.0
     */
    public function toOut() {

        // 將資料庫內容標示已離開
        $db = new Database\DBStudy();
        $db->toOutTarget($this->id);

        // 將標的目前人數-1
        if($this->isEntity()) {
            $target = new Target\Target($this->getTargetId);
            $target->addMj(-1);
        }
    }
}
