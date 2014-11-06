<?php
/**
 * UserGroup.php
 */

namespace UElearning\User;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/User/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 使用者群組類別
 *
 * 一個物件即代表這一個使用者群組
 *
 * 範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/User/UserGroup.php'
 *     use UElearning\User;
 *     use UElearning\Exception;
 *
 *     try {
 *         $group = new User\UserGroup('testG');
 *         echo $group->getName();
 *         $group->setName('測試用');
 *         echo $group->getName();
 *     }
 *     catch (Exception\GroupNoFoundException $e) {
 *         echo 'No Found group: '. $e->getGroupId();
 *     }
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class UserGroup {

    /**
     * 群組ID
     * @type string
     */
    protected $gId;

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
     * @throw UElearning\Exception\GroupNoFoundException
     * @since 2.0.0
     */
    protected function getQuery(){

        // 從資料庫查詢群組
        $db       = new Database\DBUser();
        $groupInfo = $db->queryGroup($this->gId);

        // 判斷有沒有這個群組
        if( $groupInfo != null ) {
            $this->queryResultArray = $groupInfo;
        }
        else throw new Exception\GroupNoFoundException($this->gId);
    }

    /**
     * 從資料庫更新此群組設定
     *
     * @since 2.0.0
     */
    protected function setUpdate($field, $value){

        /// 將新設定寫進資料庫裡
        $db = new Database\DBUser();
        $db->changeGroupData($this->gId, $field, $value);
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 建構子
     *
     * @param string $inputGID 群組ID
     * @since 2.0.0
     */
    public function __construct($inputGID){
        $this->gId = $inputGID;
        $this->getQuery();
    }

    // ========================================================================

    /**
     * 取得群組ID
     *
     * @return string 群組ID
     * @since 2.0.0
     */
    public function getID(){
        return $this->gId;
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

    // ========================================================================

    /**
     * 取得權限清單
     *
     * @return array 權限清單
     */
    public function getPermissionList() {
        // TODO: 取得權限清單

    }

    /**
     * 是否擁有此權限
     *
     * @param  string $permissionName 權限名稱
     * @return bool   是否擁有
     * @throw UElearning\Exception\PermissionNoFoundException
     * @since 2.0.0
     */
    public function havePermission($permissionName) {
        switch($permissionName) {
            case 'server_admin':
            case 'serverAdmin':
            case 'ServerAdmin':
                return $this->queryResultArray['auth_admin'];
                break;

            case 'client_admin':
            case 'clientAdmin':
            case 'ClientAdmin':
                return $this->queryResultArray['auth_clientAdmin'];
                break;

            default:
                throw new Exception\PermissionNoFoundException('$permissionName');
                return false;
        }
    }

    /**
     * 設定擁有此權限
     *
     * @param  string $permissionName 權限名稱
     * @param  string $setBool        是否給予
     * @return bool   是否擁有
     * @throw UElearning\Exception\PermissionNoFoundException
     * @since 2.0.0
     */
    public function setPermission($permissionName, $setBool) {
        switch($permissionName) {
            case 'server_admin':
            case 'serverAdmin':
            case 'ServerAdmin':
                $this->setUpdate('auth_admin', $setBool);
                break;

            case 'client_admin':
            case 'clientAdmin':
            case 'ClientAdmin':
                $this->setUpdate('auth_clientAdmin', $setBool);
                break;
            default:
                throw new Exception\PermissionNoFoundException('$permissionName');
        }
    }

}
