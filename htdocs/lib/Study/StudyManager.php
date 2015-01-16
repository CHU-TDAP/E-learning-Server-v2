<?php
/**
 * StudyManager.php
 */

namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBStudy.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 學習點進出記錄
 *
 * 一個物件即代表此學習點進出記錄
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Study
 */
class StudyManager {

    /**
     * 取得目前已進入的學習點
     * @param string $activity_id 活動編號
     * @return string 標的編號，若無則null
     */
    public function getCurrentInTargetId($activity_id) {

        $db = new Database\DBStudy();
        return $db->getCurrentInTargetId($activity_id);
    }

    /**
     * 取得目前已進入的學習點的進出紀錄物件
     * @param string $activity_id 活動編號
     * @return int 進出紀錄編號
     */
    public function getCurrentInStudyId($activity_id) {

        $db = new Database\DBStudy();
        return $db->getCurrentInStudyId($activity_id);
    }

    /**
     * 進入標的
     *
     * @param int $activity_id 活動編號
     * @param int $target_id   標的編號
     * @param bool $is_entity   是否為現場學習
     * return int 進出紀錄編號
     */
    public function toInTarget($activity_id, $target_id, $is_entity) {

        // 若沒有任一個點正在學習中
        if($this->getCurrentInTargetId($activity_id) == null) {
            $db = new Database\DBStudy();
            return $db->toInTaeget($activity_id, $target_id, $is_entity);
        }
        else {
            throw new Exception\InLearningException();
        }
    }

    /**
     * 離開標的
     *
     * @param int $activity_id 活動編號
     * @param int $target_id   標的編號
     */
    public function toOutTarget($activity_id, $target_id) {

        // 從資料庫取得此活動此標的學習中資料
        $db = new Database\DBStudy();
        $learning_array = $db->getInStudyIdByTargetId($activity_id, $target_id);

        if(isset($learning_array)) {

            foreach($learning_array as $thisArray) {

                $db->toOutTaeget($thisArray['study_id']);
            }
        }
    }
}
