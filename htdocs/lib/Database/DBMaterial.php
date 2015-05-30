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
class DBMaterial extends Database {

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryMaterialByWhere($where) {

        $sqlString = "SELECT * FROM `".$this->table('material')."` ".
                     "WHERE ".$where;

        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {

                if($thisResult['MEntity'] != '0') {
                    $output_entiry = true;
                }
                else { $output_entiry = false; }

                array_push($result,
                    array( 'material_id'   => $thisResult['MID'],
                           'target_id'     => $thisResult['TID'],
                           'is_entity'     => $output_entiry,
                           'mode'          => $thisResult['MMode'],
                           'url'           => $thisResult['MUrl']
                ));
            }
            return $result;
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }


    /**
     * 查詢一個標的資料
     *
     *
     * 範例:
     *
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/Database/DBMaterial.php';
     *     use UElearning\Database;
     *
     *     $db = new Database\DBMaterial();
     *     $materialInfo = $db->queryMaterial(1);
     *     echo '<pre>'; print_r($materialInfo); echo '</pre>';
     *
     *
     * @param int $mId 教材ID
     * @return array 教材資料陣列，格式為:
     *     array(
     *         'material_id'   => <教材ID>,
     *         'target_id'     => <標的ID>,
     *         'is_entity'     => <是否為實體教材>,
     *         'mode'          => <教材類型>,
     *         'url'           => <此標的教材路徑>
     *     );
     *
     */
    public function queryMaterial($mId) {

        $queryResultAll = $this->queryMaterialByWhere("`MID`=".$this->connDB->quote($mId));

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
     * 查詢此標的內所教材的資料
     *
     * @param  int   $tID 標的ID
     * @return array 教材資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'material_id'   => <教材ID>,
     *             'target_id'     => <標的ID>,
     *             'is_entity'     => <是否為實體教材>,
     *             'mode'          => <教材類型>,
     *             'url'           => <此標的教材路徑>
     *         );
     *     );
     *
     */
    public function queryAllMaterialByTargetId($tID) {

        return $this->queryMaterialByWhere("`TID`=".$this->connDB->quote($tID));
    }

    /**
     * 查詢所有教材資料
     *
     * @return array 教材資料陣列，格式為:
     *
     *     array(
     *         array(
     *             'material_id'   => <教材ID>,
     *             'target_id'     => <標的ID>,
     *             'is_entity'     => <是否為實體教材>,
     *             'mode'          => <教材類型>,
     *             'url'           => <此標的教材路徑>
     *         );
     *     );
     *
     */
    public function queryAllMaterial() {

        return $this->queryMaterialByWhere("1");
    }

    // ========================================================================

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryKindByWhere($where) {

        $sqlString = "SELECT * FROM `".$this->table('material_kind')."` ".
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
                    array( 'material_kind_id'   => $thisResult['MkID'],
                           'material_kind_name' => $thisResult['MkName']
                ));
            }
            return $result;
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }

    /**
     * 查詢所有教材類別資料
     *
     * @return array 教材類別資料陣列
     *
     */
    public function queryAllKind() {

        return $this->queryKindByWhere("1");
    }

}
