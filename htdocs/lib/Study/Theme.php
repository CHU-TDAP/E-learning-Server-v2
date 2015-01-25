<?php
/**
 * Theme.php
 */

namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBTheme.php';
require_once UELEARNING_LIB_ROOT.'/Database/DBRecommand.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 主題專用類別
 *
 * 一個物件即代表這一個主題
 *
 * 使用範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/Study/Theme.php';
 *     use UElearning\Study;
 *     use UElearning\Exception;
 *
 *     try{
 *         $theme = new Study\Theme(1);
 *
 *         echo $theme->getId();
 *         echo $theme->getName();
 *         echo $theme->getIntroduction();
 *         echo $theme->getLearnTime();
 *         echo $theme->getCreateTime();
 *         echo $theme->getModifyTime();
 *
 *     }
 *     catch (Exception\ThemeNoFoundException $e) {
 *         echo 'No Found theme: '. $e->getId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class Theme {

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
        // TODO: 從資料庫取得查詢
        //// 從資料庫查詢
        $db = new Database\DBTheme();
        $info = $db->queryTheme($this->id);

        // 判斷有沒有這個
        if( $info != null ) {
            $this->queryResultArray = $info;
        }
        else throw new Exception\ThemeNoFoundException($this->id);
    }

    /**
     * 從資料庫更新設定
     *
     * @since 2.0.0
     */
    protected function setUpdate($field, $value){
        // TODO: 從資料庫更新設定
        // 將新設定寫進資料庫裡
        //$db = new Database\DBTarget();
        //$db->changeTargetData($this->tId, $field, $value);
        //$this->getQuery();
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param int $inputID 主題ID
     * @since 2.0.0
     */
    public function __construct($inputID){
        $this->id = $inputID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得主題ID
     *
     * @return int 主題ID
     * @since 2.0.0
     */
    public function getId(){
        return $this->id;
    }

    /**
     * 取得主題名稱
     *
     * @return string 主題名稱
     * @since 2.0.0
     */
    public function getName(){
        return $this->queryResultArray['name'];
    }

    /**
     * 取得主題介紹
     *
     * @return string 主題介紹
     * @since 2.0.0
     */
    public function getIntroduction(){
        return $this->queryResultArray['introduction'];
    }

    /**
     * 取得此主題學習所需時間
     *
     * @return int 所需學習時間(分)
     * @since 2.0.0
     */
    public function getLearnTime(){
        return $this->queryResultArray['learn_time'];
    }

    /**
     * 取得此主題的標的起始點
     *
     * @return int 標的編號
     * @since 2.0.0
     */
    public function getStartTargetId(){
        return (int)$this->queryResultArray['start_target_id'];
    }

    /**
     * 取得建立時間
     *
     * @return string 建立時間
     * @since 2.0.0
     */
    public function getCreateTime(){
        return $this->queryResultArray['build_time'];
    }

    /**
     * 取得修改時間
     *
     * @return string 修改時間
     * @since 2.0.0
     */
    public function getModifyTime(){
        return $this->queryResultArray['modify_time'];
    }

    /**
     * 取得下一個標的所屬主題的權重
     * @param string $next_point
     * @return int weight 權重
     * @since 2.0.0
     */
    public function getWeightByNextTarget($next_point){
        $belong = new Database\DBRecommand();
        $weight = $belong->queryBelongByID($next_point,$this->id);
        return $weight['weight'];
    }

}
