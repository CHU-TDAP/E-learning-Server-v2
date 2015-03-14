<?php
/**
 * StudyManager.php
 */

namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBStudy.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Target/Target.php';
require_once UELEARNING_LIB_ROOT.'/Target/Exception.php';
use UElearning\Database;
use UElearning\Target;
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
     * @param int $activity_id 活動編號
     * @return int 標的編號，若無則null
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
     * 取得目前行進中的學習點
     * @param int $activity_id 活動編號
     * @return int 標的編號，若無則null
     */
    public function getCurrentEnteringTargetId($activity_id) {

        $db = new Database\DBStudy();
        return $db->getCurrentEnteringTargetId($activity_id);
    }

    /**
     * 此標的是否已學習過
     *
     * @param int $activity_id 活動編號
     * @param string $target_id 標的編號
     * @return bool 是否已學習過
     */
    public function isTargetLearned($activity_id, $target_id) {

        $db = new Database\DBStudy();
        $query = $db->getAllStudyIdByTargetId($activity_id, $target_id);
        if(count($query) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 進入標的
     *
     * @param int $activity_id 活動編號
     * @param int $target_id   標的編號
     * @param bool $is_entity   是否為現場學習
     * @throw UElearning\Exception\TargetNoFoundException
     * return int 進出紀錄編號
     */
    public function toInTarget($activity_id, $target_id, $is_entity) {

        // 若沒有任一個點正在學習中
        if($this->getCurrentInTargetId($activity_id) == null) {

            $db = new Database\DBStudy();

            // 紀錄進資料庫
            $id = $db->toInTarget($activity_id, $target_id, $is_entity);

            // 將標的目前人數+1
            if($is_entity) {
                $target = new Target\Target($target_id);
                $target->addMj(1);
            }

            // 取消行進中狀態
            $enterTId = $this->getCurrentEnteringTargetId($activity_id);
            $this->outEnteringInTarget($activity_id, $enterTId);

            return $id;
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
     * @throw UElearning\Exception\NoInLearningException
     */
    public function toOutTarget($activity_id, $target_id) {

        // 從資料庫取得此活動此標的學習中資料
        $db = new Database\DBStudy();
        $learning_array = $db->getInStudyIdByTargetId($activity_id, $target_id);
        $target = new Target\Target($target_id);

        // 找到正在學習中的資料
        if(isset($learning_array)) {

            // 將所有此標的的進入紀錄全部標示
            foreach($learning_array as $thisArray) {

                // 將此紀錄標示為已離開
                $db->toOutTarget($thisArray['study_id']);

                // 將標的目前人數-1
                if($thisArray['is_entity'] = true) {
                    $target = new Target\Target($target_id);
                    $target->addMj(-1);
                }
            }

            // 取消行進中狀態
            $enterTId = $this->getCurrentEnteringTargetId($activity_id);
            $this->outEnteringInTarget($activity_id, $enterTId);
        }
        else {
            throw new Exception\NoInLearningException();
        }
    }

    /**
     * 行進中，準備進入的學習點
     *
     * @param int $activity_id 活動編號
     * @param int $target_id   標的編號
     * @throw UElearning\Exception\TargetNoFoundException
     * return int 進出紀錄編號
     */
    public function enteringInTarget($activity_id, $target_id) {

        // 若沒有任一個點正在學習中
        if($this->getCurrentInTargetId($activity_id) == null) {
            $db = new Database\DBStudy();

            // 取消行進中狀態
            $enterTId = $this->getCurrentEnteringTargetId($activity_id);
            $this->outEnteringInTarget($activity_id, $enterTId);

            // 紀錄進資料庫
            $id = $db->enteringInTarget($activity_id, $target_id);

            // 將標的目前人數+1
            $target = new Target\Target($target_id);
            $target->addMj(1);

            return $id;
        }
        else {
            throw new Exception\InLearningException();
        }
    }

    /**
     * 取消行進中，準備進入的學習點
     *
     * @param int $activity_id 活動編號
     * @param int $target_id   標的編號
     */
    public function outEnteringInTarget($activity_id, $target_id) {

        // 從資料庫取得此活動此標的學習中資料
        $db = new Database\DBStudy();
        $learning_array = $db->getEnteringInStudyIdByTargetId($activity_id, $target_id);
        $target = new Target\Target($target_id);

        // 將所有此標的的進入紀錄全部標示
        // 找到正在學習中的資料
        if(isset($learning_array)) {

            foreach($learning_array as $thisArray) {

                // 將此紀錄標示為已離開
                $db->toOutTarget($thisArray['study_id']);

                // 將標的目前人數-1
                $target = new Target\Target($target_id);
                $target->addMj(-1);
            }
        }
    }
}
