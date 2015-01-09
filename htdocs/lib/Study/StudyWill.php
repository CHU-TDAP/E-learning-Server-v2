<?php
/**
 * StudyActivity.php
 */
namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBStudyActivity.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
require_once UELEARNING_LIB_ROOT.'/User/User.php';
use UElearning\Database;
use UElearning\Exception;
use UElearning\User;

/**
 * 預約學習階段類別
 *
 * 一個物件即代表這一個主題
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class StudyWill {
    /**
     * 預約學習階段流水號ID
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
     * @throw UElearning\Exception\AreaNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){

        // 從資料庫查詢
        $db = new Database\DBStudyActivity();
        $info = $db->queryWillActivity($this->id);

        // 判斷有沒有這個
        if( $info != null ) {
            $this->queryResultArray = $info;
        }
        else throw new Exception\StudyActivityWillNoFoundException($this->id);
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param int $inputID 預約學習流水號ID
     * @since 2.0.0
     */
    public function __construct($inputID){
        $this->id = $inputID;
        $this->getQuery();
    }

    // ========================================================================
    // 控制這次學習階段:

    /**
     * 撤銷這次預約
     *
     * @since 2.0.0
     */
    public function cancel(){
        $db = new Database\DBStudyActivity();
        $db->deleteWillActivity($this->id);
    }

    // ========================================================================
    // 取得資料:

    /**
     * 取得預約學習階段流水號ID
     *
     * @return int 預約學習階段流水號ID
     * @since 2.0.0
     */
    public function getId(){
        return $this->id;
    }

    /**
     * 取得是誰要預約學習的使用者物件
     *
     * @return \UElearning\User\User 使用者物件
     * @since 2.0.0
     */
    public function getUser(){

        $userId = $this->queryResultArray['user_id'];;
        return new User\User($userId);
    }

    /**
     * 取得是誰要預約學習
     *
     * @return string 使用者ID
     * @since 2.0.0
     */
    public function getUserId(){
        return $this->queryResultArray['user_id'];
    }

    /**
     * 設定這次是誰要預約學習
     *
     * @param string $user_id 使用者ID
     * @since 2.0.0
     */
    public function setUserById($user_id){
        $db = new Database\DBStudyActivity();
        // TODO: 檢查使用者有無存在
        $db->changeWillActivityData($this->id, 'user_id', $user_id);

        $this->getQuery();
    }

    ///**
    // * 取得這次預約是學哪個主題物件
    // *
    // * @return int 主題物件
    // * @since 2.0.0
    // */
    //public function getTheme(){
    //    $tId = $this->queryResultArray['theme_id'];
    //    return new Target\User($userId);;
    //}

    /**
     * 取得這次預約是學哪個主題
     *
     * @return int 主題ID
     * @since 2.0.0
     */
    public function getThemeId(){
        return $this->queryResultArray['theme_id'];
    }

    /**
     * 設定這次要預約學哪個主題
     *
     * @param int $theme_id 主題ID
     * @since 2.0.0
     */
    public function setThemeById($theme_id){
        $db = new Database\DBStudyActivity();
        // TODO: 檢查主體有無存在
        $db->changeWillActivityData($this->id, 'thime_id', $theme_id);

        $this->getQuery();
    }

    /**
     * 取得這次學習是預約在什麼時候開始
     *
     * @return string 開始學習預約時間
     * @since 2.0.0
     */
    public function getStartTime(){
        return $this->queryResultArray['start_time'];
    }

    /**
     * 設定這次學習是預約在什麼時候開始
     *
     * @param string $time 開始學習預約時間
     * @since 2.0.0
     */
    public function setStartTime($time){
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'start_time', $time);

        $this->getQuery();
    }

    /**
     * 取得這次學習預約什麼時候過期
     *
     * @return string 過期預約時間
     * @since 2.0.0
     */
    public function getExpiredTime(){
        return $this->queryResultArray['expired_time'];
    }

    /**
     * 設定這次學習預約什麼時候過期
     *
     * @param string $time 過期預約時間
     * @since 2.0.0
     */
    public function setExpiredTime($time){
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'expired_time', $time);

        $this->getQuery();
    }

    /**
     * 取得預約學習所需時間
     *
     * @return int 所需學習時間(分)
     * @since 2.0.0
     */
    public function getLearnTime(){
        return $this->queryResultArray['learn_time'];
    }

    /**
     * 設定預約學習所需時間
     *
     * @param int $min 所需學習時間(分)
     * @since 2.0.0
     */
    public function setLearnTime($min){
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'learn_time', $min);

        $this->getQuery();
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

    /**
     * 設定在這次學習時間已過，是否強制結束學習
     *
     * @param bool $value 是否在這次學習時間已過而強制結束學習
     * @since 2.0.0
     */
    public function setForceLearnTime($value) {
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'time_force', $value);

        $this->getQuery();
    }

    /**
     * 取得預約學習的導引風格
     *
     * @return int 將推薦幾個學習點
     * @since 2.0.0
     */
    public function getLearnStyle(){
        return $this->queryResultArray['learnStyle_mode'];
    }

    /**
     * 設定預約學習的導引風格
     *
     * @param int $num 將推薦幾個學習點
     * @since 2.0.0
     */
    public function setLearnStyle($num){
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'learnStyle_mode', $num);

        $this->getQuery();
    }

    /**
     * 在這次學習，是否拒絕使用者前往非推薦的學習點
     *
     * @return bool 是否拒絕前往非推薦的學習點
     * @since 2.0.0
     */
    public function isForceLearnStyle(){
        return $this->queryResultArray['learnStyle_force'];
    }

    /**
     * 預約本次學習，是否拒絕使用者前往非推薦的學習點
     *
     * @param bool $value 是否拒絕前往非推薦的學習點
     * @since 2.0.0
     */
    public function setForceLearnStyle($value){
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'learnStyle_force', $value);

        $this->getQuery();
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
     * 設定預約學習是否使用虛擬學習點
     *
     * @param bool $value 是否啟用虛擬學習點
     * @since 2.0.0
     */
    public function setEnableVirtual($value){
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'enable_virtual', $value);

        $this->getQuery();
    }

    /**
     * 取得預約學習的教材風格
     *
     * @return string 教材風格
     * @since 2.0.0
     */
    public function getMaterialStyle(){
        return $this->queryResultArray['material_mode'];
    }

    /**
     * 設定預約學習的教材風格
     *
     * @param string $value 教材風格
     * @since 2.0.0
     */
    public function setMaterialStyle($value){
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'material_mode', $value);

        $this->getQuery();
    }

    /**
     * 取得開始學習此預約前，是否允許讓學生自行更改設定
     *
     * @return bool 是否允許讓學生自行更改設定
     * @since 2.0.0
     */
    public function isLock() {
        return $this->queryResultArray['is_lock'];
    }

    /**
     * 設定開始學習此預約前，是否允許讓學生自行更改設定
     *
     * @param bool $value 是否允許讓學生自行更改設定
     * @since 2.0.0
     */
    function setLock($value) {
        $db = new Database\DBStudyActivity();
        $db->changeWillActivityData($this->id, 'is_lock', $value);

        $this->getQuery();
    }

    /**
     * 總共要學幾個學習點
     *
     * @return int 有幾個學習點
     * @since 2.0.0
     */
    public function getPointTotal() {
        return $this->queryResultArray['target_total'];
    }

    // ------------------------------------------------------------------------

    /**
     * 取得此預約建立時間
     *
     * @return string 建立時間
     * @since 2.0.0
     */
    public function getCreateTime(){
        return $this->queryResultArray['build_time'];
    }

    /**
     * 取得此預約修改時間
     *
     * @return string 修改時間
     * @since 2.0.0
     */
    public function getModifyTime(){
        return $this->queryResultArray['modify_time'];
    }

}
