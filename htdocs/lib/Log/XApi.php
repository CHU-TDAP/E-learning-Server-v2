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
     * 閱讀教材
     * @param  [type] $date        [description]
     * @param  [type] $token       [description]
     * @param  [type] $duration    [description]
     * @param  [type] $u_name      [description]
     * @param  [type] $u_email     [description]
     * @param  [type] $c_name      [description]
     * @param  [type] $sa_id       [description]
     * @param  [type] $l_mode      [description]
     * @param  [type] $hall_id     [description]
     * @param  [type] $hall_name   [description]
     * @param  [type] $area_id     [description]
     * @param  [type] $area_name   [description]
     * @param  [type] $floor       [description]
     * @param  [type] $target_id   [description]
     * @param  [type] $target_name [description]
     * @param  [type] $material_id [description]
     * @return [type]              [description]
     */
    public function readMaterial($date, $token, $duration, $u_name, $u_email, $c_name,
    $sa_id, $l_mode,
    $hall_id, $hall_name, $area_id, $area_name, $floor, $target_id, $target_name, $material_id)
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
                          "said" => $sa_id, //資料表user_activity的SaID
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
              "id"=> "http=>//material/id/".$material_id, //資料庫=>material的MID
              "definition"=> array(
                "name"=> array(
                  "zh-TW"=> "編號".$material_id."教材" //資料庫=>material的MID
                )
              )
            ),
            "place"=> array(
             "longitude"=> null, //經度
             "latitude"=> null, //緯度
             "Museum"=> array( //展館
              array(
               "id"=> "http=>//museum/id/1",   //固定
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> "台中科學博物館" //固定
                 )
               )
              )
             ),
             "Hall"=> array( //展廳
              array(
               "id"=> "http=>//hall/id/".$hall_id,   //資料表=>learn_hall的HID
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> $hall_name   //資料表=>learn_hall的HName
                 )
               )
              )
             ),
             "Area"=> array( //展區
              array(
               "id"=> "http=>//area/id/".$area_id,   //資料表=>learn_area的AID
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> $area_name     //資料表learn_area的AName
                 )
               )
              )
             ),
             "Floor"=> array( //樓層
              array(
               "id"=> "http=>//floor/id/".$floor,   //資料表=>learn_area的AFloor
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> "樓層".$floor          //資料表=>learn_area的AFloor
                 )
               )
              )
             ),
             "Spot"=> array( //展點
              array(
               "id"=> "http=>//spot/id/".$target_id,   //資料表=>learn_target的TID
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> $target_name       //資料表=>learn_target的TName
                 )
               )
              )
             ),
             "Exhibits"=> array( //展品
              array(
               "id"=> null,
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> null
                 )
               )
              )
             )
            ),
            "result" => array(
               "success"=> true, //true or false
               "completion"=> true, //true or false
               "response" => null, //空
               "duration"=> $duration  //閱讀期間多久 (進入教材到開啟問題的時間)
            ),
            "timestamp"=> $date //資料表=>user_history的In_TargetTime
        );
        return $result;
    }

    /**
     * 回答問題
     * @param  [type] $date        [description]
     * @param  [type] $token       [description]
     * @param  [type] $duration    [description]
     * @param  [type] $u_name      [description]
     * @param  [type] $u_email     [description]
     * @param  [type] $c_name      [description]
     * @param  [type] $sa_id       [description]
     * @param  [type] $l_mode      [description]
     * @param  [type] $hall_id     [description]
     * @param  [type] $hall_name   [description]
     * @param  [type] $area_id     [description]
     * @param  [type] $area_name   [description]
     * @param  [type] $floor       [description]
     * @param  [type] $target_id   [description]
     * @param  [type] $target_name [description]
     * @param  [type] $question_id [description]
     * @param  [type] $answer      [description]
     * @param  [type] $completion  [description]
     * @return [type]              [description]
     */
    public function answerQuestion($date, $token, $duration, $u_name, $u_email, $c_name,
    $sa_id, $l_mode,
    $hall_id, $hall_name, $area_id, $area_name, $floor, $target_id, $target_name,
    $question_id, $answer, $completion)
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
                          "said" => $sa_id, //資料表user_activity的SaID
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
              "id"=> "https=>//w3id.org/xapi/adb/verbs/answer", //固定
              "display"=> array(
                "zh-TW"=> "回答" //固定
              )
            ),
            "object"=> array(
              "objectType"=> "Activity", //固定
              "id"=> "http=>//option/id/".$question_id, //資料表=>user_history_question的QID
              "definition"=> array(
                "name"=> array(
                  "zh-TW"=> "問題".$question_id //資料表=>user_history_question的QID
                )
              )
            ),
            "place"=> array(
             "longitude"=> null, //經度
             "latitude"=> null, //緯度
             "Museum"=> array( //展館
              array(
               "id"=> "http=>//museum/id/1",   //固定
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> "台中科學博物館" //固定
                 )
               )
              )
             ),
             "Hall"=> array( //展廳
              array(
               "id"=> "http=>//hall/id/".$hall_id,   //資料表=>learn_hall的HID
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> $hall_name   //資料表=>learn_hall的HName
                 )
               )
              )
             ),
             "Area"=> array( //展區
              array(
               "id"=> "http=>//area/id/".$area_id,   //資料表=>learn_area的AID
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> $area_name     //資料表learn_area的AName
                 )
               )
              )
             ),
             "Floor"=> array( //樓層
              array(
               "id"=> "http=>//floor/id/".$floor,   //資料表=>learn_area的AFloor
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> "樓層"          //資料表=>learn_area的AFloor
                 )
               )
              )
             ),
             "Spot"=> array( //展點
              array(
               "id"=> "http=>//spot/id/".$target_id,   //資料表=>learn_target的TID
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> $target_name       //資料表=>learn_target的TName
                 )
               )
              )
             ),
             "Exhibits"=> array( //展品
              array(
               "id"=> null,
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> null
                 )
               )
              )
             )
            ),
            "result" => array(
               "success"=> true, //true or false
               "completion"=> $completion, //true or false
               "response" => $answer, //資料表=>user_history_question的Ans
               "duration"=> $duration  //回答期間多久(進入問題到回答問題的時間)
            ),
            "timestamp"=> $date //資料表=>user_history_question的ADate
        );
        return $result;
    }

    public function endStudyActivity($date, $token, $duration, $u_name, $u_email, $c_name,
    $sa_id, $l_mode)
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
                          "said" => $sa_id, //資料表user_activity的SaID
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
              "id"=> "https=>//w3id.org/xapi/adb/verbs/finish", //固定
              "display"=> array(
                "zh-TW"=> "結束" //固定
              )
            ),
            "object"=> array(
              "objectType"=> "Activity", //固定
              "id"=> "http=>//elearning/id/".$sa_id, //固定
              "definition"=> array(
                "name"=> array(
                  "zh-TW"=> "學習導覽系統" //固定
                )
              )
            ),
            "place"=> array(
             "longitude"=> null, //經度
             "latitude"=> null, //緯度
             "Museum"=> array( //展館
              array(
               "id"=> "http=>//museum/id/1",   //固定
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> "台中科學博物館" //固定
                 )
               )
              )
             ),
             "Hall"=> array( //展廳
              array(
               "id"=> "http=>//hall/id/1",   //資料表=>learn_hall的HID
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> "生命科學廳"   //資料表=>learn_hall的HName
                 )
               )
              )
             ),
             "Area"=> array( //展區
              array(
               "id"=> null,
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> null
                 )
               )
              )
             ),
             "Floor"=> array( //樓層
              array(
               "id"=> null,
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> null
                 )
               )
              )
             ),
             "Spot"=> array( //展點
              array(
               "id"=> null,
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> null
                 )
               )
              )
             ),
             "Exhibits"=> array( //展品
              array(
               "id"=> null,
               "definition"=> array(
                 "name"=> array(
                   "zh-TW"=> null
                 )
               )
              )
             )
            ),
            "result" => array(
               "success"=> true, //true or false
               "completion"=> true, //true or false
               "response" => null, //空
               "duration"=> $duration  //在學習導覽系統期間多久(登入到登出的時間)
            ),
            "timestamp"=> $date //資料表=>user_history_question的ADate
        );
        return $result;
    }


}
