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
     * @param string $uId 使用者名稱
     * @param string $password 密碼
     * @param string $gId 群組
     * @param string $cId 班級
     * @param string $enable 啟用此帳號
     * @param string $l_mode 學習模式
     * @param string $m_mode 教材模式
     * @param string $nickName 暱稱
     * @param string $realName 姓名
     * @param string $email 電子郵件地址
     * @param string $memo 備註
     */ 
    public function insertUser($uId, $password, $gId, $cId, $enable,
                     $l_mode, $m_mode, 
                     $nickName, $realName, $email, $memo){
        
        // 檢查是否有支援所設定的DBMS
        if($this->db_type == 'mysql') {
            
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
        }
        else {
            throw new Exception\DatabaseNoSupportException($this->db_type);
        }
                
    }
    
    /**
     * 查詢一位使用者帳號資料
     * @param string $uId 使用者名稱
     * @return array 使用者資料 (TODO 格式待補)
     */ 
    public function queryUser($uId) {
        
        $sqlString = "SELECT * FROM ".$this->table('User').
                     " WHERE `UID` = :uid";
		
		$query = $this->connDB->prepare($sqlString);
		$query->bindParam(':uid', $uId);
		$query->execute();
		
		$queryResultAll = $query->fetchAll();
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
        else {
            return null;
        }
    }
    
    /**
     * 移除一位使用者
     * @param string $uId 使用者名稱
     */ 
    public function deleteUser($uId) {
        
        if($this->db_type == 'mysql') {
            $sqlString = "DELETE FROM ".$this->table(self::FORM_USER). 
                         " WHERE `UID` = :id ";
            
            $query = $this->connDB->prepare($sqlString);
            $query->bindParam(":id", $uId);
            $query->execute();
        }
        else {
            throw new Exception\DatabaseNoSupportException($this->db_type);
        }
    }
    
}