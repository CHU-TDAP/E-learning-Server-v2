<?php
/**
 * StudyActivityManager
 */
namespace UElearning\Study;

require_once UELEARNING_LIB_ROOT.'/Database/DBStudyActivity.php';
require_once UELEARNING_LIB_ROOT.'/Study/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Study/StudyWill.php';
require_once UELEARNING_LIB_ROOT.'/Study/Theme.php';
require_once UELEARNING_LIB_ROOT.'/User/User.php';
use UElearning\User;
use UElearning\Database;
use UElearning\Exception;

/**
 * 學習階段管理類別
 *
 * 開始活動、預約活動所用的
 *
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Study
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
     * @param bool   $enable_virtual   是否啟用虛擬教材
     * @param string $materialMode     教材風格
     * @throw UElearning\Exception\UserNoFoundException
     * @throw UElearning\Exception\ThemeNoFoundException
     * @return int 本次學習活動的流水編號
     * @since 2.0.0
     */
    public function startActivity( $userId, $themeId, $learnTime, $timeForce,
                            $learnStyle, $learnStyle_force, $enable_virtual, $materialMode )
    {

        $user = new User\User($userId);

        $theme = new Theme($themeId);

        // 若無指定學習時間
        if(!isset($learnTime)) {
            $learnTime = $theme->getLearnTime();
        }

        // 若無指定強制時間
        if(!isset($timeForce)) {
            $timeForce = false;
        }

        // 若無指定學習導引模式
        if(!isset($learnStyle)) {
            // 若使用者有偏好
            if($user->getLearnStyle() == null){
                $learnStyle = $user->getLearnStyle();
            }
            else {
                $learnStyle = LMODE;
            }
        }

        // 若無指定強制學習導引模式
        if(!isset($learnStyle_force)) {
            $learnStyle_force = false;
        }

        // 若無指定教材
        if(!isset($materialMode)) {
            // 若使用者有偏好
            if($user->getMaterialStyle() == null)
                $materialMode = $user->getMaterialStyle();
            else {
                $materialMode = MMODE;
            }

        }

        // 若無指定是否要使用虛擬教材
        if(!isset($enable_virtual)) {
            $enable_virtual = false;
        }

        // 存入資料庫
        $db = new Database\DBStudyActivity();
        $resultId = $db->insertActivity($userId, $themeId, null, null,
            $learnTime, 0, $timeForce, $learnStyle, $learnStyle_force, $enable_virtual, $materialMode);

        // 傳回新增後得到的編號
        return $resultId;
    }

    /**
     * 從預約開始進行學習活動
     *
     * @param int $swid 預約編號
     * @return int 本次學習活動的流水編號
     * @since 2.0.0
     */
    public function startWithWillActivity($swid) {

        // 取得預約資料
        $sact = new StudyWill($swid);
        $userId           = $sact->getUserId();
        $themeId          = $sact->getThemeId();
        $learnTime        = $sact->getLearnTime();
        $timeForce        = $sact->isForceLearnTime();
        $learnStyle       = $sact->getLearnStyle();
        $learnStyle_force = $sact->isForceLearnStyle();
        $enable_virtual   = $sact->isEnableVirtual();
        $materialMode     = $sact->getMaterialStyle();

        $this->startActivity( $userId, $themeId, $learnTime, $timeForce,
                            $learnStyle, $learnStyle_force, $enable_virtual, $materialMode );
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
     * @param bool   $enable_virtual   是否啟用虛擬教材
     * @param string $materialMode     教材風格
     * @param bool   $lock             是否鎖定不讓學生更改
     * @return int 預約學習活動的流水編號
     * @since 2.0.0
     */
    public function createWiilActivity($userId, $themeId, $startTime, $expiredTime,
                            $learnTime, $timeForce, $learnStyle, $learnStyle_force,
                            $enable_virtual, $materialMode, $lock)
    {

        if($this->checkDataIsExist($userId, $themeId, $materialMode)) {

            // 存入資料庫
            $db = new Database\DBStudyActivity();
            $resultId = $db->insertWillActivity($userId, $themeId,
                    $startTime, $expiredTime, $learnTime, $timeForce,
                    $learnStyle, $learnStyle_force, $materialMode, $materialMode, $lock);

            // 傳回新增後得到的編號
            return $resultId;
        }
    }

    // ========================================================================

    /**
     * 取得這位學生可以使用的學習活動有哪些
     *
     * @param string $user_id 使用者ID
     * @return array 可用的學習活動資訊，格式如下:
     *
     *     array(
     *         array(
     *             'type'             => <類型>,
     *             'id'               => <編號>,
     *             'activity_id'      => <學習活動ID>,
     *             'activity_will_id' => <預約活動ID>,
     *             'theme_id'         => <主題ID>,
     *             'start_time'       => <開始生效時間>,
     *             'expired_time'     => <過期時間>,
     *             'have_time'        => <擁有的學習時間(分)>,
     *             'learn_time'       => <預定學習時間(分)>,
     *             'delay'            => <延期時間(分)>,
     *             'remaining_time'   => <剩餘學習時間(分)>,
     *             'time_force'       => <時間到時是否強制中止學習>,
     *             'learnStyle_mode'  => <學習導引模式>,
     *             'learnStyle_force' => <拒絕前往非推薦的學習點>,
     *             'enable_virtual'   => <是否啟用虛擬教材>,
     *             'material_mode'    => <教材模式>,
     *             'lock'             => <是否鎖定不讓學生更改>,
     *             'target_total'     => <有多少標的學習>,
     *             'learned_total'    => <還剩下幾個學習點還沒學>
     *         )
     *     );
     *
     * @since 2.0.0
     */
    public function getEnableActivityByUserId($user_id) {

        $db = new Database\DBStudyActivity();
        return $db->getEnableActivityByUserId($user_id);
    }
}
