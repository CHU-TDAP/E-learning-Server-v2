<?php
/**
 * DBTarget.php
 *
 * 此檔案針對學習標的，以及學習標的的區域、廳等的資料庫查詢用。
 */

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

/**
 * 學習標的資料表
 *
 * 此檔案針對學習標的，以及學習標的的區域、廳等的資料表進行操作。
 *
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBInfo extends Database {

    public function queryAllPlaceInfo() {
        $sqlString = "SELECT * FROM `".$this->table('place_info')."` WHERE 1";

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();

//        return $queryResultAll;

        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                array_push($result,
                    array( 'id'      => $thisResult['IID'],
                           'name'    => $thisResult['IName'],
                           'content' => $thisResult['IContent']
                ));
            }
            return $result;
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }
    public function queryALLPlaceMap() {
        $sqlString = "SELECT * FROM `".$this->table('place_map')."` WHERE 1";

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();

        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                array_push($result,
                    array( 'id'      => $thisResult['PID'],
                           'name'    => $thisResult['PName'],
                           'url' => $thisResult['PURL']
                ));
            }
            return $result;
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }

}
