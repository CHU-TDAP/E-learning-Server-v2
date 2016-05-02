<?php
/**
 * DBQuestion.php
 *
 */

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';


class DBQuestion extends Database {

    public function insert($activity_id, $target_id, $question_time, $answer_time, $question_id, $answer, $correct){

        $sqlString = "INSERT INTO ".$this->table('user_history_question').
            " (`ID`, `SaID`, `TID`, `QDate`, `ADate`, `QID`, `Ans`, `Correct`)
            VALUES (NULL, :said , :tid , :qd , :ad , :qid , :ans , :cor )";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":said", $activity_id);
        $query->bindParam(":tid", $target_id);
        $query->bindParam(":qd", $question_time);
        $query->bindParam(":ad", $answer_time);
        $query->bindParam(":qid", $question_id);
        $query->bindParam(":ans", $answer);
        $query->bindParam(":cor", $correct);
        $query->execute();

    }

}
