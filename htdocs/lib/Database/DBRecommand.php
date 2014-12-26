<?php

namespace UElearning\Database;

use UElearning\Exception;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

class DBRecomamnd extends Database
{

    /**
     * 內部查詢用
     * @param string $where SQL WHERE子句
     * @return array 查詢結果
     */
	protected function queryBelongByWhere($where)
	{
		$sqlString = "SELECT DISTINCT ".$this->table('Edge').".Ti, ".$this->table('Edge').".Tj, ".$this->table('Edge').".MoveTime".
                     " FROM ".$this->table('Edge')." WHERE ".$where;
        $this->conndb->prepare($sqlString);
        $this->conndb->execute();

        $queryAllResult = $this->conndb->fetchAll();

        if(count($queryAllResult) != 0)
        {
            $result  = array();
            foreach ($queryAllResult as $key => $thisResult)
            {
                array_push($result,
                    array("Ti" => $thisResult['Ti'],
                          "Tj" => $thisResult['Tj'],
                          "MoveTime" => $thisResult['MoveTime']));
            }

            return $result;
        }
        else return null;
	}

}
