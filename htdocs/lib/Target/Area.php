<?php
/**
 * Area.php
 */

namespace UElearning\Target;

require_once UELEARNING_LIB_ROOT.'/Database/DBTarget.php';
require_once UELEARNING_LIB_ROOT.'/Target/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 區域專用類別
 *
 * 一個物件即代表一塊區域
 *
 * 範例:
 *
 *     try{
 *         $target = new Target\Area(3);
 *         echo $target->getId();
 *         echo $target->getHallId();
 *         echo $target->getFloor();
 *         echo $target->getNumber();
 *         echo $target->getName();
 *         echo $target->getMapUrl();
 *
 *     }
 *     catch (Exception\AreaNoFoundException $e) {
 *         echo 'No Found area: '. $e->getId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class Area {

    /**
     * 標的ID
     * @type int
     */
    protected $aId;

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
     * @throw UElearning\Exception\AreaNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){
        // 從資料庫查詢
        $db         = new Database\DBTarget();
        $areaInfo = $db->queryArea($this->aId);

        // 判斷有沒有這個
        if( $areaInfo != null ) {
            $this->queryResultArray = $areaInfo;
        }
        else throw new Exception\AreaNoFoundException($this->aId);
    }

    /**
     * 從資料庫更新此標的設定
     *
     * @since 2.0.0
     */
    protected function setUpdate($field, $value){
        // 將新設定寫進資料庫裡
        //$db = new Database\DBTarget();
        //$db->changeTargetData($this->tId, $field, $value);
        //$this->getQuery();
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param int $inputAID 區域ID
     * @since 2.0.0
     */
    public function __construct($inputAID){
        $this->aId = $inputAID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得區域ID
     *
     * @return int 區域ID
     * @since 2.0.0
     */
    public function getId(){
        return $this->aId;
    }

    /**
     * 取得區域所在的廳ID
     *
     * @return int 區域所在的廳ID
     * @since 2.0.0
     */
    public function getHallId(){
        return $this->queryResultArray['hall_id'];
    }

    /**
     * 取得區域所在樓層
     *
     * @return int 區域所在樓層
     * @since 2.0.0
     */
    public function getFloor(){
        return $this->queryResultArray['floor'];
    }

    /**
     * 取得區域地圖上的編號
     *
     * @return int 地圖上的區域編號
     * @since 2.0.0
     */
    public function getNumber(){
        return $this->queryResultArray['area_number'];
    }

    /**
     * 取得區域名稱
     *
     * @return string 區域名稱
     * @since 2.0.0
     */
    public function getName(){
        return $this->queryResultArray['name'];
    }

    ///**
    // * 設定區域名稱
    // *
    // * @param string $name 區域名稱
    // * @since 2.0.0
    // */
    //public function setName($name){
    //    $this->setUpdate('name', $name);
    //}

    // ========================================================================

    /**
     * 取得區域的地圖圖片檔路徑
     *
     * @return string 地圖圖片檔路徑
     * @since 2.0.0
     */
    public function getMapUrl(){
        return $this->queryResultArray['map_url'];
    }

    /**
     * 取得區域簡介
     *
     * @return string 區域簡介
     * @since 2.0.0
     */
    public function getIntroduction(){
        return $this->queryResultArray['introduction'];
    }

}
