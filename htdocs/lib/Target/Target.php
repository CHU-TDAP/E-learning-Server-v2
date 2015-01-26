<?php
/**
 * Target.php
 */

namespace UElearning\Target;

require_once UELEARNING_LIB_ROOT.'/Database/DBTarget.php';
require_once UELEARNING_LIB_ROOT.'/Database/DBMaterial.php';
require_once UELEARNING_LIB_ROOT.'/Target/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 標的專用類別
 *
 * 一個物件即代表一個標的
 *
 * 使用範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/Target/Target.php';
 *     use UElearning\Target;
 *     use UElearning\Exception;
 *
 *     try{
 *         $target = new Target\Target(3);
 *         echo $target->getId();
 *         echo $target->getAreaId();
 *         echo $target->getHallId();
 *         echo $target->getNumber();
 *         echo $target->getName();
 *         echo $target->getMapUrl();
 *         echo $target->getLearnTime();
 *         echo $target->getPLj();
 *         echo $target->getMj();
 *         echo $target->isFullPeople();
 *         echo $target->getVacancyPeople();
 *         echo $target->getS();
 *         echo $target->getFj();
 *
 *     }
 *     catch (Exception\TargetNoFoundException $e) {
 *         echo 'No Found target: '. $e->getId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class Target {

    /**
     * 標的ID
     * @type int
     */
    protected $tId;

    // ------------------------------------------------------------------------

    /**
     * 查詢到此標的的所有資訊的結果
     *
     * 由 $this->getQuery() 抓取資料表中所有資訊，並放在此陣列裡
     * @type array
     */
    protected $queryResultArray;

    /**
     * 從資料庫取得此標的查詢
     *
     * @throw UElearning\Exception\TargetNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){
        // 從資料庫查詢此標的
        $db         = new Database\DBTarget();
        $targetInfo = $db->queryTarget($this->tId);

        // 判斷有沒有這個標的
        if( $targetInfo != null ) {
            $this->queryResultArray = $targetInfo;
        }
        else throw new Exception\TargetNoFoundException($this->tId);
    }

    /**
     * 從資料庫更新此標的設定
     *
     * @since 2.0.0
     */
    protected function setUpdate($field, $value){
        // 將新設定寫進資料庫裡
        $db = new Database\DBTarget();
        $db->changeTargetData($this->tId, $field, $value);
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param int $inputTID 標的ID
     * @since 2.0.0
     */
    public function __construct($inputTID){
        $this->tId = (int)$inputTID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得標的ID
     *
     * @return int 標的ID
     * @since 2.0.0
     */
    public function getId(){
        return $this->tId;
    }


    /**
     * 取得標的所在的區域ID
     *
     * @return int 標的所在的區域ID
     * @since 2.0.0
     */
    public function getAreaId(){
        return $this->queryResultArray['area_id'];
    }

    /**
     * 取得標的所在的廳ID
     *
     * @return int 標的所在的廳ID
     * @since 2.0.0
     */
    public function getHallId(){
        return $this->queryResultArray['hall_id'];
    }

    /**
     * 取得標的地圖上的編號
     *
     * @return int 地圖上的標的編號
     * @since 2.0.0
     */
    public function getNumber(){
        return $this->queryResultArray['target_number'];
    }
    // ========================================================================

    /**
     * 取得標的名稱
     *
     * @return string 標的名稱
     * @since 2.0.0
     */
    public function getName(){
        return $this->queryResultArray['name'];
    }

    ///**
    // * 設定標的名稱
    // *
    // * @param string $name 標的名稱
    // * @since 2.0.0
    // */
    //public function setName($name){
    //    $this->setUpdate('name', $name);
    //}

    // ========================================================================

    /**
     * 取得標的的地圖圖片檔路徑
     *
     * @return string 地圖圖片檔路徑
     * @since 2.0.0
     */
    public function getMapUrl(){
        return $this->queryResultArray['map_url'];
    }

    /**
     * 取得標的的教材路徑
     *
     * @param bool   $isEntity 是否為實體教材
     * @param string $mode     教材種類
     * @return string 教材檔案路徑
     * @since 2.0.0
     */
    public function getMaterialUrl($isEntity, $mode){

        $db = new Database\DBMaterial();
        $query = $db->queryAllMaterialByTargetId($this->tId);

        if(count($query) > 0) {

            foreach($query as $thisData) {
                if($thisData['is_entity'] != 0) {
                    $thisEntiry = true;
                }
                else { $thisEntiry = false; }

                if($thisEntiry==$isEntity && $thisData['mode'] == $mode) {
                    return $thisData['url'];
                    break;
                }
            }
        }
        else {
            return null;
        }
    }

    // ========================================================================

    /**
     * 取得預估的學習時間
     *
     * @return int 預估的學習時間(分)
     * @since 2.0.0
     */
    public function getLearnTime(){
        return $this->queryResultArray['learn_time'];
    }

    /**
     * 取得學習標的的人數限制
     *
     * @return int 學習標的的人數限制
     * @since 2.0.0
     */
    public function getPLj(){
        return $this->queryResultArray['PLj'];
    }

    /**
     * 取得學習標的的人數限制
     *
     * @return int 學習標的的人數限制
     * @since 2.0.0
     */
    public function getMaxPeople(){
        return $this->getPLj();
    }

    // ------------------------------------------------------------------------

    /**
     * 取得學習標的目前人數
     *
     * @return int 學習標的目前人數
     * @since 2.0.0
     */
    public function getMj(){
        return $this->queryResultArray['Mj'];
    }

    /**
     * 設定學習標的目前人數
     *
     * @param int $number 學習標的目前人數
     * @since 2.0.0
     */
    public function setMj($number){
        $this->setUpdate('Mj', $number);
    }

    /**
     * 增加學習標的目前人數
     *
     * 若要減少可直接在參數內帶入負值
     *
     * @param int $number 學習標的目前人數調整值
     * @return int 學習標的目前人數
     * @since 2.0.0
     */
    public function addMj($number){
        $setedNum = $this->queryResultArray['Mj']+$number;
        if($setedNum < 0) $setedNum = 0;
        $this->setUpdate('Mj', $setedNum);
    }

    /**
     * 取得學習標的目前人數
     *
     * @return int 學習標的目前人數
     * @since 2.0.0
     */
    public function getCurrentPeople(){
        return $this->getMj();
    }

    /**
     * 判斷目前標的人數是否為零
     *
     * @return bool true/此標的目前人數是空的,false/此標的目前人數不是空的
     * @since 2.0.0
     */
    public function isNumberOfPeopleZero(){
        if($this->getCurrentPeople() == 0) return true;
        else return false;
    }

    /**
     * 增加學習標的目前人數
     *
     * 若要減少可直接在參數內帶入負值
     *
     * @param int $number 學習標的目前人數調整值
     * @return int 學習標的目前人數
     * @since 2.0.0
     */
    public function addCurrentPeople($number){
        return $this->addMj($number);
    }

    /**
     * 設定學習標的目前人數
     *
     * @param int $number 學習標的目前人數
     * @since 2.0.0
     */
    public function setCurrentPeople($number){
        $this->setMj($number);
    }

    /**
     * 取得此標的還剩下人可容納
     *
     * @return int 此標的還剩下人可容納
     * @since 2.0.0
     */
    public function getVacancyPeople() {
        return $this->getPLj() - $this->getMj();
    }

    /**
     * 目前此標的人數是否已滿
     *
     * @return bool 目前人數已滿
     * @since 2.0.0
     */
    public function isFullPeople(){
        if($this->getFj() >= 1) return true;
        else return false;
    }

    // ------------------------------------------------------------------------

    /**
     * 取得學習標的飽和率上限
     *
     * @return int 學習標的飽和率上限
     * @since 2.0.0
     */
    public function getS(){
        return $this->queryResultArray['S'];
    }

    /**
     * 取得學習標的滿額指標
     *
     * @return int 學習標的滿額指標
     * @since 2.0.0
     */
    public function getFj(){
        return $this->queryResultArray['Fj'];
    }

}
