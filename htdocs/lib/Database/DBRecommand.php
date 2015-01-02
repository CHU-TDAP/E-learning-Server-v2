<?php

namespace UElearning\Database;

require_once UELEARNING_ROOT.'/config.php';
require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';
use UElearning\Exception;

/**
 * 查推薦學習點時所需要的表格資料
 * Usage:
 *
 */

class DBRecommand extends Database
{

    /**
     * 內部查詢用
     * @param string $where SQL WHERE子句
     * @return array 查詢結果
     */
	protected function queryEdgeByWhere($where)
	{
		$sqlString = "SELECT DISTINCT ".$this->table('Edge').".Ti, ".$this->table('Edge').".Tj, ".$this->table('Edge').".MoveTime".
                     " FROM ".$this->table('Edge')." WHERE ".$where;
        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryAllResult = $query->fetchAll();

        if(count($queryAllResult) != 0)
        {
            $result  = array();
            foreach ($queryAllResult as $key => $thisResult)
            {
                array_push($result,
                    array("current_point" => $thisResult['Ti'],
                          "next_point" => $thisResult['Tj'],
                          "move_time" => $thisResult['MoveTime']));
            }

            return $result;
        }
        else return null;
	}

    protected function queryBelongByWhere($where)
    {
        $sqlString = "SELECT ".$this->table('tbelong').".Weights FROM ".$this->table('tbelong')." WHERE ".$where;
        $query = $this->connDB->prepare($sqlString);
        $query->execute();

        $queryResult = $query->fetchAll();

        if(count($queryResult) != 0)
        {
            $result = array();
            foreach ($queryResult as $key => $thisResult)
            {
                array_push($result, array("weight" => $thisResult['Weights']));
            }
            return $result;
        }
        else return null;
    }

    public function queryBelongByID($next_point,$theme_number)
    {
        $whereClause = $this->table('tbelong').".thID = ".$this->connDB->quote($theme_number)." AND ".$this->table('tbelong').".TID = ".$this->connDB->quote($next_point);
        $AllOfResult = $this->queryBelongByWhere($whereClause);

        if(count($AllOfResult) != 0) return $AllOfResult;
        else return null;
    }


    public function queryEdgeByID($currentPoint)
    {
        $AllOfResult = $this->queryBelongByWhere("Ti = ".$this->connDB->quote($currentPoint));
        if(count($AllOfResult) != 0) return $AllOfResult;
        else return null;
    }

}
