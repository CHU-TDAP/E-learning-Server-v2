<?php
/**
 * StudyActivityManager
 */ 
namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBStudyActivity.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 學習階段管理類別
 * 
 * 開始活動、預約活動所用的
 * 
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class StudyActivityManager {
    
    /**
     * 
     * @param {Type} $uid 
     * @param {Type} tid 
     * @param {Type} mmode 
     * @return bool 輸入的資料是否存在來新增學習活動記錄
     */
    protected function checkDataIsExist($uid, $tid, $mmode) {        
        		
        // TODO: 使用者存不存在
        
        // TODO: 標的存不存在
        
        // TODO: 教材是否存在
        return true;
    }
    
    /**
     * 開始這次學習
     * 
     * @param string $userId           使用者ID
     * @param string $themeId          主題ID
     * @param int    $learnTime        所需學習時間(分)
     * @param bool   $timeForce        時間到時是否強制中止學習
     * @param int    $learnStyle       將推薦幾個學習點
     * @param bool   $learnStyle_force 是否拒絕前往非推薦的學習點
     * @param string $materialMode     教材風格
     * @return int 本次學習活動的流水編號
     * @since 2.0.0
     */ 
    public function startActivity( $userId, $themeId, $learnTime, $timeForce, 
                            $learnStyle, $learnStyle_force, $materialMode )
    {

        if($this->checkDataIsExist($userId, $themeId, $materialMode)) {
            
            // 存入資料庫
            $db = new Database\DBStudyActivity();
            $resultId = $db->insertActivity($userId, $themeId, null, null, 
                $learnTime, 0, $timeForce, $learnStyle, $learnStyle_force, $materialMode);

            // 傳回新增後得到的編號
            return $resultId;
        }
	}
    
    /**
     * 幫學生預約學習
     * 
     * @param string $userId           使用者ID
     * @param string $themeId          主題ID
     * @param string $startTime        預約開始時間
     * @param string $expiredTime      預約過期時間
     * @param int    $learnTime        所需學習時間(分)
     * @param bool   $timeForce        學習時間已過是否強制中止學習
     * @param int    $learnStyle       將推薦幾個學習點
     * @param bool   $learnStyle_force 是否拒絕前往非推薦的學習點
     * @param string $materialMode     教材風格
     * @param bool   $lock             是否鎖定不讓學生更改
     * @return int 預約學習活動的流水編號
     * @since 2.0.0
     */ 
    public function createWiilActivity($userId, $themeId, $startTime, $expiredTime,
            $learnTime, $timeForce, $learnStyle, $learnStyle_force, $materialMode, $lock)
    {
        
        if($this->checkDataIsExist($userId, $themeId, $materialMode)) {
            
            // 存入資料庫
            $db = new Database\DBStudyActivity();
            $resultId = $db->insertWillActivity($userId, $themeId, 
                    $startTime, $expiredTime, $learnTime, $timeForce, 
                    $learnStyle, $learnStyle_force, $materialMode, $lock);

            // 傳回新增後得到的編號
            return $resultId;
        }
    }
    
    // ========================================================================
    
    /**
     * 取得這位學生可以使用的學習活動有哪些
     * 
     * @param string $user_id 使用者ID
     * @return array 可用的學習活動資訊
     * @since 2.0.0
     */ 
    public function getEnableActivityByUserId($user_id) {
        
    }
}