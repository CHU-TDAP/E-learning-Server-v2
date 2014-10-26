<?php
/**
 * DBStudyActivity.php
 *
 * 此檔案針對學習標的，以及學習標的的區域、廳等的資料庫查詢用。
 */

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

/**
 * 學習活動資料表
 * 
 * 此檔案針對學習標的，以及學習標的的區域、廳等的資料表進行操作。
 * 
 * 範例:
 * 
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/Database/DBStudyActivity.php';
 *     use UElearning\Database;
 *     
 *     $db = new Database\DBStudyActivity();
 *     // 現在開始一個活動
 *     $db->insertActivity('yuan', '1', null, null, null, 0, null, true, null);
 *     // 設定延後
 *     $db->setDelay(40, -12);
 *     
 *     // 查詢'yuan'的所有活動
 *     $data = $db->queryAllActivityByUserId('yuan');
 *     echo '<pre>';print_r($data);echo '</pre>';
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBStudyActivity extends Database {
    
    /**
     * 建立一個活動
     * 
     * @param string $userId           使用者ID
     * @param string $themeId          主題ID
     * @param string $startTime        開始學習時間
     * @param string $endTime          結束學習時間
     * @param int    $learnTime        學習所需時間(分)
     * @param int    $delay            延誤結束時間(分)
     * @param int    $learnStyle       學習導引模式
     * @param bool   $learnStyle_force 拒絕前往非推薦的學習點
     * @param string $materialMode     教材模式
     * @since 2.0.0
     */
    public function insertActivity($userId, $themeId, $startTime, $endTime, 
            $learnTime, $delay, $learnStyle, $learnStyle_force, $materialMode)
    {
        
        // 自動填入未填的時間
        if(isset($startTime)) 
            $to_startTime = $this->connDB->quote($startTime);
        else $to_startTime = "NOW()";
        
        if(isset($endTime)) 
            $to_endTime = $this->connDB->quote($endTime);
        else $to_endTime = "NULL";
        
        // 未填入學習時間，將會自動取得主題學習時間
        if(isset($learnTime)) 
            $to_learnTime = $this->connDB->quote($learnTime);
        else $to_learnTime = 
            "(SELECT `ThLearnTime` FROM `".$this->table('Theme').
            "` WHERE `ThID` = ".$this->connDB->quote($themeId).")";
        
        // 未填入學習風格，將會取用使用者偏好的風格，若帳號未設定，將取用系統預設的學習風格
        $queryResult = array();
        if(!isset($learnStyle) || !isset($materialMode)) {
            $sqlSUser = "SELECT `LMode`, `MMode` ".
                        "FROM `".$this->table('User')."` ".
                        "WHERE `UID`=".$this->connDB->quote($userId);
            
            $query = $this->connDB->prepare($sqlSUser);
		    $query->execute();
            
            $queryResult = $query->fetch();
        }
        if(isset($learnStyle)) 
            $to_learnStyle = $this->connDB->quote($learnStyle);
        else if(isset($queryResult['LMode'])) 
            $to_learnStyle = $queryResult['LMode'];
        else
            $to_learnStyle = LMODE;
        
        if(isset($materialMode)) 
            $to_materialMode = $this->connDB->quote($materialMode);
        else if(isset($queryResult['MMode'])) 
            $to_materialMode = "'".$queryResult['MMode']."'";
        else
            $to_materialMode = "'".MMODE."'";
        
        // 寫入學習活動資料
        $sqlString = "INSERT INTO `".$this->table('StudyActivity').
            "` (`UID`, `ThID`, 
            `StartTime`, `EndTime`, `LearnTime`, `Delay`, 
            `LMode`, `LModeForce`, `MMode`) 
            VALUES ( :uid , :thid , 
            ".$to_startTime.", ".$to_endTime.", ".$to_learnTime." , :delay , 
            ".$to_learnStyle.", :lstyle_force , ".$to_materialMode.")";

        
            
        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":uid", $userId);
        $query->bindParam(":thid", $themeId);
        $query->bindParam(":delay", $delay);
        $query->bindParam(":lstyle_force", $learnStyle_force);
        $query->execute();
        
        // 取得剛剛加入的ID
        $sqlString = "SELECT LAST_INSERT_ID()";
        $query = $this->connDB->query($sqlString);
        $queryResult = $query->fetch();
        
        if(isset($cId)) return $cId;
        return $queryResult[0];           
    }
    
    /**
     * 移除一場活動
     * @param string $uId 使用者名稱
     * @since 2.0.0
     */ 
    public function deleteActivity($id) {
        
        $sqlString = "DELETE FROM ".$this->table('StudyActivity'). 
                     " WHERE `SaID` = :id ";
            
        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $id);
        $query->execute();
    }
    
    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */ 
    public function queryActivityByWhere($where) {
        
        $sqlString = "SELECT `SaID`, `UID`, `ThID`, ".
                     "`StartTime`, `EndTime`, `LearnTime`, `Delay`, ".
                     "`LMode`, `LModeForce`, `MMode` ".
                     "FROM `".$this->table('StudyActivity')."` AS Act ".
                     //"LEFT JOIN `".$this->table('Area')."` AS Area ".
                     //"ON Area.`AID` = Target.`AID` ".
                     "WHERE ".$where;
		
		$query = $this->connDB->prepare($sqlString);
		$query->execute();
		
		$queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) { 
                
                array_push($result,
                    array( 'activity_id'      => $thisResult['SaID'],
                           'user_id'          => $thisResult['UID'],
                           'theme_id'         => $thisResult['ThID'],
                           'start_time'       => $thisResult['StartTime'],
                           'end_time'         => $thisResult['EndTime'],
                           'learn_time'       => $thisResult['LearnTime'],
                           'delay'            => $thisResult['Delay'],
                           'learnStyle_mode'  => $thisResult['LMode'],
                           'learnStyle_force' => $thisResult['LModeForce'],
                           'material_mode'    => $thisResult['MMode'])
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
     * 查詢一個活動資料
     * 
     * 
     * 範例: 
     * 
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/Database/DBTarget.php';
     *     use UElearning\Database;
     * 
     *     $db = new Database\DBTarget();
     *     
     *     $targetInfo = $db->queryActivity(4);
     *     echo '<pre>'; print_r($targetInfo); echo '</pre>';
     *     
     * 
     * @param int $td 學習活動ID
     * @return array 活動資料陣列，格式為: 
     *     array( 'activity_id'      => <活動流水編號>,
     *            'user_id'          => <使用者ID>,
     *            'theme_id'         => <主題ID>,
     *            'start_time'       => <開始學習時間>,
     *            'end_time'         => <結束學習時間>,
     *            'learn_time'       => <學習所需時間(分)>,
     *            'delay'            => <延誤結束時間(分)>,
     *            'learnStyle_mode'  => <學習導引模式>,
     *            'learnStyle_force' => <拒絕前往非推薦的學習點>,
     *            'material_mode'    => <教材模式>
     *     );
     * 
     */ 
    public function queryActivity($id) {
		
		$queryResultAll = 
            $this->queryActivityByWhere("`SaID`=".$this->connDB->quote($id));
        
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
     * 查詢所有活動資料
     * 
     * @return array 學習活動資料陣列，格式為: 
     *     
     *     array(
     *         array( 
     *             'activity_id'      => <活動流水編號>,
     *             'user_id'          => <使用者ID>,
     *             'theme_id'         => <主題ID>,
     *             'start_time'       => <開始學習時間>,
     *             'end_time'         => <結束學習時間>,
     *             'delay'            => <延誤結束時間(分)>,
     *             'learnStyle_mode'  => <學習導引模式>,
     *             'learnStyle_force' => <拒絕前往非推薦的學習點>,
     *             'material_mode'    => <教材模式>
     *         )
     *     );
     * 
     */ 
    public function queryAllActivity() {
        
        return $this->queryActivityByWhere("1");
    }
    
    /**
     * 查詢此使用者所有活動資料
     * 
     * @param int $user_id 使用者ID
     * @return array 學習活動資料陣列，格式為: 
     *     
     *     array(
     *         array( 
     *             'activity_id'      => <活動流水編號>,
     *             'user_id'          => <使用者ID>,
     *             'theme_id'         => <主題ID>,
     *             'start_time'       => <開始學習時間>,
     *             'end_time'         => <結束學習時間>,
     *             'delay'            => <延誤結束時間(分)>,
     *             'learnStyle_mode'  => <學習導引模式>,
     *             'learnStyle_force' => <拒絕前往非推薦的學習點>,
     *             'material_mode'    => <教材模式>
     *         )
     *     );
     * 
     */ 
    public function queryAllActivityByUserId($user_id) {
        
        return $this->queryActivityByWhere(
            "`UID`=".$this->connDB->quote($user_id));
    }
    
    /**
     * 設定結束時間
     * 
     * 只要一設定，就代表學習活動結束了
     * @param int    $activity_id 活動編號
     * @param string $endTime     時間
     */ 
    public function setEndTime($activity_id, $endTime) {
        $sqlString = "UPDATE ".$this->table('StudyActivity').
                     " SET `EndTime` = :value".
                     " WHERE `SaID` = :id";
        
        $query = $this->connDB->prepare($sqlString);
		$query->bindParam(':id', $activity_id);
        $query->bindParam(':value', $endTime);
		$query->execute();
    }
    
    /**
     * 設定立即結束
     * 
     * 只要一設定，就代表學習活動結束了
     * @param int    $activity_id 活動編號
     */ 
    public function setEndTimeNow($activity_id) {
        $sqlString = "UPDATE ".$this->table('StudyActivity').
                     " SET `EndTime` = NOW()".
                     " WHERE `SaID` = :id";
        
        $query = $this->connDB->prepare($sqlString);
		$query->bindParam(':id', $activity_id);
		$query->execute();
    }
    
    /**
     * 設定延後時間
     * 
     * 只要一設定，就代表學習活動結束了
     * @param int $activity_id 活動編號
     * @param int $delay       延後時間(分)
     */ 
    public function setDelay($activity_id, $delay) {
        $sqlString = "UPDATE ".$this->table('StudyActivity').
                     " SET `Delay` = :value".
                     " WHERE `SaID` = :id";
        
        $query = $this->connDB->prepare($sqlString);
		$query->bindParam(':id', $activity_id);
        $query->bindParam(':value', $delay);
		$query->execute();
    }
}