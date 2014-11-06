<?php
/**
 * Hall.php
 */

namespace UElearning\Target;

require_once UELEARNING_LIB_ROOT.'/Database/DBTarget.php';
require_once UELEARNING_LIB_ROOT.'/Target/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 廳專用類別
 *
 * 一個物件即代表一塊廳
 *
 * 使用範例:
 *
 *     try{
 *         $target = new Target\Hall(1);
 *         echo $target->getId();
 *         echo $target->getName();
 *         echo $target->getMapUrl();
 *         echo $target->getIntroduction();
 *
 *     }
 *     catch (Exception\HallNoFoundException $e) {
 *         echo 'No Found area: '. $e->getId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class Hall {

    /**
     * 廳ID
     * @type int
     */
    protected $hId;

    // ------------------------------------------------------------------------

    /**
     * 查詢到此廳的所有資訊的結果
     *
     * 由 $this->getQuery() 抓取資料表中所有資訊，並放在此陣列裡
     * @type array
     */
    protected $queryResultArray;

    /**
     * 從資料庫取得此廳查詢
     *
     * @throw UElearning\Exception\HallNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){
        // 從資料庫查詢
        $db         = new Database\DBTarget();
        $hallInfo = $db->queryHall($this->hId);

        // 判斷有沒有這個
        if( $hallInfo != null ) {
            $this->queryResultArray = $hallInfo;
        }
        else throw new Exception\HallNoFoundException($this->hId);
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
    public function __construct($inputHID){
        $this->hId = $inputHID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得廳ID
     *
     * @return int 廳ID
     * @since 2.0.0
     */
    public function getId(){
        return $this->hId;
    }

    /**
     * 取得廳名稱
     *
     * @return string 廳名稱
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
