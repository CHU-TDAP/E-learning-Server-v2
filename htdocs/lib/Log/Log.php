<?php
/**
 * Log.php
 */

namespace UElearning\Log;

require_once UELEARNING_LIB_ROOT.'/Database/DBLog.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 學習記錄
 *
 * @version         2.0.1
 * @package         UElearning
 * @subpackage      Log
 */
class Log {

    /**
     * 紀錄這筆資料
     *
     * @param array $logArray 這筆紀錄陣列，格式為:
     *     array( 'LID'         => '<唯一的加密代碼>',
     *            'UID'         => '<使用者ID>',
     *            'Date'        => '<日期時間>',
     *            'SaID'        => '<學習活動>',
     *            'TID'         => '<標的編號>',
     *            'ActionGroup' => '<動作分組>',
     *            'Encode'      => '<動作>',
     *            'QID'         => '<問題編號>',
     *            'Answer'      => '<回答編號>',
     *            'Other'       => '<其他資訊>' )
    )
     */
    public function insert($logArray) {
        // 檢查必填項目有無填寫
        if(isset($logArray)) {

            // 若無填寫
            if( !isset($logArray['LID'])  ||
                !isset($logArray['UID']) ||
                !isset($logArray['Date']) ||
                !isset($logArray['Encode']) ) {
                throw new Exception\NoDataException();
            }
            // 沒有問題
            else {

                // 處理未帶入的資料
                if( !isset($logArray['SaID']) ){
                    $logArray['SaID'] = null;
                }
                if( !isset($logArray['TID']) ){
                    $logArray['TID'] = null;
                }
                if( !isset($logArray['ActionGroup']) ){
                    $logArray['ActionGroup'] = null;
                }
                if( !isset($logArray['QID']) ){
                    $logArray['QID'] = null;
                }
                if( !isset($logArray['Answer']) ){
                    $logArray['Answer'] = null;
                }
                if( !isset($logArray['Other']) ){
                    $logArray['Other'] = null;
                }

                // 新增一筆使用者資料進資料庫
                $db = new Database\DBLog();
                $db->insertLog(
                    array(
                        'lid'            => $logArray['LID'],
                        'uid'            => $logArray['UID'],
                        'date'           => $logArray['Date'],
                        'said'           => $logArray['SaID'],
                        'tid'            => $logArray['TID'],
                        'actionGruop'    => $logArray['ActionGroup'],
                        'encode'         => $logArray['Encode'],
                        'qid'            => $logArray['QID'],
                        'answer'         => $logArray['Answer'],
                        'other'          => $logArray['Other']
                    )
                );
            }
        }
        else throw Exception\NoDataException();
    }
}
