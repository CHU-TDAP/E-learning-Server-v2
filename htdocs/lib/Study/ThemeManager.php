<?php
/**
 * ThemeManager.php
 */

namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBTheme.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 主題管理類別
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class ThemeManager {

    /**
     * 取得所有的主題資訊清單
     *
     * @return array 班級資訊清單陣列，格式為:
     *
     *     array(
     *         array(
     *             'theme_id'      => <主題ID>,
     *             'name'          => <主題名稱>,
     *             'learn_time'    => <預估的學習時間>,
     *             'introduction'  => <主題介紹>,
     *             'build_time'    => <主題建立時間>,
     *             'modify_time'   => <主題資料修改時間>
     *         )
     *     );
     *
     * @since 2.0.0
     */
    public function getInfoList() {

        $db = new Database\DBTheme();
        $queryResult = $db->queryAllTheme();
        return $queryResult;
    }
}
