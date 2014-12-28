<?php
/**
 * Target.php
 */

namespace UElearning\Target;

require_once UELEARNING_LIB_ROOT.'/Database/DBTarget.php';
require_once UELEARNING_LIB_ROOT.'/Target/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 標的管理類別
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class TargetManager {

    /**
     * 取得這個主題內所有的標的資訊
     *
     * @param  int   $thID 主題ID
     * @return array 標的資訊清單陣列，格式為:
     *
     *     array(
     *         array(
     *             'theme_id'      => <主題ID>,
     *             'target_id'     => <標的ID>,
     *             'weights'       => <權重>
     *             'hall_id'       => <標的所在的廳ID>,
     *             'hall_name'     => <標的所在的廳名稱>,
     *             'area_id'       => <標的所在的區域ID>,
     *             'area_name'     => <標的所在的區域名稱>,
     *             'floor'         => <標的所在的區域樓層>,
     *             'area_number'   => <標的所在的區域地圖上編號>,
     *             'target_number' => <地圖上的標的編號>,
     *             'name'          => <標的名稱>,
     *             'map_url'       => <地圖路徑>,
     *             'learn_time'    => <預估的學習時間>,
     *             'PLj'           => <學習標的的人數限制>,
     *             'Mj'            => <目前人數>,
     *             'S'             => <學習標的飽和率上限>,
     *             'Fj'            => <學習標的滿額指標>
     *         )
     *     );
     *
     * @since 2.0.0
     */
    public function getAllTargetInfoByTheme($thID) {

        $db = new Database\DBTarget();
        return $db->queryAllTargetByTheme($thID);
    }

    /**
     * 取得所有的標的資訊
     *
     * @return array 標的資訊清單陣列，格式為:
     *
     *     array(
     *         array(
     *             'target_id'     => <標的ID>,
     *             'area_id'       => <標的所在的區域ID>,
     *             'hall_id'       => <標的所在的廳ID>,
     *             'target_number' => <地圖上的標的編號>,
     *             'name'          => <標的名稱>,
     *             'map_url'       => <地圖路徑>,
     *             'learn_time'    => <預估的學習時間>,
     *             'PLj'           => <學習標的的人數限制>,
     *             'Mj'            => <目前人數>,
     *             'S'             => <學習標的飽和率上限>,
     *             'Fj'            => <學習標的滿額指標>
     *         )
     *     );
     *
     * @since 2.0.0
     */
    public function getAllTargetInfo() {

        $db = new Database\DBTarget();
        return $db->queryAllTarget();
    }

}
