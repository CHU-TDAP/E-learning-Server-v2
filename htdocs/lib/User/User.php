<?php
/**
 * User.php
 */

namespace UElearning\User;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/User/UserGroupAdmin.php';
require_once UELEARNING_LIB_ROOT.'/User/UserGroup.php';
require_once UELEARNING_LIB_ROOT.'/User/ClassGroupAdmin.php';
require_once UELEARNING_LIB_ROOT.'/User/ClassGroup.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Util/Password.php';
use UElearning\Exception;
use UElearning\Database;
use UElearning\Util;

/**
 * 使用者處理專用類別
 *
 * 一個物件即代表這一位使用者
 *
 * 建立此物件範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/User/User.php';
 *     use UElearning\User;
 *     use UElearning\Exception;
 *
 *     try {
 *         $user = new User\User('yuan');
 *
 *         $user->changePassword('123456');
 *         echo $user->isPasswordCorrect('123456');
 *
 *         echo 'NickName: '.$user->getNickName();
 *     }
 *     catch (Exception\UserNoFoundException $e) {
 *         echo 'No Found user: '. $e->getUserId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class User {

    /**
     * 使用者ID
     * @type string
     */
    protected $uId;

    // ------------------------------------------------------------------------

    /**
     * 查詢到此帳號的所有資訊的結果
     *
     * 由 $this->getQuery() 抓取資料表中所有資訊，並放在此陣列裡
     * @type array
     */
    protected $queryResultArray;

    /**
     * 從資料庫取得此帳號查詢
     *
     * @throw UElearning\Exception\UserNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){
        // 從資料庫查詢使用者
        $db       = new Database\DBUser();
        $userInfo = $db->queryUser($this->uId);

        // 判斷有沒有這位使用者
        if( $userInfo != null ) {
            $this->queryResultArray = $userInfo;
        }
        else throw new Exception\UserNoFoundException($this->uId);
    }

    /**
     * 從資料庫更新此帳號設定
     *
     * @since 2.0.0
     */
    protected function setUpdate($field, $value){
        /// 將新設定寫進資料庫裡
        $db = new Database\DBUser();
        $db->changeUserData($this->uId, $field, $value);
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param string $inputUID 使用者ID
     * @since 2.0.0
     */
    public function __construct($inputUID){
        $this->uId = $inputUID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得帳號名稱
     *
     * @return string 帳號名稱
     * @since 2.0.0
     */
    public function getId(){
        return $this->uId;
    }

    /**
     * 取得帳號名稱
     *
     * @return string 帳號名稱
     * @since 2.0.0
     */
    public function getUsername(){
        return $this->uId;
    }

    // ------------------------------------------------------------------------

    /**
     * 驗證密碼是否錯誤
     *
     * @param string $inputPasswd 密碼
     * @return bool true:密碼正確，false:密碼錯誤
     * @since 2.0.0
     */
    public function isPasswordCorrect($inputPasswd){
        $passUtil    = new Util\Password();
        $this_passwd = $this->queryResultArray['password'];
        return $passUtil->checkSameTryAll($this_passwd, $inputPasswd);
    }

    /**
     * 更改密碼
     *
     * @param  string $newPasswd     新密碼
     * @param  string $newPasswdMode 新密碼加密方式（可省略）
     * @return string 狀態回傳
     * @since 2.0.0
     */
    public function changePassword($newPasswd){
        // 進行密碼加密
        $passUtil = new Util\Password();
        $passwdEncrypted = $passUtil->encrypt($newPasswd);

        // 將新密碼寫進資料庫裡
        $this->setUpdate('password', $passwdEncrypted);
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

    /**
     * 取得帳號資訊修改時間
     *
     * @return string 修改時間
     * @since 2.0.0
     */
    public function getModifyTime(){
        return $this->queryResultArray['modify_time'];
    }
    // ------------------------------------------------------------------------

    /**
     * 取得所在群組
     *
     * @return string 群組ID
     * @since 2.0.0
     */
    public function getGroupID(){
        return $this->queryResultArray['group_id'];
    }

    /**
     * 取得所在群組顯示名稱
     *
     * @return string 群組名稱
     * @throw UElearning\Exception\GroupNoFoundException
     * @since 2.0.0
     */
    public function getGroupName(){

        return $this->queryResultArray['group_name'];
    }

    /**
     * 設定所在群組
     *
     * 範例:
     *
     *     try {
     *         $user = new User\User('yuan');
     *         try {
     *             $user->setGroup('student');
     *         }
     *         catch (Exception\GroupNoFoundException $e) {
     *             echo 'No Group to set: '. $e->getGroupId();
     *         }
     *         echo $user->getGroup();
     *     }
     *     catch (Exception\UserNoFoundException $e) {
     *         echo 'No Found user: '. $e->getUserId();
     *     }
     *
     * @param string $toGroup 群組ID
     * @throw UElearning\Exception\GroupNoFoundException
     * @since 2.0.0
     */
    public function setGroup($toGroup){

        // 檢查有此群組
        $groupAdmin = new UserGroupAdmin();
        if($groupAdmin->isExist($toGroup)) {
            $this->setUpdate('group_id', $toGroup);
        }
        else {
            throw new Exception\GroupNoFoundException($toGroup);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * 取得所在班級
     *
     * @return string 班級ID
     * @since 2.0.0
     */
    public function getClassId(){
        return $this->queryResultArray['class_id'];
    }

    /**
     * 取得所在班級名稱
     *
     * @return string 班級名稱
     * @throw UElearning\Exception\ClassNoFoundException
     * @since 2.0.0
     */
    public function getClassName(){
        return $this->queryResultArray['class_name'];
    }

    /**
     * 設定所在班級
     *
     * 範例:
     *
     *     try {
     *         $user = new User\User('yuan');
     *
     *         try {
     *             $user->setClass(1);
     *         }
     *         catch (Exception\ClassNoFoundException $e) {
     *             echo 'No Class to set: '. $e->getGroupId();
     *         }
     *     }
     *     catch (Exception\UserNoFoundException $e) {
     *         echo 'No Found user: '. $e->getUserId();
     *     }
     *
     * @param string $toClass 班級ID
     * @throw UElearning\Exception\ClassNoFoundException
     * @since 2.0.0
     */
    public function setClass($toClass){

        // 檢查有此群組
        if(isset($toClass)) {

            $classGroupAdmin = new ClassGroupAdmin();
            if($classGroupAdmin->isExist($toClass)) {
                $this->setUpdate('class_id', $toClass);
            }
            else {
                throw new Exception\ClassNoFoundException($toClass);
            }
        }
        else {
            $this->setUpdate('class_id', null);
        }

    }

    // ------------------------------------------------------------------------

    /**
     * 取得帳號啟用狀態
     *
     * @return bool 是否已啟用
     * @since 2.0.0
     */
    public function isEnable(){
        return $this->queryResultArray['enable'];
    }

    /**
     * 設定帳號啟用狀態
     *
     * @param bool $isActive 是否為啟用
     * @since 2.0.0
     */
    public function setEnable($isActive){
        // TODO: 防呆，至少一個帳號是啟用的

        // 將新設定寫進資料庫裡
        $this->setUpdate('enable', $isActive);
    }

    // ------------------------------------------------------------------------

    /**
     * 取得這個人的學習導引風格
     *
     * @return int 將推薦幾個學習點
     * @since 2.0.0
     */
    public function getLearnStyle(){
        return $this->queryResultArray['learnStyle_mode'];
    }

    /**
     * 設定這個人的學習導引風格
     *
     * @param int $style 將推薦幾個學習點
     * @since 2.0.0
     */
    public function setLearnStyle($style){

        if( $style >= 0 ) {
            $this->setUpdate('learnStyle_mode', $style);
        }
        else {
            throw new \UnexpectedValueException();
        }

    }

    /**
     * 取得這個人的教材風格
     *
     * @return string 教材風格
     * @since 2.0.0
     */
    public function getMaterialStyle(){
        return $this->queryResultArray['material_mode'];
    }

    /**
     * 設定這個人的教材風格
     *
     * @param string $style 教材風格
     * @since 2.0.0
     */
    public function setMaterialStyle($style){

        // TODO: 防呆- 無此教材
        $this->setUpdate('material_mode', $style);
    }

    /**
     * 取得是否允需此人進行非預約學習
     *
     * @return bool 是否允許非預約學習
     * @since  2.0.0
     */
    public function isEnableNoAppoint(){
        return $this->queryResultArray['enable_noAppoint'];
    }

    /**
     * 設定是否允需此人進行非預約學習
     *
     * @param bool 是否允許非預約學習
     * @since 2.0.0
     */
    public function setEnableNoAppoint($value){
        $this->setUpdate('enable_noAppoint', $value);
    }

    // ------------------------------------------------------------------------

    /**
     * 取得名稱
     *
     * @return string 依照有填入多少名字 <br />優先順序: 暱稱→真實名字→帳號名稱
     * @since 2.0.0
     */
    public function getName(){
        // TODO: 待修正-取得名稱
        if($this->getNickName() != "") {
            return $this->getNickName();
        }
        else if($this->getRealName() != "") {
            return $this->getRealName();
        }
        else {
            return $this->getUsername();
        }
    }

    // ------------------------------------------------------------------------

    /**
     * 取得暱稱
     *
     * @return string 暱稱
     * @since 2.0.0
     */
    public function getNickName(){
        return $this->queryResultArray['nickname'];
    }

    /**
     * 修改暱稱
     *
     * @param string $input 新暱稱
     * @since 2.0.0
     */
    public function setNickName($input){
        // 將新設定寫進資料庫裡
        $this->setUpdate('nickname', $input);
    }

    // ------------------------------------------------------------------------

    /**
     * 取得真實姓名
     *
     * @return string 真實姓名
     * @since 2.0.0
     */
    public function getRealName(){
        return $this->queryResultArray['realname'];
    }

    /**
     * 修改真實姓名
     *
     * @param string $input 新真實姓名
     * @since 2.0.0
     */
    public function setRealName($input){
        // 將新設定寫進資料庫裡
        $this->setUpdate('realname', $input);
    }

    // ------------------------------------------------------------------------

    /**
     * 取得帳號Email
     *
     * @return string 使用者資訊的Email
     * @since 2.0.0
     */
    public function getEmail(){
        return $this->queryResultArray['email'];
    }

    /**
     * 修改帳號Email
     *
     * @param string $input 新Email
     * @since 2.0.0
     */
    public function setEmail($input){
        // 將新設定寫進資料庫裡
        $this->setUpdate('email', $input);
    }

    // ------------------------------------------------------------------------

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

    // ========================================================================

    /**
     * 取得權限清單
     *
     * @return array 權限清單
     * @since  2.0.0
     */
     public function getPermissionList() {
        $thisGroup = new UserGroup($this->getQueryInfo("GID"));
        return $thisGroup->getPermissionList();
     }

    /**
     * 是否擁有此權限
     *
     * @param  string $permissionName 權限名稱
     * @return bool   是否擁有
     * @throw  UElearning\User\Exception\PermissionNoFoundException
     * @since  2.0.0
     */
     public function havePermission($permissionName) {
        $thisGroup = new UserGroup($this->getQueryInfo("GID"));
        return $thisGroup->havePermission($permissionName);
     }

     // ========================================================================

     /**
     * 取得登入次數
     *
     * @return int 登入了多少次
     * @since  2.0.0
     */
     public function getLoginTimes() {
         // TODO: 取得登入次數
     }

    /**
     * 目前有幾個裝置登入
     *
     * @return int 幾個已登入的登入階段
     * @since  2.0.0
     */
     public function getCurrentLoginTotal() {
         // TODO: 取得登入次數
     }

    // ========================================================================

    /**
     * 目前有哪些活動可以進行學習
     *
     * @return array 可以學習的活動清單
     * @since  2.0.0
     */
     public function getStudyActivity() {
         // TODO: 可以學習的活動清單
     }

}
