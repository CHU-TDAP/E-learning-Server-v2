<?php
/**
 * DBStudy.php
 *
 * 此檔案針對學習標的，以及學習標的的區域、廳等的資料庫查詢用。
 */

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

/**
 * 學習點記錄資料表
 *
 * 範例:
 *
 *     require_once __DIR__.'/../config.php';
 *     require_once UELEARNING_LIB_ROOT.'/Database/DBStudyActivity.php';
 *     use UElearning\Database;
 *
 *     $db = new Database\DBStudy();
 *     // 查詢'yuan'的所有活動
 *     $data = $db->queryAllStudyByUserId('yuan');
 *     echo '<pre>';print_r($data);echo '</pre>';
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBStudy extends Database {

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryByWhere($where) {

        $sqlString = "SELECT `SID`, `SaID`, ".
                     "`TID`, `IsEnter`, `IsEntity`, `In_TargetTime`, `Out_TargetTime` ".
                     "FROM `".$this->table('user_history')."` ".
                     "WHERE ".$where;

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                if($thisResult['IsEntity'] != '0') {
                    $output_entity = true;
                }
                else { $output_entity = false; }
                if($thisResult['IsEnter'] != '0') {
                    $output_enter = true;
                }
                else { $output_enter = false; }

                array_push($result,
                    array( 'study_id'         => (int)$thisResult['SID'],
                           'activity_id'      => (int)$thisResult['SaID'],
                           'target_id'        => (int)$thisResult['TID'],
                           'is_enter'         => $output_enter,
                           'is_entity'        => $output_entity,
                           'in_target_time'   => $thisResult['In_TargetTime'],
                           'out_target_time'  => $thisResult['Out_TargetTime']
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
     * 以活動編號查詢所有進出標的資料
     *
     * @return array 進出標的資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'study_id'         => <流水編號>,
     *             'activity_id'      => <活動編號>,
     *             'target_id'        => <標的編號>,
     *             'is_enter'         => <是否為已進入的狀態，若否代表行進中>,
     *             'is_entity'        => <是否為現場學習>,
     *             'in_target_time'   => <進入時間>,
     *             'out_target_time'  => <離開時間>
     *         )
     *     );
     *
     */
    public function queryById($id) {
        $queryResultAll = $this->queryByWhere("`SID`=".$this->connDB->quote($id));

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
     * 以活動編號查詢所有進出標的資料
     *
     * @return array 進出標的資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'study_id'         => <流水編號>,
     *             'activity_id'      => <活動編號>,
     *             'target_id'        => <標的編號>,
     *             'is_enter'         => <是否為已進入的狀態，若否代表行進中>,
     *             'is_entity'        => <是否為現場學習>,
     *             'in_target_time'   => <進入時間>,
     *             'out_target_time'  => <離開時間>
     *         )
     *     );
     *
     */
    public function queryByActivityId($id) {

        return $this->queryByWhere("`SID`=".$this->connDB->quote($id));
    }

    /**
     * 查詢所有進出標的資料
     *
     * @return array 進出標的資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'study_id'         => <流水編號>,
     *             'activity_id'      => <活動編號>,
     *             'target_id'        => <標的編號>,
     *             'is_entity'        => <是否為現場學習>,
     *             'in_target_time'   => <進入時間>,
     *             'out_target_time'  => <離開時間>
     *         )
     *     );
     *
     */
    public function queryAll() {

        return $this->queryByWhere("1");
    }

    /**
     * 建立學習點進出記錄
     *
     * @param string $activity_id      活動編號
     * @param string $target_id        標的編號
     * @param string $is_enter         是否為已進入的狀態，若否代表行進中
     * @param string $is_entity        是否為現場學習
     * @param int    $in_target_time   進入時間
     * @param int    $out_target_time  離開時間
     *
     * @return int 剛新增的記錄編號
     * @since 2.0.0
     */
    public function insert($activity_id, $target_id, $is_enter,
                           $is_entity, $in_target_time, $out_target_time)
    {

        if(!isset($is_enter)) {
            $is_enter = true;
        }
        if(!isset($is_entity)) {
            $is_entity = true;
        }

        // 寫入
        $sqlString = "INSERT INTO `".$this->table('user_history').
            "` (`SaID`, `TID`, `IsEnter`, `IsEntity`, `In_TargetTime`, `Out_TargetTime`)
            VALUES ( :said , :tid , :entity , :intime , :outtime )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->bindParam(":tid", $target_id);
        $query->bindParam(":enter", $is_enter);
        $query->bindParam(":entity", $is_entity);
        $query->bindParam(":intime", $in_target_time);
        $query->bindParam(":outtime", $out_target_time);
        $query->execute();

        // 取得剛剛加入的ID
        $sqlString = "SELECT LAST_INSERT_ID()";
        $query = $this->connDB->query($sqlString);
        $queryResult = $query->fetch();
        $resultId = $queryResult[0];

        return $resultId;
    }

    /**
     * 移除一筆進出標的資料
     * @param int $id 進出標的編號
     * @since 2.0.0
     */
    public function delete($id) {

        $sqlString = "DELETE FROM ".$this->table('user_history').
                     " WHERE `SID` = :id ";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $id);
        $query->execute();
    }

    /**
     * 移除此學習活動的所有進出標的資料
     * @param int $id 學習活動編號
     * @since 2.0.0
     */
    public function deleteByActivityId($id) {

        $sqlString = "DELETE FROM ".$this->table('user_history').
                     " WHERE `SaID` = :id ";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $id);
        $query->execute();
    }

    /**
     * 進入學習點
     *
     * @param string $activity_id 活動編號
     * @param string $target_id   標的編號
     * @param string $is_entity   是否為現場學習
     * @return int 剛新增的記錄編號
     * @since 2.0.0
     */
    public function toInTarget($activity_id, $target_id, $is_entity)
    {

        if(!isset($is_entity)) {
            $is_entity = true;
        }

        // 寫入
        $sqlString = "INSERT INTO `".$this->table('user_history').
            "` (`SaID`, `TID`, `IsEnter`, `IsEntity`, `In_TargetTime`, `Out_TargetTime`)
            VALUES ( :said , :tid , '1' , :entity , NOW() , NULL )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->bindParam(":tid", $target_id);
        $query->bindParam(":entity", $is_entity);
        $query->execute();

        // 取得剛剛加入的ID
        $sqlString = "SELECT LAST_INSERT_ID()";
        $query = $this->connDB->query($sqlString);
        $queryResult = $query->fetch();
        $resultId = $queryResult[0];

        return $resultId;
    }

    /**
     * 離開學習點
     *
     * @param string $study_id 此記錄編號
     * @since 2.0.0
     */
    public function toOutTarget($study_id)
    {

        // 寫入
        $sqlString = "UPDATE `".$this->table('user_history').
            "` SET `Out_TargetTime` = NOW()
            WHERE `SID` = :id ";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $study_id);
        $query->execute();
    }

    /**
     * 此活動內離開所有學習點
     *
     * @param string $activity_id 活動編號
     * @since 2.0.0
     */
    public function alltoOutTarget($activity_id)
    {

        // 寫入
        $sqlString = "UPDATE `".$this->table('user_history').
            "` SET `Out_TargetTime` = NOW()
            WHERE `SaID` = :id ";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":id", $activity_id);
        $query->execute();
    }

    /**
     * 行進中，準備進入的學習點
     *
     * @param string $activity_id 活動編號
     * @param string $target_id   標的編號
     * @param string $is_entity   是否為現場學習
     * @return int 剛新增的記錄編號
     * @since 2.0.0
     */
    public function enteringInTarget($activity_id, $target_id)
    {

        if(!isset($is_entity)) {
            $is_entity = true;
        }

        // 寫入
        $sqlString = "INSERT INTO `".$this->table('user_history').
            "` (`SaID`, `TID`, `IsEnter`, `IsEntity`, `In_TargetTime`, `Out_TargetTime`)
            VALUES ( :said , :tid , '0' , '1' , NOW() , NULL )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->bindParam(":tid", $target_id);
        $query->execute();

        // 取得剛剛加入的ID
        $sqlString = "SELECT LAST_INSERT_ID()";
        $query = $this->connDB->query($sqlString);
        $queryResult = $query->fetch();
        $resultId = $queryResult[0];

        return $resultId;
    }

    /**
     * 取得目前正在進行的標的編號
     *
     * @param int $activity_id 活動編號
     * @return int 標的編號
     * @since 2.0.0
     */
    public function getCurrentInTargetId($activity_id) {

        $sqlString = "SELECT `TID` FROM `".$this->table('user_history')."` ".
            "WHERE `Out_TargetTime` IS NULL AND `SaID` = :said ".
            "AND `IsEnter` = '1'";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->execute();

        $queryResult = $query->fetch();
        // 如果有查到一筆以上
        if( count($queryResult) >= 1 ) {
            return (int)$queryResult[0];
        }
        else {
            return null;
        }
    }

    /**
     * 取得目前正在進行的標的編號
     *
     * @param int $activity_id 活動編號
     * @return int 標的編號
     * @since 2.0.0
     */
    public function getCurrentEnteringTargetId($activity_id) {

        $sqlString = "SELECT `TID` FROM `".$this->table('user_history')."` ".
            "WHERE `Out_TargetTime` IS NULL AND `SaID` = :said ".
            "AND `IsEnter` = '0'";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->execute();

        $queryResult = $query->fetch();
        // 如果有查到一筆以上
        if( count($queryResult) >= 1 ) {
            return (int)$queryResult[0];
        }
        else {
            return null;
        }
    }

    /**
     * 取得目前正在行進中標的編號
     *
     * @param int $activity_id 活動編號
     * @return int 標的編號
     * @since 2.0.0
     */
    public function getCurrentEnteringInTargetId($activity_id) {

        $sqlString = "SELECT `TID` FROM `".$this->table('user_history')."` ".
            "WHERE `Out_TargetTime` IS NULL AND `SaID` = :said ".
            "AND `IsEnter` = '0'";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->execute();

        $queryResult = $query->fetch();
        // 如果有查到一筆以上
        if( count($queryResult) >= 1 ) {
            return (int)$queryResult[0];
        }
        else {
            return null;
        }
    }

    /**
     * 取得目前正在進行的紀錄編號
     *
     * @param int $activity_id 活動編號
     * @return int 進出紀錄編號
     * @since 2.0.0
     */
    public function getCurrentInStudyId($activity_id) {

        $sqlString = "SELECT `SID` FROM `".$this->table('user_history')."` ".
            "WHERE `Out_TargetTime` IS NULL AND `SaID` = :said ".
            " AND `IsEnter` = '1'";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->execute();

        $queryResult = $query->fetch();
        // 如果有查到一筆以上
        if( count($queryResult) >= 1 ) {
            return (int)$queryResult[0];
        }
        else {
            return null;
        }
    }

    /**
     * 取得所有在此標的的記錄編號
     *
     * 正常來說只會有一個，但考量使用者可能在這次活動內同一標的進出兩次以上，故還是以二維陣列輸出。
     *
     * @param int $activity_id 活動編號
     * @param int $target_id 標的編號
     * @return array 所有進出記錄資訊陣列
     * @since 2.0.0
     */
    public function getAllStudyIdByTargetId($activity_id, $target_id) {

        $queryResultAll = $this->queryByWhere(
            "`TID` = ".$this->connDB->quote($target_id).
            " AND `SaID` = ".$this->connDB->quote($activity_id).
            " AND `IsEnter` = '1'");

        return $queryResultAll;
    }

    /**
     * 取得在此標學習中的記錄編號
     *
     * 正常來說只會有一個，但考量使用者可能在這次活動內同一標的進出兩次以上，故還是以陣列輸出。
     *
     * @param int $activity_id 活動編號
     * @param int $target_id 標的編號
     * @return array 所有進出記錄資訊陣列
     * @since 2.0.0
     */
    public function getInStudyIdByTargetId($activity_id, $target_id) {

        $queryResultAll = $this->queryByWhere(
            "`TID` = ".$this->connDB->quote($target_id).
            " AND `SaID` = ".$this->connDB->quote($activity_id).
            " AND `Out_TargetTime` IS NULL ".
            " AND `IsEnter` = '1'");

        return $queryResultAll;
    }

    /**
     * 取得在此標學習中的記錄編號
     *
     * 正常來說只會有一個，但考量使用者可能在這次活動內同一標的進出兩次以上，故還是以陣列輸出。
     *
     * @param int $activity_id 活動編號
     * @param int $target_id 標的編號
     * @return array 所有進出記錄資訊陣列
     * @since 2.0.0
     */
    public function getEnteringInStudyIdByTargetId($activity_id, $target_id) {

        $queryResultAll = $this->queryByWhere(
            "`TID` = ".$this->connDB->quote($target_id).
            " AND `SaID` = ".$this->connDB->quote($activity_id).
            " AND `Out_TargetTime` IS NULL ".
            " AND `IsEnter` = '0'");

        return $queryResultAll;
    }
}
