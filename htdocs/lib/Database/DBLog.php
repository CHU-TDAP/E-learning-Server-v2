<?php
/**
 * DBLog.php
 *
 */

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';


class DBLog extends Database {

    public function insertLog($array){


        if( !isset($array['said']) ){
            $array['said'] = null;
        }
        if( !isset($array['tid']) ){
            $array['tid'] = null;
        }
        if( !isset($array['qid']) ){
            $array['qid'] = null;
        }
        if( !isset($array['answer']) ){
            $array['answer'] = null;
        }
        if( !isset($array['other']) ){
            $array['other'] = null;
        }
        // TODO: 不填enable, enable_noAppoint也要能操作
        $LID          = $array['lid'];
        $UId          = $array['uid'];
        $Date         = $array['date'];
        $SaID         = $array['said'];
        $TID          = $array['tid'];
        $ActionGruop  = $array['actionGruop'];
        $Encode       = $array['encode'];
        $QID          = $array['qid'];
        $Answer       = $array['answer'];
        $Other        = $array['other'];

        //紀錄使用者帳號進資料庫
        $sqlString = "INSERT INTO ".$this->table('user_log').
            " (`LID`, `UID`, `Date`, `SaID`, `TID`,
            `ActionGroup`, `Encode`,
            `QID`, `Aswer`, `Other`)
            VALUES ( :lid , :uid, :date , :said , :tid ,
            :actionGruop , :encode , :qid ,
            :answer , :other )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":lid", $LID);
        $query->bindParam(":uid", $UId);
        $query->bindParam(":date", $Date);
        $query->bindParam(":said", $SaID);
        $query->bindParam(":tid", $TID);
        $query->bindParam(":actionGruop", $ActionGruop);
        $query->bindParam(":encode", $Encode);
        $query->bindParam(":qid", $QID);
        $query->bindParam(":answer", $Answer);
        $query->bindParam(":other", $Other);
        $query->execute();

    }

    /**
     * 內部使用的查詢動作
     * @param string $where 查詢語法
     * @return array 查詢結果陣列
     */
    protected function queryLogByWhere($where) {

        $sqlString = "SELECT * FROM `".$this->table('user_log')."` ".
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
                    array( 'lid'            => $thisResult['LID'],
                           'uid'            => $thisResult['UID'],
                           'date'           => $thisResult['Date'],
                           'said'           => $thisResult['SaID'],
                           'tid'            => $thisResult['TID'],
                           'actionGruop'    => $thisResult['ActionGroup'],
                           'encode'         => $thisResult['Encode'],
                           'qid'            => $thisResult['QID'],
                           'answer'         => $thisResult['Aswer'],
                           'other'          => $thisResult['Other']
                    )
                );
            }
            return $result;
        }
        else {
            return null;
        }
    }

    public function queryLog($lid) {

        $queryResultAll = $this->queryLogByWhere("`LID`=".$this->connDB->quote($lid));

        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            return $queryResultAll[0];
        }
        // 若都沒查到的話
        else {
            return null;
        }
    }

    public function queryAllLog() {
        return $this->queryLogByWhere("1");
    }
}
