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
 *     $db->insertActivity('yuan', '1', null, null, null, 0, false, null, true, null);
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
     * @param bool   $timeForce        時間到時是否強制中止學習
     * @param int    $learnStyle       學習導引模式
     * @param bool   $learnStyle_force 拒絕前往非推薦的學習點
     * @param bool   $enable_virtual   是否啟用虛擬教材
     * @param string $materialMode     教材模式
     *
     * @return int 剛新增的活動編號
     * @since 2.0.0
     */
    public function insertActivity($userId, $themeId, $startTime, $endTime,
                             $learnTime, $delay, $timeForce,
                             $learnStyle, $learnStyle_force, $enable_virtual,
                             $materialMode)
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
            "(SELECT `ThLearnTime` FROM `".$this->table('learn_topic').
            "` WHERE `ThID` = ".$this->connDB->quote($themeId).")";

        // 未填入學習風格，將會取用使用者偏好的風格，若帳號未設定，將取用系統預設的學習風格
        $queryResult = array();
        if(!isset($learnStyle) || !isset($materialMode)) {
            $sqlSUser = "SELECT `LMode`, `MMode` ".
                        "FROM `".$this->table('user')."` ".
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
        $sqlString = "INSERT INTO `".$this->table('user_activity').
            "` (`UID`, `ThID`,
            `StartTime`, `EndTime`, `LearnTime`, `Delay`, `TimeForce`,
            `LMode`, `LModeForce`, `EnableVirtual`, `MMode`)
            VALUES ( :uid , :thid ,
            ".$to_startTime.", ".$to_endTime.", ".$to_learnTime." , :delay , :timeforce ,
            ".$to_learnStyle.", :lstyle_force , :enable_virtual , ".$to_materialMode.")";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":uid", $userId);
        $query->bindParam(":thid", $themeId);
        $query->bindParam(":delay", $delay);
        $query->bindParam(":timeforce", $timeForce, \PDO::PARAM_BOOL);
        $query->bindParam(":lstyle_force", $learnStyle_force, \PDO::PARAM_BOOL);
        $query->bindParam(":enable_virtual", $enable_virtual, \PDO::PARAM_BOOL);
        $query->execute();

        // 取得剛剛加入的ID
        $sqlString = "SELECT LAST_INSERT_ID()";
        $query = $this->connDB->query($sqlString);
        $queryResult = $query->fetch();
        $resultId = $queryResult[0];

        return $resultId;
    }

    /**
     * 移除一場活動
     * @param int $id 活動編號
     * @since 2.0.0
     */
    public function deleteActivity($id) {

        $sqlString = "DELETE FROM ".$this->table('user_activity').
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
    protected function queryActivityByWhere($where) {

        $sqlString = "SELECT `SaID`, `UID`, `ThID`, ".
                     "(SELECT `ThName` FROM `".$this->table('learn_topic')."` AS `th` ".
                     "WHERE `th`.`ThID` = `sa`.`ThID`) AS `ThName`, ".
                     "`StartTime`, ".
                     "FROM_UNIXTIME(UNIX_TIMESTAMP(`StartTime`)+(`LearnTime`+`Delay`)*60)".
                     " AS `ExpiredTime`, ".
                     "`EndTime`, ".
                     "`LearnTime`, `Delay`, `TimeForce`, ".
                     "`LMode`, `LModeForce`, `EnableVirtual`, `MMode`, ".

                     "(SELECT count(`TID`)
                     FROM `".$this->table('learn_topic_belong')."` AS `belong`
                     WHERE `belong`.`ThID` = `sa`.`ThID`) AS `TargetTotal`, ".

                     "(SELECT count(DISTINCT `TID`)
                     FROM `".$this->table('user_history')."` AS `study`
                     WHERE `study`.`SaID` = `sa`.`SaID`) AS `LearnedTotal`".

                     "FROM `".$this->table('user_activity')."` AS sa ".
                     "WHERE ".$where;

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                if($thisResult['TimeForce'] != '0') {
                    $output_time_force = true;
                }
                else { $output_time_force = false; }

                if($thisResult['LModeForce'] != '0') {
                    $output_learnStyleForce = true;
                }
                else { $output_learnStyleForce = false; }

                if($thisResult['EnableVirtual'] != '0') {
                    $output_enable_virtual = true;
                }
                else { $output_enable_virtual = false; }

                array_push($result,
                    array( 'activity_id'      => (int)$thisResult['SaID'],
                           'user_id'          => $thisResult['UID'],
                           'theme_id'         => (int)$thisResult['ThID'],
                           'theme_name'       => $thisResult['ThName'],
                           'start_time'       => $thisResult['StartTime'],
                           'expired_time'     => $thisResult['ExpiredTime'],
                           'end_time'         => $thisResult['EndTime'],
                           'learn_time'       => (int)$thisResult['LearnTime'],
                           'delay'            => (int)$thisResult['Delay'],
                           'time_force'       => $output_time_force,
                           'learnStyle_mode'  => (int)$thisResult['LMode'],
                           'learnStyle_force' => $output_learnStyleForce,
                           'enable_virtual'   => $output_enable_virtual,
                           'material_mode'    => $thisResult['MMode'],
                           'target_total'     => (int)$thisResult['TargetTotal'],
                           'learned_total'    => (int)$thisResult['LearnedTotal']
                         )
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
     *            'time_force'       => <時間到時是否強制中止學習>,
     *            'learnStyle_mode'  => <學習導引模式>,
     *            'learnStyle_force' => <拒絕前往非推薦的學習點>,
     *            'enable_virtual'   => <是否啟用虛擬教材>,
     *            'material_mode'    => <教材模式>,
     *            'target_total'     => <有多少標的學習>,
     *            'learned_total'    => <已經完成多少標的學習>
     *     );
     * @param int $id 活動編號
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
     *             'learn_time'       => <學習所需時間(分)>,
     *             'delay'            => <延誤結束時間(分)>,
     *             'time_force'       => <時間到時是否強制中止學習>,
     *             'learnStyle_mode'  => <學習導引模式>,
     *             'learnStyle_force' => <拒絕前往非推薦的學習點>,
     *             'enable_virtual'   => <是否啟用虛擬教材>,
     *             'material_mode'    => <教材模式>,
     *             'target_total'     => <有多少標的學習>,
     *             'learned_total'    => <已經完成多少標的學習>
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
     *             'enable_virtual'   => <是否啟用虛擬教材>,
     *             'material_mode'    => <教材模式>,
     *             'target_total'     => <有多少標的學習>,
     *             'learned_total'    => <已經完成多少標的學習>
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
        $sqlString = "UPDATE ".$this->table('user_activity').
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
        $sqlString = "UPDATE ".$this->table('user_activity').
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
        $sqlString = "UPDATE ".$this->table('user_activity').
                     " SET `Delay` = :value".
                     " WHERE `SaID` = :id";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':id', $activity_id);
        $query->bindParam(':value', $delay);
        $query->execute();
    }

    // ========================================================================

    /**
     * 預約一個活動
     *
     * @param string $userId           使用者ID
     * @param string $themeId          主題ID
     * @param string $startTime        開始生效時間
     * @param string $expiredTime      過期時間
     * @param int    $learnTime        學習所需時間(分)
     * @param bool   $timeForce        時間到時是否強制中止學習
     * @param int    $learnStyle       學習導引模式
     * @param bool   $learnStyle_force 拒絕前往非推薦的學習點
     * @param bool   $enable_virtual   是否啟用虛擬教材
     * @param string $materialMode     教材模式
     * @param string $isLock           是否鎖定不讓學生更改
     *
     * @return int 剛新增的活動編號
     * @since 2.0.0
     */
    public function insertWillActivity($userId, $themeId, $startTime, $expiredTime,
                             $learnTime, $timeForce,
                             $learnStyle, $learnStyle_force, $enable_virtual,
                             $materialMode, $isLock)
    {

        // 自動填入未填的時間
        if(isset($startTime))
            $to_startTime = $this->connDB->quote($startTime);
        else $to_startTime = "NOW()";

        if(isset($expiredTime))
            $to_expiredTime = $this->connDB->quote($expiredTime);
        else $to_expiredTime = "NULL";

        // 未填入學習時間，將會自動取得主題學習時間
        if(isset($learnTime))
            $to_learnTime = $this->connDB->quote($learnTime);
        else $to_learnTime =
            "(SELECT `ThLearnTime` FROM `".$this->table('learn_topic').
            "` WHERE `ThID` = ".$this->connDB->quote($themeId).")";

        // 未填入學習風格，將會取用使用者偏好的風格，若帳號未設定，將取用系統預設的學習風格
        $queryResult = array();
        if(!isset($learnStyle) || !isset($materialMode)) {
            $sqlSUser = "SELECT `LMode`, `MMode` ".
                        "FROM `".$this->table('user')."` ".
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
        $sqlString = "INSERT INTO `".$this->table('user_activity_will').
            "` (`UID`, `ThID`,
            `StartTime`, `ExpiredTime`, `LearnTime`, `TimeForce`,
            `LMode`, `LModeForce`, `EnableVirtual`, `MMode`, `Lock`)
            VALUES ( :uid , :thid ,
            ".$to_startTime.", ".$to_expiredTime.", ".$to_learnTime." , :timeforce ,
            ".$to_learnStyle.", :lstyle_force , :enable_virtual ,
            ".$to_materialMode.", :lock )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":uid", $userId);
        $query->bindParam(":thid", $themeId);
        $query->bindParam(":timeforce", $timeForce);
        $query->bindParam(":lstyle_force", $learnStyle_force);
        $query->bindParam(":enable_virtual", $enable_virtual);
        $query->bindParam(":lock", $isLock);
        $query->execute();

        // 取得剛剛加入的ID
        $sqlString = "SELECT LAST_INSERT_ID()";
        $query = $this->connDB->query($sqlString);
        $queryResult = $query->fetch();
        $resultId = $queryResult[0];

        return $resultId;
    }

    /**
     * 移除一場預約
     * @param int $id 預約編號
     * @since 2.0.0
     */
    public function deleteWillActivity($id) {

        $sqlString = "DELETE FROM ".$this->table('user_activity_will').
                     " WHERE `SwID` = :id ";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $id);
        $query->execute();
    }

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryWillActivityByWhere($where) {

        $sqlString = "SELECT `SwID`, `UID`, `ThID`, ".
                     "`StartTime`, `ExpiredTime`, `LearnTime`, `TimeForce`, ".
                     "`LMode`, `LModeForce`, `EnableVirtual`, `MMode`, `Lock`, ".

                     "(SELECT count(`TID`)
                     FROM `".$this->table('learn_topic_belong')."` AS `belong`
                     WHERE `belong`.`ThID` = `sw`.`ThID`) AS `TargetTotal`, ".

                     "`BuildTime`, `ModifyTime` ".

                     "FROM `".$this->table('user_activity_will')."` AS `sw` ".
                     "WHERE ".$where;

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                if($thisResult['TimeForce'] != '0') {
                    $output_time_force = true;
                }
                else { $output_time_force = false; }

                if($thisResult['LModeForce'] != '0') {
                    $output_learnStyleForce = true;
                }
                else { $output_learnStyleForce = false; }

                if($thisResult['Lock'] != '0') {
                    $output_isLock = true;
                }
                else { $output_isLock = false; }

                if($thisResult['EnableVirtual'] != '0') {
                    $output_enable_virtual = true;
                }
                else { $output_enable_virtual = false; }

                array_push($result,
                    array( 'activity_will_id' => $thisResult['SwID'],
                           'user_id'          => $thisResult['UID'],
                           'theme_id'         => $thisResult['ThID'],
                           'start_time'       => $thisResult['StartTime'],
                           'expired_time'     => $thisResult['ExpiredTime'],
                           'learn_time'       => $thisResult['LearnTime'],
                           'time_force'       => $output_time_force,
                           'learnStyle_mode'  => $thisResult['LMode'],
                           'learnStyle_force' => $output_learnStyleForce,
                           'enable_virtual'   => $output_enable_virtual,
                           'material_mode'    => $thisResult['MMode'],
                           'is_lock'          => $output_isLock,
                           'target_total'     => $thisResult['TargetTotal'],
                           'build_time'       => $thisResult['BuildTime'],
                           'modify_time'      => $thisResult['ModifyTime']
                         )
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
     *     array( 'activity_will_id' => <預約活動流水編號>,
     *            'user_id'          => <使用者ID>,
     *            'theme_id'         => <主題ID>,
     *            'start_time'       => <開始生效時間>,
     *            'expired_time'     => <過期時間>,
     *            'learn_time'       => <學習所需時間(分)>,
     *            'time_force'       => <時間到時是否強制中止學習>,
     *            'learnStyle_mode'  => <學習導引模式>,
     *            'learnStyle_force' => <拒絕前往非推薦的學習點>,
     *            'enable_virtual'   => <是否啟用虛擬教材>,
     *            'material_mode'    => <教材模式>,
     *            'is_lock'          => <是否鎖定不讓學生更改>,
     *            'target_total'     => <有多少標的學習>,
     *            'build_time'       => <建立時間>,
     *            'modify_time'      => <修改時間>
     *     );
     * @param int $id 活動編號
     */
    public function queryWillActivity($id) {

        $queryResultAll =
            $this->queryWillActivityByWhere("`SwID`=".$this->connDB->quote($id));

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
     *             'activity_will_id' => <預約活動流水編號>,
     *             'user_id'          => <使用者ID>,
     *             'theme_id'         => <主題ID>,
     *             'start_time'       => <開始生效時間>,
     *             'expired_time'     => <過期時間>,
     *             'learn_time'       => <學習所需時間(分)>
     *             'time_force'       => <時間到時是否強制中止
     *             'learnStyle_mode'  => <學習導引模式>,
     *             'learnStyle_force' => <拒絕前往非推薦的學習>,
     *             'enable_virtual'   => <是否啟用虛擬教材>,
     *             'material_mode'    => <教材模式>,
     *             'is_lock'          => <是否鎖定不讓學生更改
     *             'target_total'     => <有多少標的學習>,
     *             'build_time'       => <建立時間>,
     *             'modify_time'      => <修改時間>
     *         )
     *     );
     *
     */
    public function queryAllWillActivity() {

        return $this->queryWillActivityByWhere("1");
    }

    /**
     * 查詢此使用者所有活動資料
     *
     * @param int $user_id 使用者ID
     * @return array 學習活動資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'activity_id'      => <預約活動流水編號>,
     *             'user_id'          => <使用者ID>,
     *             'theme_id'         => <主題ID>,
     *             'start_time'       => <開始生效時間>,
     *             'expired_time'     => <過期時間>,
     *             'learn_time'       => <學習所需時間(分)>
     *             'time_force'       => <時間到時是否強制中止
     *             'learnStyle_mode'  => <學習導引模式>,
     *             'learnStyle_force' => <拒絕前往非推薦的學習>,
     *             'enable_virtual'   => <是否啟用虛擬教材>,
     *             'material_mode'    => <教材模式>,
     *             'is_lock'          => <是否鎖定不讓學生更改
     *             'target_total'     => <有多少標的學習>,
     *             'build_time'       => <建立時間>,
     *             'modify_time'      => <修改時間>
     *         )
     *     );
     *
     */
    public function queryAllWillActivityByUserId($user_id) {

        return $this->queryWillActivityByWhere(
            "`UID`=".$this->connDB->quote($user_id));
    }

    /**
     * 修改預約資訊
     *
     * @param int    $tId   標的編號
     * @param string $field 欄位名稱
     * @param string $value 內容
     */
    public function changeWillActivityData($id, $field, $value) {
        $sqlField = null;
        switch($field) {
            case 'user_id':          $sqlField = 'UID';           break;
            case 'theme_id':         $sqlField = 'ThID';          break;
            case 'start_time':       $sqlField = 'StartTime';     break;
            case 'expired_time':     $sqlField = 'ExpiredTime';   break;
            case 'learn_time':       $sqlField = 'LearnTime';     break;
            case 'learn_time':       $sqlField = 'TLearnTime';    break;
            case 'time_force':       $sqlField = 'TimeForce';     break;
            case 'learnStyle_mode':  $sqlField = 'LMode';         break;
            case 'learnStyle_force': $sqlField = 'LModeForce';    break;
            case 'enable_virtual':   $sqlField = 'EnableVirtual'; break;
            case 'material_mode':    $sqlField = 'MMode';         break;
            case 'is_lock':          $sqlField = 'Lock';          break;
            default:                 $sqlField = $field;          break;
        }

        $sqlString = "UPDATE ".$this->table('user_activity_will').
                     " SET `".$sqlField."` = :value".
                     " , `ModifyTime` = NOW()".
                     " WHERE `SwID` = :id";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':id', $id);
        $query->bindParam(':value', $value);
        $query->execute();
    }

    /**
     * 取得可以學習的資訊
     *
     * @param string $userId 使用者ID
     * @return array 可進行學習的資訊
     */
    public function getEnableActivityByUserId($userId) {

$sqlString_SA = "
SELECT 'study' AS `Type`,
  `SaID` AS `ID`, `SaID`, NULL AS `SwID`,
  `th`.`ThID`, `th`.`ThName`, `th`.`ThIntroduction`,

  `StartTime`,
  FROM_UNIXTIME(UNIX_TIMESTAMP(`StartTime`)+(`LearnTime`+`Delay`)*60) AS `ExpiredTime`,
  (`LearnTime`+`Delay`) AS `HaveTime`, `LearnTime`, `Delay`,
  ceiling((UNIX_TIMESTAMP(`StartTime`)+(`LearnTime`+`Delay`)*60-UNIX_TIMESTAMP(NOW()))/60) AS `RemainingTime`, `TimeForce`,

  `LMode`, `LModeForce`, `MMode`, `EnableVirtual`, '1' AS `Lock`,

(SELECT count(`TID`) FROM `".$this->table('learn_topic_belong')."` AS `belong` WHERE `belong`.`ThID` = `sa`.`ThID`) AS `  TargetTotal`,
(SELECT count(DISTINCT `TID`) FROM `".$this->table('user_history')."` AS `study` WHERE `study`.`SaID` = `sa`.`SaID`) AS `LearnedTotal`

FROM `".$this->table('user_activity')."` AS `sa`
LEFT JOIN `".$this->table('learn_topic')."` AS `th`
ON `th`.`ThID` = `sa`.`ThID`
WHERE `EndTime` IS NULL AND `UID` = :uid ";


$sqlString_SW = "
SELECT 'will' AS `Type`,
  `SwID` AS `ID`, NULL, `SwID`,
  `th`.`ThID`, `th`.`ThName`, `th`.`ThIntroduction`,

  `StartTime`, `ExpiredTime`, `LearnTime` AS `HaveTime`, `LearnTime`, 0 AS `Delay`,
  `LearnTime` AS `RemainingTime`, `TimeForce`,

  `LMode`, `LModeForce`, `MMode`, `EnableVirtual`, `Lock`,

  (SELECT count(`TID`) FROM `".$this->table('learn_topic_belong')."` AS `belong` WHERE `belong`.`ThID` = `sw`.`ThID`) AS `TargetTotal`,
  0 AS `LearnedTotal`

FROM `".$this->table('user_activity_will')."` AS `sw`
LEFT JOIN `".$this->table('learn_topic')."` AS `th`
ON `th`.`ThID` = `sw`.`ThID`
WHERE NOW()>=`StartTime` AND NOW()<`ExpiredTime` AND `UID` = :uid
";


$sqlString_TG = "
SELECT 'theme' AS `Type`,
  `ThID` AS `ID`, NULL, NULL,
  `ThID`, `ThName`, `ThIntroduction`,

  NULL, NULL, `ThLearnTime` AS `HaveTime`, `ThLearnTime`, 0 AS `Delay`,
  `ThLearnTime` AS `RemainingTime`, NULL,

  NULL, NULL, NULL, 0 AS `EnableVirtual`, 0 AS `Lock`,

  (SELECT count(`TID`) FROM `".$this->table('learn_topic_belong')."` AS `belong` WHERE `belong`.`ThID` = `th`.`ThID`) AS `TargetTotal`,
  0 AS `LearnedTotal`

FROM `".$this->table('learn_topic')."` AS `th` WHERE (SELECT `UEnable_NoAppoint` FROM `".$this->table('user')."` WHERE `UID`= :uid ) = '1'
";

$sqlString = $sqlString_SA." UNION ".$sqlString_SW." UNION ".$sqlString_TG;

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":uid", $userId);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                if($thisResult['TimeForce'] != '0') {
                    $output_time_force = true;
                }
                else { $output_time_force = false; }

                if($thisResult['LModeForce'] != '0') {
                    $output_learnStyleForce = true;
                }
                else { $output_learnStyleForce = false; }

                if($thisResult['Lock'] != '0') {
                    $output_lock = true;
                }
                else { $output_lock = false; }

                if($thisResult['EnableVirtual'] != '0') {
                    $output_enable_virtual = true;
                }
                else { $output_enable_virtual = false; }

                array_push($result,
                    array( 'type'             => $thisResult['Type'],
                           'id'               => $thisResult['ID'],
                           'activity_id'      => $thisResult['SaID'],
                           'activity_will_id' => $thisResult['SwID'],
                           'theme_id'         => $thisResult['ThID'],
                           'theme_name'       => $thisResult['ThName'],
                           'theme_introduction' => $thisResult['ThIntroduction'],
                           'start_time'       => $thisResult['StartTime'],
                           'expired_time'     => $thisResult['ExpiredTime'],
                           'have_time'        => $thisResult['HaveTime'],
                           'learn_time'       => $thisResult['LearnTime'],
                           'delay'            => $thisResult['Delay'],
                           'remaining_time'   => $thisResult['RemainingTime'],
                           'time_force'       => $output_time_force,
                           'learnStyle_mode'  => $thisResult['LMode'],
                           'learnStyle_force' => $output_learnStyleForce,
                           'enable_virtual'   => $output_enable_virtual,
                           'material_mode'    => $thisResult['MMode'],
                           'lock'             => $output_lock,
                           'target_total'     => $thisResult['TargetTotal'],
                           'learned_total'    => $thisResult['LearnedTotal']
                         )
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
