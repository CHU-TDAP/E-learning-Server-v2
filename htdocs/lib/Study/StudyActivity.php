<?php
/**
 * StudyActivity.php
 */

namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBStudyActivity.php';
require_once UELEARNING_LIB_ROOT.'/User/User.php';
require_once UELEARNING_LIB_ROOT.'/Study/Theme.php';
require_once UELEARNING_LIB_ROOT.'/Study/StudyManager.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
use UElearning\Database;
use UElearning\Exception;
use UElearning\User;

/**
 * 學習階段類別
 *
 * 一個物件即代表這一個學習活動
 *
 * 使用範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/Study/StudyActivity.php';
 *     use UElearning\Study;
 *     use UElearning\Exception;
 *
 *     try{
 *         $sact = new Study\StudyActivity(8);
 *
 *         echo $sact->getId();
 *         echo $sact->getUserId();
 *         echo $sact->getThemeId();
 *         echo $sact->getLearnStyle();
 *         echo $sact->isForceLearnStyle();
 *         echo $sact->getMaterialStyle();
 *         $sact->setDelay(23);
 *         echo $sact->getDelay();
 *         echo $sact->isLearning();
 *
 *         $sact->finishActivity();
 *     }
 *     catch (Exception\StudyActivityNoFoundException $e) {
 *         echo 'No Found learnActivity: '. $e->getId();
 *     }
 *     catch (Exception\StudyActivityFinishedException $e) {
 *         echo 'The learnActivity is over: '. $e->getId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class StudyActivity {
    /**
     * 學習階段流水號ID
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
     * @throw \UElearning\Exception\StudyActivityNoFoundException
     * @since 2.0.0
     */
    protected function getQuery() {

        // 從資料庫查詢
        $db = new Database\DBStudyActivity();
        $info = $db->queryActivity($this->id);

        // 判斷有沒有這個
        if( $info != null ) {
            $this->queryResultArray = $info;
        }
        else throw new Exception\StudyActivityNoFoundException($this->id);
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param int $inputID 學習階段流水號ID
     * @since 2.0.0
     */
    public function __construct($inputID) {
        $this->id = $inputID;
        $this->getQuery();
    }

    // ========================================================================
    // 控制這次學習階段時間:

    /**
     * 結束這次學習
*    *
     * @throw \UElearning\Exception\StudyActivityFinishedException
     * @since 2.0.0
     */
    public function finishActivity() {

        // 此活動還在進行中
        if($this->isLearning()) {

            // 確定目前有無進行中的學習點
            $cur_inTId = $this->getCurrentInTarget();
            $cur_inEnTId = $this->getCurrentEnteringTargetId();
            if($cur_inTId) { $this->toOutTarget($cur_inTId); }
            if($cur_inEnTId) { $this->outEnteringInTarget($cur_inEnTId); }


            // 標記結束時間
            $db = new Database\DBStudyActivity();
            $db->setEndTimeNow($this->id);
        }
        // 此活動已結束
        else throw new Exception\StudyActivityFinishedException($this->id);
    }

    /**
     * 撤銷這次學習
     *
     * @since 2.0.0
     */
    public function cancelActivity() {
        //return $this->queryResultArray['name'];
        // TODO: cancelActivity
    }

    /**
     * 此學習階段是否正在學習中
     */
    public function isLearning() {
        if(!isset($this->queryResultArray['end_time'])) return true;
        else return false;
    }

    // ========================================================================

    /**
     * 總共要學幾個學習點
     *
     * @return int 有幾個學習點
     * @since 2.0.0
     */
    public function getPointTotal() {
        return $this->queryResultArray['target_total'];
    }

    /**
     * 取得已經學過幾個學習點
     *
     * @return int 已學過幾個學習點
     * @since 2.0.0
     */
    public function getLearnedPointTotal() {
        return $this->queryResultArray['learned_total'];
    }

    /**
     * 取得還剩下幾個學習點還沒學
     *
     * @return int 還剩下幾個學習點
     * @since 2.0.0
     */
    public function getRemainingPointTotal() {
        $total = $this->getPointTotal();
        $learned = $this->getLearnedPointTotal();

        return ($total-1) - $learned;
    }

    // ========================================================================
    // 取得資料:

    /**
     * 取得學習階段流水號ID
     *
     * @return int 學習階段流水號ID
     * @since 2.0.0
     */
    public function getId() {
        return (int)$this->id;
    }

    /**
     * 取得這次是誰在學習物件
     *
     * @return \UElearning\User\User 使用者物件
     * @since 2.0.0
     */
    public function getUser() {

        $userId = $this->queryResultArray['user_id'];;
        return new User\User($userId);
    }

    /**
     * 取得這次是誰在學習
     *
     * @return string 使用者ID
     * @since 2.0.0
     */
    public function getUserId() {
        return $this->queryResultArray['user_id'];
    }

    ///**
    // * 取得這次是學哪個主題物件
    // *
    // * @return int 主題物件
    // * @since 2.0.0
    // */
    //public function getTheme() {
    //    $tId = $this->queryResultArray['theme_id'];
    //    return new Target\User($userId);;
    //}

    /**
     * 取得這次是學哪個主題
     *
     * @return int 主題ID
     * @since 2.0.0
     */
    public function getThemeId() {
        return $this->queryResultArray['theme_id'];
    }

    /**
     * 取得這次是的主題名稱
     *
     * @return string 主題名稱
     * @since 2.0.0
     */
    public function getThemeName() {
        return $this->queryResultArray['theme_name'];
    }

    // ------------------------------------------------------------------------
    // 時間控制:

    /**
     * 取得這次學習是什麼時候開始的
     *
     * @return string 開始學習時間
     * @since 2.0.0
     */
    public function getStartTime() {
        return $this->queryResultArray['start_time'];
    }

    /**
     * 取得這次學習的過期時間
     *
     * @return string 過期時間
     * @since 2.0.0
     */
    public function getExpiredTime() {
        return $this->queryResultArray['expired_time'];
    }

    /**
     * 取得這次學習是什麼時候結束的
     *
     * @return string 結束學習時間
     * @since 2.0.0
     */
    public function getEndTime() {
        return $this->queryResultArray['end_time'];
    }

    /**
     * 取得這次學習所需時間
     *
     * @return int 所需學習時間(分)
     * @since 2.0.0
     */
    public function getLearnTime() {
        return $this->queryResultArray['learn_time'];
    }

    /**
     * 取得這次可實際學習時間（包含延時）
     *
     * @return int 可實際學習時間(分)
     * @since 2.0.0
     */
    public function getRealLearnTime() {

        $learnTime = $this->queryResultArray['learn_time'];
        $delay = $this->queryResultArray['delay'];
        return $learnTime + $delay;
    }

    /**
     * 取得這次學習還剩下多少學習時間
     *
     * @return int 剩下的學習時間(分)
     * @since 2.0.0
     */
    public function getRemainingTime() {

        // 計算總共學習時間（包含延長時間）
        $haveTime = $this->getRealLearnTime();

        // 現在時間-開始時間 = 已經過了多久
        $nowDate = strtotime("now");
        $startDate = strtotime($this->getStartTime());
        $elapsedDate = $nowDate - $startDate;
        $elapsedMinute = (int)(date('H', $elapsedDate)*60);
        // 取得目前時區差（分鐘）
        $timeZoneMinute = ((int)date('O')/100)*60;
        // 扣除時區時間差
        $elapsedMinute = $elapsedMinute - $timeZoneMinute;
        // 加上分鐘
        $elapsedMinute = $elapsedMinute + (int)(date('i', $elapsedDate));

        // 可學習時間 - 已經過了多久 = 剩餘時間
        $remainingMinute = $haveTime - $elapsedMinute;

        return (int)$remainingMinute;
    }

    /**
     * 取得這次學習時間要延長多久
     *
     * @return int 延長時間(分)
     * @since 2.0.0
     */
    public function getDelay() {
        return $this->queryResultArray['delay'];
    }

    /**
     * 設定這次學習時間要延長多久
     *
     * @param int $minute 延長時間(分)
     * @throw \UElearning\Exception\StudyActivityNoFoundException
     * @since 2.0.0
     */
    public function setDelay($minute) {

        // 此活動還在進行中
        if($this->isLearning()) {

            $db = new Database\DBStudyActivity();
            $db->setDelay($this->id, $minute);

            $this->getQuery();
        }
        // 此活動已結束
        else throw new Exception\StudyActivityFinishedException($this->id);
    }

    /**
     * 設定累加這次學習時間要延長多久
     *
     * @param int $minute 延長時間(分)
     * @throw \UElearning\Exception\StudyActivityNoFoundException
     * @since 2.0.0
     */
    public function addDelay($minute) {

        // 此活動還在進行中
        if($this->isLearning()) {

            $setMinute = $this->queryResultArray['delay'] + $minute;

            $db = new Database\DBStudyActivity();
            $db->setDelay($this->id, $setMinute);

            // TODO: 防呆-不能設的比開始時間還早

            $this->getQuery();
        }
        // 此活動已結束
        else throw new Exception\StudyActivityFinishedException($this->id);
    }

    /**
     * 在這次學習時間已過，是否強制結束學習
     *
     * @return bool 是否在這次學習時間已過而強制結束學習
     * @since 2.0.0
     */
    public function isForceLearnTime() {
        return $this->queryResultArray['time_force'];
    }

    // ------------------------------------------------------------------------

    /**
     * 取得這次學習的導引風格
     *
     * @return int 將推薦幾個學習點
     * @since 2.0.0
     */
    public function getLearnStyle() {
        return $this->queryResultArray['learnStyle_mode'];
    }

    /**
     * 在這次學習，是否拒絕使用者前往非推薦的學習點
     *
     * @return bool 是否拒絕前往非推薦的學習點
     * @since 2.0.0
     */
    public function isForceLearnStyle() {
        return $this->queryResultArray['learnStyle_force'];
    }

    /**
     * 取得預約學習是否使用虛擬學習點
     *
     * @return bool 是否啟用虛擬學習點
     * @since 2.0.0
     */
    public function isEnableVirtual(){
        return $this->queryResultArray['enable_virtual'];
    }

    /**
     * 取得這次學習的教材風格
     *
     * @return string 教材風格
     * @since 2.0.0
     */
    public function getMaterialStyle() {
        return $this->queryResultArray['material_mode'];
    }

    // ========================================================================

    /**
     * 取得此主題的標的起始點
     *
     * @return int 標的編號
     * @since 2.0.0
     */
    public function getStartTargetId(){
        $theme = new Theme( $this->getThemeId() );
        return $theme->getStartTargetId();
    }

    // ========================================================================

    /**
     * 取得目前已進入的學習點
     *
     * @return int 標的編號，若無則null
     */
    public function getCurrentInTarget() {

        // 活動編號
        $saId = $this->id;

        $sct = new StudyManager();
        return $sct->getCurrentInTargetId($saId);
    }

    /**
     * 取得目前行進中的學習點
     *
     * @return int 標的編號，若無則null
     */
    public function getCurrentEnteringTargetId() {

        // 活動編號
        $saId = $this->id;

        $sct = new StudyManager();
        return $sct->getCurrentEnteringTargetId($saId);
    }

    /**
     * 此標的是否已學習過
     *
     * @param string $target_id 標的編號
     * @return bool 是否已學習過
     */
    public function isTargetLearned($target_id) {

        // 活動編號
        $saId = $this->id;

        $sct = new StudyManager();
        return $sct->isTargetLearned($saId, $target_id);
    }

    /**
     * 進入標的
     *
     * @param int  $target_id 標的編號
     * @param bool $is_entity 是否為現場學習
     * @throw UElearning\Exception\InLearningException
     * return int 進出紀錄編號
     */
    public function toInTarget($target_id, $is_entity) {

        // 活動編號
        $saId = $this->id;

        $sct = new StudyManager();
        // 進入學習點
        try{
            return $sct->toInTarget($saId, $target_id, $is_entity);
        }
        // 若狀態為正在標的內學習時，送出例外
        catch (Exception\InLearningException $e) {
            throw $e;
        }

    }

    /**
     * 離開標的
     *
     * @param int $target_id 標的編號
     * @throw UElearning\Exception\NoInLearningException
     */
    public function toOutTarget($target_id) {

        // 活動編號
        $saId = $this->id;

        $sct = new StudyManager();
        // 離開學習點
        $sct->toOutTarget($saId, $target_id);
    }

    /**
     * 行進中，準備進入的學習點
     *
     * @param int  $target_id 標的編號
     * @param bool $is_entity 是否為現場學習
     * @throw UElearning\Exception\InLearningException
     * return int 進出紀錄編號
     */
    public function enteringInTarget($target_id, $is_entity) {

        // 活動編號
        $saId = $this->id;

        $sct = new StudyManager();
        // 進入學習點
        try{
            return $sct->enteringInTarget($saId, $target_id, $is_entity);
        }
        // 若狀態為正在標的內學習時，送出例外
        catch (Exception\InLearningException $e) {
            throw $e;
        }

    }

    /**
     * 取消行進中，準備進入的學習點
     *
     * @param int  $target_id 標的編號
     * @param bool $is_entity 是否為現場學習
     * @throw UElearning\Exception\InLearningException
     * return int 進出紀錄編號
     */
    public function outEnteringInTarget($target_id) {

        // 活動編號
        $saId = $this->id;

        $sct = new StudyManager();
        // 進入學習點
        try{
            return $sct->outEnteringInTarget($saId, $target_id);
        }
        // 若狀態為正在標的內學習時，送出例外
        catch (Exception\InLearningException $e) {
            throw $e;
        }

    }
}
