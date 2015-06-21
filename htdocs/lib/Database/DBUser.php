<?php
/**
 * DBUser.php
 *
 * 此檔案針對使用者資料表的功能。
 */

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

/**
 * 使用者帳號資料表
 *
 * 對資料庫中的使用者資料表進行操作。
 *
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBUser extends Database {

    const FORM_USER = 'User';

    /**
     * 新增一個使用者
     *
     * 範例:
     *
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
     *     use UElearning\Database;
     *
     *     try {
     *         $db = new Database\DBUser();
     *
     *         $db->insertUser(
     *             array(
     *                 'user_id'            => 'eric',
     *                 'password'           => 'passwd',
     *                 'group_id'           => 'user',
     *                 'enable'             => true,
     *                 'enable_noAppoint'   => true
     *             )
     *         );
     *
     *         echo 'Finish';
     *     }
     *
     *
     *     // 若設定的DBMS不被支援 則丟出例外
     *     catch (Exception\DatabaseNoSupportException $e) {
     *         echo 'No Support in ',  $e->getType();
     *     } catch (Exception $e) {
     *         echo 'Caught other exception: ',  $e->getMessage();
     *         echo '<h2>'. $e->getCode() .'</h2>';
     *     }
     *
     * @param array $array 使用者資料陣列，格式為:
     *     array(
     *         'user_id'            => <帳號名稱>,
     *         'password'           => <密碼>,
     *         'group_id'           => <群組>,
     *         'class_id'           => <班級>,
     *         'enable'             => <啟用>,
     *         'learnStyle_mode'    => <偏好學習導引模式>,
     *         'material_mode'      => <偏好教材模式>,
     *         'enable_noAppoint'   => <是否允許非預約學習>,
     *         'nickname'           => <暱稱>,
     *         'realname'           => <真實姓名>,
     *         'email'              => <電子郵件地址>,
     *         'memo'               => <備註>
     *     );
     *
     * @since 2.0.0
     */
    public function insertUser($array){


        if( !isset($array['class_id']) ){
            $array['class_id'] = null;
        }
        if( !isset($array['learnStyle_mode']) ){
            $array['learnStyle_mode'] = null;
        }
        if( !isset($array['material_mode']) ){
            $array['material_mode'] = null;
        }
        if( !isset($array['nickname']) ){
            $array['nickname'] = null;
        }
        if( !isset($array['realname']) ){
            $array['realname'] = null;
        }
        if( !isset($array['email']) ){
            $array['email'] = null;
        }
        if( !isset($array['memo']) ){
            $array['memo'] = null;
        }
        // TODO: 不填enable, enable_noAppoint也要能操作

        $uId      = $array['user_id'];
        $password = $array['password'];
        $gId      = $array['group_id'];
        $cId      = $array['class_id'];
        $enable   = $array['enable'];
        $l_mode   = $array['learnStyle_mode'];
        $m_mode   = $array['material_mode'];
        $enPublic = $array['enable_noAppoint'];
        $nickName = $array['nickname'];
        $realName = $array['realname'];
        $email    = $array['email'];
        $memo     = $array['memo'];

        //紀錄使用者帳號進資料庫
        $sqlString = "INSERT INTO ".$this->table('user').
            " (`UID`, `UPassword`, `GID`, `CID`, `UEnabled`,
            `UBuildTime`, `UModifyTime`,
            `LMode`, `MMode`, `UEnable_NoAppoint`,
            `UNickname`, `URealName`, `UEmail`, `UMemo`)
            VALUES ( :id , :passwd, :gid , :cid , :enable ,
            NOW(), NOW(),
            :lmode , :mmode , :enpublic ,
            :nickname , :realname , :email , :memo )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $uId);
        $query->bindParam(":passwd", $password);
        $query->bindParam(":gid", $gId);
        $query->bindParam(":cid", $cId);
        $query->bindParam(":enable", $enable, \PDO::PARAM_BOOL);
        $query->bindParam(":lmode", $l_mode);
        $query->bindParam(":mmode", $m_mode);
        $query->bindParam(":enpublic", $enPublic, \PDO::PARAM_BOOL);
        $query->bindParam(":nickname", $nickName);
        $query->bindParam(":realname", $realName);
        $query->bindParam(":email", $email);
        $query->bindParam(":memo", $memo);
        $query->execute();

    }

    /**
     * 移除一位使用者
     * @param string $uId 使用者名稱
     * @since 2.0.0
     */
    public function deleteUser($uId) {

        //if($this->db_type == 'mysql') {
            $sqlString = "DELETE FROM ".$this->table(self::FORM_USER).
                         " WHERE `UID` = :id ";

            $query = $this->connDB->prepare($sqlString);
            $query->bindParam(":id", $uId);
            $query->execute();
        //}
        //else {
        //    throw new Exception\DatabaseNoSupportException($this->db_type);
        //}
    }

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryUserByWhere($where) {

        $sqlString = "SELECT `UID`, `UPassword`, ".
                     "`group`.`GID`, `group`.`GName`, `class`.`CID`, `class`.`CName`, ".
                     "`UEnabled`, `UBuildTime`, `UModifyTime`, ".
                     "`LMode`, `MMode`, `UEnable_NoAppoint`, ".
                     "`UNickname`, `URealName`, `UEmail`, `UMemo` ".
                     "FROM `".$this->table('user')."` AS `user` ".
                     "LEFT JOIN `".$this->table('user_auth_group')."` as `group` ".
                     "ON `group`.`GID` = `user`.`GID`".
                     "LEFT JOIN `".$this->table('user_class')."` as `class` ".
                     "ON `class`.`CID` = `user`.`CID`".
                     "WHERE ".$where;

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                if($thisResult['UEnabled'] != '0') {
                    $output_enable = true;
                }
                else { $output_enable = false; }

                if($thisResult['UEnable_NoAppoint'] != '0') {
                    $output_enable_noAppoint = true;
                }
                else { $output_enable_noAppoint = false; }

                array_push($result,
                    array( 'user_id'            => $thisResult['UID'],
                           'password'           => $thisResult['UPassword'],
                           'group_id'           => $thisResult['GID'],
                           'group_name'         => $thisResult['GName'],
                           'class_id'           => $thisResult['CID'],
                           'class_name'         => $thisResult['CName'],
                           'enable'             => $output_enable,
                           'build_time'         => $thisResult['UBuildTime'],
                           'modify_time'        => $thisResult['UModifyTime'],
                           'learnStyle_mode'    => $thisResult['LMode'],
                           'material_mode'      => $thisResult['MMode'],
                           'enable_noAppoint'   => $output_enable_noAppoint,
                           'nickname'           => $thisResult['UNickname'],
                           'realname'           => $thisResult['URealName'],
                           'email'              => $thisResult['UEmail'],
                           'memo'               => $thisResult['UMemo'])
                );
            }
            return $result;
        }
        else {
            return null;
        }
    }

    /**
     * 查詢一位使用者帳號資料
     *
     *
     * 範例:
     *
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
     *     use UElearning\Database;
     *     use UElearning\Exception;
     *
     *     try {
     *         $db = new Database\DBUser();
     *
     *         $userInfo = $db->queryUser('yuan');
     *         echo '<pre>'; print_r($userInfo); echo '</pre>';
     *     }
     *
     *
     *     // 若設定的DBMS不被支援 則丟出例外
     *     catch (Exception\DatabaseNoSupportException $e) {
     *         echo 'No Support in ',  $e->getType();
     *     } catch (Exception $e) {
     *         echo 'Caught other exception: ',  $e->getMessage();
     *         echo '<h2>'. $e->getCode() .'</h2>';
     *     }
     *
     * @param string $uId 使用者名稱
     * @return array 使用者資料陣列，格式為:
     *     array(
     *         'user_id'            => <帳號名稱>,
     *         'password'           => <密碼>,
     *         'group_id'           => <群組>,
     *         'class_id'           => <班級>,
     *         'enable'             => <啟用>,
     *         'build_time'         => <建立日期>,
     *         'modify_time'        => <修改日期>,
     *         'learnStyle_mode'    => <偏好學習導引模式>,
     *         'material_mode'      => <偏好教材模式>,
     *         'enable_noAppoint'   => <是否允許非預約學習>,
     *         'nickname'           => <暱稱>,
     *         'realname'           => <真實姓名>,
     *         'email'              => <電子郵件地址>,
     *         'memo'               => <備註>
     *     );
     *
     * @since 2.0.0
     */
    public function queryUser($uId) {

        $queryResultAll = $this->queryUserByWhere("`UID`=".$this->connDB->quote($uId));

        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            return $queryResultAll[0];
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }

    /**
     * 查詢所有的使用者帳號資料
     *
     * @return array 使用者資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'user_id'            => <帳號名稱>,
     *             'password'           => <密碼>,
     *             'group_id'           => <群組>,
     *             'class_id'           => <班級>,
     *             'enable'             => <啟用>,
     *             'build_time'         => <建立日期>,
     *             'modify_time'        => <修改日期>,
     *             'learnStyle_mode'    => <偏好學習導引模式>,
     *             'material_mode'      => <偏好教材模式>,
     *             'enable_noAppoint'   => <是否允許非預約學習>,
     *             'nickname'           => <暱稱>,
     *             'realname'           => <真實姓名>,
     *             'email'              => <電子郵件地址>,
     *             'memo'               => <備註>
     *         )
     *     );
     *
     */
    public function queryAllUser() {
        return $this->queryUserByWhere("1");
    }

    /**
     * 修改一位使用者的資料內容
     *
     * 範例:
     *
     *     $db = new Database\DBUser();
     *     $db->changeUserData('yuan', 'memo', 'hahaha');
     *
     * @param string $uId   使用者名稱
     * @param string $field 欄位名稱
     * @param string $value 內容
     */
    public function changeUserData($uId, $field, $value) {

        $sqlField = null;
        switch($field) {
            case 'user_id':          $sqlField = 'UID';               break;
            case 'password':         $sqlField = 'UPassword';         break;
            case 'group_id':         $sqlField = 'GID';               break;
            case 'class_id':         $sqlField = 'CID';               break;
            case 'enable':           $sqlField = 'UEnabled';          break;
            case 'build_time':       $sqlField = 'UBuildTime';        break;
            case 'modify_time':      $sqlField = 'UModifyTime';       break;
            case 'learnStyle_mode':  $sqlField = 'LMode';             break;
            case 'material_mode':    $sqlField = 'MMode';             break;
            case 'enable_noAppoint': $sqlField = 'UEnable_NoAppoint'; break;
            case 'nickname':         $sqlField = 'UNickname';         break;
            case 'realname':         $sqlField = 'URealName';         break;
            case 'email':            $sqlField = 'UEmail';            break;
            case 'memo':             $sqlField = 'UMemo';             break;
            default:                 $sqlField = $field;              break;
        }


        $sqlString = "UPDATE ".$this->table('user').
                     " SET `".$sqlField."` = :value".
                     " , `UModifyTime` = NOW()".
                     " WHERE `UID` = :uid";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':uid', $uId);
        $query->bindParam(':value', $value);

        $query->execute();
    }

    // ========================================================================

    /**
     * 插入群組資料
     *
     * @param array $array 使用者群組資料陣列，格式為:
     *
     *     array( 'group_id'         => <群組ID>,
     *            'name'             => <群組顯示名稱>,
     *            'memo'             => <備註>,
     *            'auth_admin'       => <Server端管理權>,
     *            'auth_clientAdmin' => <Client端管理權>
     *     );
     *
     */
    public function insertGroup($array) {

        // TODO: 不填以下欄位也能進行操作
        if( !isset($array['name']) ){
            $array['name'] = null;
        }
        if( !isset($array['memo']) ){
            $array['memo'] = null;
        }
        if( !isset($array['auth_admin']) ){
            $array['auth_admin'] = null;
        }
        if( !isset($array['auth_clientAdmin']) ){
            $array['auth_clientAdmin'] = null;
        }


        $gId              = $array['group_id'];
        $name             = $array['name'];
        $memo             = $array['memo'];
        $auth_admin       = $array['auth_admin'];
        $auth_clientAdmin = $array['auth_clientAdmin'];

        // 紀錄使用者帳號進資料庫
        $sqlString = "INSERT INTO ".$this->table('user_auth_group').
            " (`GID`, `GName`, `GMemo`,
            `GBuildTime`, `GModifyTime`,
            `GAuth_Admin`, `GAuth_ClientAdmin`)
            VALUES ( :id , :name, :memo ,
            NOW(), NOW(),
            :auth_admin , :auth_clientAdmin )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $gId);
        $query->bindParam(":name", $name);
        $query->bindParam(":memo", $memo);
        $query->bindParam(":auth_admin", $auth_admin);
        $query->bindParam(":auth_clientAdmin", $auth_clientAdmin);
        $query->execute();
    }

    /**
     * 移除一個使用者群組
     * @param string $gId
     */
    public function deleteGroup($gId) {

        $sqlString = "DELETE FROM ".$this->table('user_auth_group').
                         " WHERE `GID` = :id ";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $gId);
        $query->execute();
    }

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryGroupByWhere($where) {
        $sqlString = "SELECT * FROM ".$this->table('user_auth_group').
                     " WHERE ".$where;

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                // 轉換成boolean
                if($thisResult['GAuth_Admin'] != '0') {
                    $output_auth_admin = true;
                } else { $output_auth_admin = false; }

                if($thisResult['GAuth_ClientAdmin'] != '0') {
                    $output_auth_clientAdmin = true;
                } else { $output_auth_clientAdmin = false; }

                // 製作回傳結果陣列
                array_push($result,
                          array('group_id'         => $thisResult['GID'],
                              'name'             => $thisResult['GName'],
                              'memo'             => $thisResult['GMemo'],
                              'build_time'       => $thisResult['GBuildTime'],
                              'modify_time'      => $thisResult['GModifyTime'],
                              'auth_admin'       => $output_auth_admin,
                              'auth_clientAdmin' => $output_auth_clientAdmin)
                );
            }

            return $result;
        }
        // 若都沒查到的話
        else {
            return null;
        }

    }

    /**
     * 查詢一個使用者群組資料
     *
     * @param string $gId 群組ID
     * @return array 使用者群組資料陣列，格式為:
     *
     *     array( 'group_id'         => <群組ID>,
     *            'name'             => <群組顯示名稱>,
     *            'memo'             => <備註>,
     *            'build_time'       => <建立時間>,
     *            'modify_time'      => <修改時間>,
     *            'auth_admin'       => <Server端管理權>,
     *            'auth_clientAdmin' => <Client端管理權>
     *     );
     *
     */
    public function queryGroup($gId) {

        $queryResultAll = $this->queryGroupByWhere("`GID`=".$this->connDB->quote($gId));

        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            return $queryResultAll[0];
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }

    /**
     * 查詢所有的使用者群組資料
     *
     * @return array 使用者群組資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'group_id'         => <群組ID>,
     *             'name'             => <群組顯示名稱>,
     *             'memo'             => <備註>,
     *             'build_time'       => <建立時間>,
     *             'modify_time'      => <修改時間>,
     *             'auth_admin'       => <Server端管理權>,
     *             'auth_clientAdmin' => <Client端管理權>
     *         )
     *     );
     *
     */
    public function queryAllGroup() {

        return $this->queryGroupByWhere('1');
    }

    /**
     * 修改一個群組的資料內容
     *
     * 範例:
     *
     *     $db = new Database\DBUser();
     *     $db->changeGroupData('student', 'name', '學生');
     *
     * @param string $gId   群組ID
     * @param string $field 欄位名稱
     * @param string $value 內容
     */
    public function changeGroupData($gId, $field, $value) {

        $sqlField = null;
        switch($field) {
            case 'group_id':         $sqlField = 'GID';                 break;
            case 'name':             $sqlField = 'GName';               break;
            case 'memo':             $sqlField = 'GMemo';               break;
            case 'auth_admin':       $sqlField = 'GAuth_Admin';         break;
            case 'auth_clientAdmin': $sqlField = 'GAuth_ClientAdmin';   break;
            default:                 $sqlField = $field;                break;
        }


        $sqlString = "UPDATE ".$this->table('user_auth_group').
                     " SET `".$sqlField."` = :value".
                     " , `GModifyTime` = NOW()".
                     " WHERE `GID` = :gid";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':gid', $gId);
        $query->bindParam(':value', $value);
        $query->execute();
    }

    // ========================================================================

    /**
     * 插入班級資料
     *
     * @param  array $array 班級資料陣列，格式為:
     *
     *     array( 'class_id'         => <班級ID>,
     *            'name'             => <班級顯示名稱>,
     *            'memo'             => <備註>
     *     );
     *
     * @return int    剛剛新增的ID
     */
    public function insertClassGroup($array) {

        $cId  = $array['class_id'];
        // TODO: 不填以下欄位也能進行操作
        $name = $array['name'];
        $memo = $array['memo'];

        // 紀錄使用者帳號進資料庫
        $sqlString = "INSERT INTO ".$this->table('user_class').
            " (`CID`, `CName`, `CMemo`,
            `CBuildTime`, `CModifyTime`)
            VALUES ( :id , :name , :memo ,
            NOW(), NOW() )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $cId);
        $query->bindParam(":name", $name);
        $query->bindParam(":memo", $memo);
        $query->execute();

        // 取得剛剛加入的ID
        $sqlString = "SELECT LAST_INSERT_ID()";
        $query = $this->connDB->query($sqlString);
        $queryResult = $query->fetch();

        if(isset($cId)) return $cId;
        return $queryResult[0];
    }

    /**
     * 移除一個班級
     * @param string $cId
     */
    public function deleteClassGroup($cId) {

        $sqlString = "DELETE FROM ".$this->table('user_class').
                         " WHERE `CID` = :id ";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $cId);
        $query->execute();
    }

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryClassByWhere($where) {
        $sqlString = "SELECT * FROM ".$this->table('user_class').
                     " WHERE ".$where;

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {
                array_push($result,
                    array( 'class_id'          => $thisResult['CID'],
                           'name'              => $thisResult['CName'],
                           'memo'              => $thisResult['CMemo'],
                           'build_time'        => $thisResult['CBuildTime'],
                           'modify_time'       => $thisResult['CModifyTime'])
                );
            }

            return $result;
        }
        // 若都沒查到的話
        else {
            return null;
        }

    }

    /**
     * 查詢一個班級資料
     *
     * @param int $cId 班級ID
     * @return array 班級資料陣列，格式為:
     *
     *     array( 'class_id'         => <班級ID>,
     *            'name'             => <班級顯示名稱>,
     *            'memo'             => <備註>,
     *            'build_time'       => <建立時間>,
     *            'modify_time'      => <修改時間>
     *     );
     *
     */
    public function queryClassGroup($cId) {

        $queryResultAll = $this->queryClassByWhere("`CID`=".$this->connDB->quote($cId));

        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            return $queryResultAll[0];
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }

    /**
     * 查詢所有的班級資料
     *
     * @return array 班級資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'class_id'         => <班級ID>,
     *             'name'             => <班級顯示名稱>,
     *             'memo'             => <備註>,
     *             'build_time'       => <建立時間>,
     *             'modify_time'      => <修改時間>
     *         )
     *     );
     *
     */
    public function queryAllClassGroup() {

        return $this->queryClassByWhere('1');
    }

    /**
     * 修改一個群組的資料內容
     *
     * 範例:
     *
     *     $db = new Database\DBUser();
     *     $db->changeClassGroupData(2, 'name', '五年一班');
     *
     * @param string $cId   班級ID
     * @param string $field 欄位名稱
     * @param string $value 內容
     */
    public function changeClassGroupData($cId, $field, $value) {

        $sqlField = null;
        switch($field) {
            case 'class_id':         $sqlField = 'CID';                 break;
            case 'name':             $sqlField = 'CName';               break;
            case 'memo':             $sqlField = 'CMemo';               break;
            default:                 $sqlField = $field;                break;
        }


        $sqlString = "UPDATE ".$this->table('user_class').
                     " SET `".$sqlField."` = :value".
                     " , `CModifyTime` = NOW()".
                     " WHERE `CID` = :cid";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':cid', $cId);
        $query->bindParam(':value', $value);
        $query->execute();
    }

    /**
     * 設定自動編號的起始值
     * @param int $num 自動編號起始值
     */
    public function setClassGroupIDAutoIncrement($num) {

        // TODO: 不帶值的話，以最後編號為起頭
        $sqlString = "ALTER TABLE ".$this->table('user_class').
                     " AUTO_INCREMENT = $num";

        $this->connDB->exec($sqlString);
    }
}
