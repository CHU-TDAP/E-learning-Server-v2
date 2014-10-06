<?php
/**
 * 使用者資料表
 *
 * 此檔案針對使用者資料表的功能。
 */

namespace UElearning\Database;

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
     *         $db->insertUser('eric', 'passwd', 'user', null, 1, 'harf-line-learn', '1', '偉人', 'Eric Chiou', 'eric@example.com', null);
     *         
     *         echo 'Finish';
     *     }
     *     
     *     
     *     // 若設定的DBMS不被支援 則丟出例外
     *     catch (Database\Exception\DatabaseNoSupportException $e) {
     *         echo 'No Support in ',  $e->getType();
     *     } catch (Exception $e) {
     *         echo 'Caught other exception: ',  $e->getMessage();
     *         echo '<h2>'. $e->getCode() .'</h2>';
     *     }
     * 
     * @param string $uId      使用者名稱
     * @param string $password 密碼
     * @param string $gId      群組
     * @param string $cId      班級
     * @param string $enable   啟用此帳號
     * @param string $l_mode   學習模式
     * @param string $m_mode   教材模式
     * @param string $nickName 暱稱
     * @param string $realName 姓名
     * @param string $email    電子郵件地址
     * @param string $memo     備註
     */ 
    public function insertUser($uId, $password, $gId, $cId, $enable,
                     $l_mode, $m_mode, 
                     $nickName, $realName, $email, $memo){
        
        // 檢查是否有支援所設定的DBMS
        //if($this->db_type == 'mysql') {
            
            //紀錄使用者帳號進資料庫
            $sqlString = "INSERT INTO ".$this->table('User').
                " (`UID`, `UPassword`, `GID`, `CID`, `UEnabled`, `UBuild_Time`,
                `LMode`, `MMode`, `UNickname`, `UReal_Name`, `UEmail`, `UMemo`)
                VALUES ( :id , :passwd, :gid , :cid , :enable , NOW() , 
                :lmode , :mmode , :nickname , :realname , :email , :memo )";
            
            $query = $this->connDB->prepare($sqlString);
            $query->bindParam(":id", $uId);
            $query->bindParam(":passwd", $password);
            $query->bindParam(":gid", $gId);
            $query->bindParam(":cid", $cId);
            $query->bindParam(":enable", $enable);
            $query->bindParam(":lmode", $l_mode);
            $query->bindParam(":mmode", $m_mode);
            $query->bindParam(":nickname", $nickName);
            $query->bindParam(":realname", $realName);
            $query->bindParam(":email", $email);
            $query->bindParam(":memo", $memo);
            $query->execute();
        //}
        //else {
        //    throw new Exception\DatabaseNoSupportException($this->db_type);
        //}
                
    }
    
    /**
     * 移除一位使用者
     * @param string $uId 使用者名稱
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
     * 查詢一位使用者帳號資料
     * 
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
     *         $userInfo = $db->queryUser('yuan');
     *         echo '<pre>'; print_r($userInfo); echo '</pre>';
     *     }
     *     
     *     
     *     // 若設定的DBMS不被支援 則丟出例外
     *     catch (Database\Exception\DatabaseNoSupportException $e) {
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
     *         'learnStyle_mode'    => <偏好學習導引模式>,
     *         'material_mode'      => <偏好教材模式>,
     *         'nickname'           => <暱稱>,
     *         'realname'           => <真實姓名>,
     *         'email'              => <電子郵件地址>,
     *         'memo'               => <備註>
     *     );
     * 
     */ 
    public function queryUser($uId) {
        
        $sqlString = "SELECT * FROM ".$this->table('User').
                     " WHERE `UID` = :uid";
		
		$query = $this->connDB->prepare($sqlString);
		$query->bindParam(':uid', $uId);
		$query->execute();
		
		$queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            $queryResult = $queryResultAll[0];

            $result = array( 
                'user_id'            => $queryResult['UID'],
                'password'           => $queryResult['UPassword'],
                'group_id'           => $queryResult['GID'],
                'class_id'           => $queryResult['CID'],
                'enable'             => $queryResult['UEnabled'],
                'build_time'         => $queryResult['UBuild_Time'],
                'learnStyle_mode'    => $queryResult['LMode'],
                'material_mode'      => $queryResult['MMode'],
                'nickname'           => $queryResult['UNickname'],
                'realname'           => $queryResult['UReal_Name'],
                'email'              => $queryResult['UEmail'],
                'memo'               => $queryResult['UMemo']
            );

            return $result;
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
     *             'learnStyle_mode'    => <偏好學習導引模式>,
     *             'material_mode'      => <偏好教材模式>,
     *             'nickname'           => <暱稱>,
     *             'realname'           => <真實姓名>,
     *             'email'              => <電子郵件地址>,
     *             'memo'               => <備註>
     *         )
     *     );
     * 
     */ 
    public function queryAllUser() {
        
        $sqlString = "SELECT * FROM ".$this->table('User');
		
		$query = $this->connDB->prepare($sqlString);
		$query->execute();
		
		$queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) { 
                array_push($result,
                    array( 'user_id'            => $thisResult['UID'],
                           'password'           => $thisResult['UPassword'],
                           'group_id'           => $thisResult['GID'],
                           'class_id'           => $thisResult['CID'],
                           'enable'             => $thisResult['UEnabled'],
                           'build_time'         => $thisResult['UBuild_Time'],
                           'learnStyle_mode'    => $thisResult['LMode'],
                           'material_mode'      => $thisResult['MMode'],
                           'nickname'           => $thisResult['UNickname'],
                           'realname'           => $thisResult['UReal_Name'],
                           'email'              => $thisResult['UEmail'],
                           'memo'               => $thisResult['UMemo'])
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
        // UPDATE `UElearning`.`chu__User` SET `UMemo` = '測試者' WHERE `chu__User`.`UID` = 'yuan';
        
        $sqlField = null;
        switch($field) {
            case 'user_id':         $sqlField = 'UID';         break;
            case 'password':        $sqlField = 'UPassword';   break;
            case 'group_id':        $sqlField = 'GID';         break;
            case 'class_id':        $sqlField = 'CID';         break;
            case 'enable':          $sqlField = 'UEnabled';    break;
            case 'build_time':      $sqlField = 'UBuild_Time'; break;
            case 'learnStyle_mode': $sqlField = 'LMode';       break;
            case 'material_mode':   $sqlField = 'MMode';       break;
            case 'nickname':        $sqlField = 'UNickname';   break;
            case 'realname':        $sqlField = 'UReal_Name';  break;
            case 'email':           $sqlField = 'UEmail';      break;
            case 'memo':            $sqlField = 'UMemo';       break;
            default:                $sqlField = $field;        break;
        }
        
        
        $sqlString = "UPDATE ".$this->table('User').
                     " SET `".$sqlField."` = :value".
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
     * @param string $gId              群組ID
     * @param string $name             群組顯示名稱
     * @param string $memo             備註
     * @param string $auth_admin       Server端管理權
     * @param string $auth_clientAdmin Client端管理權
     */ 
    public function insertGroup($gId, $name, $memo, $auth_admin, $auth_clientAdmin) {
        
        // 紀錄使用者帳號進資料庫
        $sqlString = "INSERT INTO ".$this->table('AGroup').
            " (`GID`, `GName`, `GMemo`, `GAuth_Admin`, `GAuth_ClientAdmin`)
            VALUES ( :id , :name, :memo , :auth_admin , :auth_clientAdmin )";

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
        
        $sqlString = "DELETE FROM ".$this->table('AGroup'). 
                         " WHERE `GID` = :id ";
            
        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $gId);
        $query->execute();
    }
    
    /**
     * 查詢一個使用者群組資料
     * 
     * @return array 使用者群組資料陣列，格式為: 
     *     
     *     array( 'group_id'         => <群組ID>,
     *            'name'             => <群組顯示名稱>,
     *            'memo'             => <備註>,
     *            'auth_admin'       => <Server端管理權>,
     *            'auth_clientAdmin' => <Client端管理權>
     *     );
     * 
     */ 
    public function queryGroup($gId) {
    
        $sqlString = "SELECT * FROM ".$this->table('AGroup').
                     " WHERE `GID` = :gid";
		
		$query = $this->connDB->prepare($sqlString);
		$query->bindParam(':gid', $gId);
		$query->execute();
		
        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            $thisResult = $queryResultAll[0];
            // 製作回傳結果陣列
            $result = array('group_id'          => $thisResult['GID'],
                            'name'              => $thisResult['GName'],
                            'memo'              => $thisResult['GMemo'],
                            'auth_admin'        => $thisResult['GAuth_Admin'],
                            'auth_clientAdmin'  => $thisResult['GAuth_ClientAdmin']
            );
            return $result;
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
     *             'auth_admin'       => <Server端管理權>,
     *             'auth_clientAdmin' => <Client端管理權>
     *         )
     *     );
     * 
     */ 
    public function queryAllGroup() {
    
        $sqlString = "SELECT * FROM ".$this->table('AGroup');
        
		$query = $this->connDB->prepare($sqlString);
		$query->execute();
		
        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) { 
                array_push($result,
                    array( 'group_id'          => $thisResult['GID'],
                           'name'              => $thisResult['GName'],
                           'memo'              => $thisResult['GMemo'],
                           'auth_admin'        => $thisResult['GAuth_Admin'],
                           'auth_clientAdmin'  => $thisResult['GAuth_ClientAdmin'])
                );
            }
            return $result;
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }
    
    
}