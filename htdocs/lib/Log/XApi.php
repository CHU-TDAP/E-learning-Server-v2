<?php
/**
 * Log.php
 */

namespace UElearning\Log;

require_once UELEARNING_LIB_ROOT.'/Exception.php';
use UElearning\Exception;

/**
 * 學習記錄
 *
 * @version         2.0.1
 * @package         UElearning
 * @subpackage      Log
 */
class XApi {

    /**
     * 登入學習導覽系統
     * @param  string $login_date 紀錄時間
     * @param  string $token      登入token
     * @param  string $u_name     使用者顯示名稱
     * @param  string $u_email    使用者的Email
     * @param  string $c_name     班級名稱
     * @param  int    $lmode      導覽模式
     * @return array              轉換後XAPI陣列
     */
    public function login($login_date, $token, $u_name, $u_email, $c_name, $lmode)
    {
        $result = array(
          "actor"=> array(
            "objectType"=> "Group", //固定
            "name"=> "篤行國小", //TODO: 目前先寫死
            "mbox"=> null, //沒有
            "member"=> array(
              array(
                "objectType"=> "Group", //固定
                "name"=> $c_name, //資料表=>user_class的cname
                "mbox"=> null, //沒有
                "member"=> array(
                  array(
                    "objectType"=> "Agent", //固定
                    "name"=> $u_name, //資料表=>user的URealName
                    "mbox"=> $u_email, //資料表=>user的UEmail
                    "account"=> array(
                      "homePage"=> null, //沒有
                      "token"=> $token, //資料表=>user_session的UToken
                      "born"=> null, //沒有
                      "gender"=> null, //沒有
                      "lmode"=> $lmode //資料表=>user的LMode
                    )
                  )
                )
              )
            )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adl/verbs/logged-in", //固定
            "display"=> array(
              "zh-TW"=> "登入" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//elearning/id/1", //固定
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> "學習導覽系統"//固定
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $login_date //資料表=>user_session的ULoginDate
        );

        return $result;
    }

    /**
     * 選擇學習主題
     * @param  [type] $date       [description]
     * @param  [type] $token      [description]
     * @param  [type] $u_name     [description]
     * @param  [type] $u_email    [description]
     * @param  [type] $c_name     [description]
     * @param  [type] $theme_id   [description]
     * @param  [type] $theme_name [description]
     * @param  [type] $l_mode     [description]
     * @return [type]             [description]
     */
    public function createStudyActivity($date, $token, $u_name, $u_email, $c_name, $theme_id, $theme_name, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //TODO: 目前先寫死
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> $u_email, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/selected", //固定
            "display"=> array(
              "zh-TW"=> "選擇" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//thname/id/".$theme_id, //資料表=>learn_topic的ThID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> $theme_name //資料表=>learn_topic的ThName
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date //資料表=>user_activity的StartTime
      );

      return $result;
    }

    /**
     * 刷新學習點
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $tid     [description]
     * @param  [type] $t_name  [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function getRecommandPoints($date, $token, $u_name, $u_email, $c_name, $t_id, $t_name, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //TODO: 目前先寫死
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> $u_email, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/refresh", //固定
            "display"=> array(
              "zh-TW"=> "刷新" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//tname/id/".$t_id, //資料表=>learn_target的TID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> $t_name //資料表=>learn_target的TName
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date
        );

        return $result;
    }

    /**
     * 掃描教材
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $t_id    [description]
     * @param  [type] $t_name  [description]
     * @param  [type] $m_id    [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function scanToMaterial($date, $token, $u_name, $u_email, $c_name, $t_id, $t_name, $m_id, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //TODO: 目前先寫死
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> $u_email, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/scan", //固定
            "display"=> array(
              "zh-TW"=> "掃描" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//material/id/".$m_id, //資料庫=>material的MID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> "編號".$m_id."教材" //資料庫=>material的MID
              )
            )
          ),
          "place"=> array(
            "id"=> "http=>//tname/id/".$t_id, //資料表=>learn_target的TID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> $t_name //資料表=>learn_target的TName
              )
            )
          ),
          "timestamp"=> $date
        );
        return $result;
    }

    /**
     * 瀏覽學習點
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $t_id    [description]
     * @param  [type] $t_name  [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function browseTarget($date, $token, $u_name, $u_email, $c_name, $t_id, $t_name, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //TODO: 目前先寫死
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> $u_email, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/browse", //固定
            "display"=> array(
              "zh-TW"=> "瀏覽" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//tname/id/".$t_id, //資料表=>learn_target的TID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> $t_name //資料表=>learn_target的TName
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date
        );
        return $result;
    }

    /**
     * 選擇學習點
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $t_id    [description]
     * @param  [type] $t_name  [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function chooseTarget($date, $token, $u_name, $u_email, $c_name, $t_id, $t_name, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //TODO: 目前先寫死
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> $u_email, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/selected", //固定
            "display"=> array(
              "zh-TW"=> "選擇" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//tname/id/".$t_id, //資料表=>learn_target的TID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> $t_name //資料表=>learn_target的TName
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date
        );
        return $result;
    }

    /**
     * 點選掃描按鈕
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function pressScanButton($date, $token, $u_name, $u_email, $c_name, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //固定
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> $u_email, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/click", //固定
            "display"=> array(
              "zh-TW"=> "點選" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//scanbtn/id/1", //固定
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> "掃描按鈕" //固定
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date
        );

        return $result;
    }

    /**
     * 按下開始作答按鈕
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $m_id    [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function pressStartAnswer($date, $token, $u_name, $u_email, $c_name, $m_id, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //固定
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> null, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/started", //固定
            "display"=> array(
              "zh-TW"=> "開始" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//startbtn/id/".$m_id, //資料庫=>material的MID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> "開始作答按鈕" //固定
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date //資料表=>user_history_question的QDate
        );

        return $result;
    }

    /**
     * 閱讀教材
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $m_id    [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function readMaterial($date, $token, $u_name, $u_email, $c_name, $m_id, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //固定
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> $u_email, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> "http=>//www.fg11323.com", //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/read", //固定
            "display"=> array(
              "zh-TW"=> "閱讀" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//material/id/".$m_id, //資料庫=>material的MID
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> "編號".$m_id."教材" //資料庫=>material的MID
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date //資料表=>user_history的In_TargetTime
        );

        return $result;
    }

    /**
     * 離開教學活動
     * @param  [type] $date    [description]
     * @param  [type] $token   [description]
     * @param  [type] $u_name  [description]
     * @param  [type] $u_email [description]
     * @param  [type] $c_name  [description]
     * @param  [type] $l_mode  [description]
     * @return [type]          [description]
     */
    public function endStudyActivity($date, $token, $u_name, $u_email, $c_name, $l_mode)
    {
        $result = array(
          "actor"=> array(
          "objectType"=> "Group", //固定
          "name"=> "篤行國小", //固定
          "mbox"=> null, //沒有
          "member"=> array(
            array(
              "objectType"=> "Group", //固定
              "name"=> $c_name, //資料表=>user_class的cname
              "mbox"=> null, //沒有
              "member"=> array(
                array(
                  "objectType"=> "Agent", //固定
                  "name"=> $u_name, //資料表=>user的URealName
                  "mbox"=> null, //資料表=>user的UEmail
                  "account"=> array(
                    "homePage"=> null, //沒有
                    "token"=> $token, //資料表=>user_session的UToken
                    "born"=> null, //沒有
                    "gender"=> null, //沒有
                    "lmode"=> $l_mode //資料表=>user的LMode
                  )
                )
              )
            )
          )
          ),
          "verb"=> array(
            "id"=> "https=>//w3id.org/xapi/adb/verbs/exit", //固定
            "display"=> array(
              "zh-TW"=> "離開" //固定
            )
          ),
          "object"=> array(
            "objectType"=> "Activity", //固定
            "id"=> "http=>//elearning/id/1", //固定
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> "學習導覽系統" //固定
              )
            )
          ),
          "place"=> array(
            "id"=> null, //空
            "definition"=> array(
              "name"=> array(
                "zh-TW"=> null //空
              )
            )
          ),
          "timestamp"=> $date //資料表=>user_session的ULogoutDate
        )
    }
}
