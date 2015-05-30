<?php
/**
 * DBTheme.php
 */

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

/**
 * 學習主題資料表
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBTheme extends Database {

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryThemeByWhere($where) {

        $sqlString = "SELECT `ThID`, `ThName`, ".
                     "`ThLearnTime`, `StartTID`, `ThIntroduction`, ".
                     "`ThBuildTime`, `ThModifyTime`, ".
                     "(SELECT count(`TID`) FROM `".$this->table('learn_topic_belong')."` AS `belong`
                     WHERE `belong`.`ThID` = `theme`.`ThID`) AS `TargetTotal`".
                     "FROM `".$this->table('learn_topic')."` AS `theme` ".
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
                    array( 'theme_id'        => $thisResult['ThID'],
                           'name'            => $thisResult['ThName'],
                           'learn_time'      => $thisResult['ThLearnTime'],
                           'start_target_id' => $thisResult['StartTID'],
                           'introduction'    => $thisResult['ThIntroduction'],
                           'target_total'    => $thisResult['TargetTotal'],
                           'build_time'      => $thisResult['ThBuildTime'],
                           'modify_time'     => $thisResult['ThModifyTime'] )
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
     * 查詢一個主題資料
     *
     *
     * 範例:
     *
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/Database/DBTheme.php';
     *     use UElearning\Database;
     *
     *     $db = new Database\DBTheme();
     *
     *     $info = $db->queryTheme(1);
     *     echo '<pre>'; print_r($info); echo '</pre>';
     *
     *
     * @param int $thId 主題ID
     * @return array 主題資料陣列，格式為:
     *     array(
     *         'theme_id'      => <主題ID>,
     *         'name'          => <主題名稱>,
     *         'learn_time'    => <預估的學習時間>,
     *         'introduction'  => <主題介紹>,
     *         'target_total'  => <此主題內有多少標的>,
     *         'build_time'    => <主題建立時間>,
     *         'modify_time'   => <主題資料修改時間>
     *     );
     *
     */
    public function queryTheme($thId) {

        $queryResultAll =
            $this->queryThemeByWhere("`ThID`=".$this->connDB->quote($thId));

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
     * 查詢所有主題資料
     *
     * @return array 主題資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'theme_id'      => <主題ID>,
     *             'name'          => <主題名稱>,
     *             'learn_time'    => <預估的學習時間>,
     *             'introduction'  => <主題介紹>,
     *             'target_total'  => <此主題內有多少標的>,
     *             'build_time'    => <主題建立時間>,
     *             'modify_time'   => <主題資料修改時間>
     *         )
     *     );
     *
     */
    public function queryAllTheme() {

        return $this->queryThemeByWhere("1");
    }

//    /**
//     * 修改一個標的資訊
//     *
//     * @param int    $tId   標的編號
//     * @param string $field 欄位名稱
//     * @param string $value 內容
//     */
//    public function changeTargetData($tId, $field, $value) {
//        $sqlField = null;
//        switch($field) {
//            case 'area_id':       $sqlField = 'AID';         break;
//            case 'hall_id':       $sqlField = 'HID';         break;
//            case 'target_number': $sqlField = 'TNum';        break;
//            case 'name':          $sqlField = 'TName';       break;
//            case 'map_url':       $sqlField = 'TMapID';      break;
//            case 'learn_time':    $sqlField = 'TLearnTime';  break;
//            case 'PLj':           $sqlField = 'PLj';         break;
//            case 'Mj':            $sqlField = 'Mj';          break;
//            case 'S':             $sqlField = 'S';           break;
//            case 'Fj':            $sqlField = 'Fj';          break;
//            default:              $sqlField = $field;        break;
//        }
//
//
//        $sqlString = "UPDATE ".$this->table('learn_target').
//                     " SET `".$sqlField."` = :value".
//                     " WHERE `TID` = :tid";
//
//        $query = $this->connDB->prepare($sqlString);
//        $query->bindParam(':tid', $tId);
//        $query->bindParam(':value', $value);
//        $query->execute();
//    }

}
