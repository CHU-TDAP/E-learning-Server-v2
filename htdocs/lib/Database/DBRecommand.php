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
        $sqlString = "SELECT DISTINCT ".$this->table('learn_path').".Ti, ".$this->table('learn_path').".Tj, ".$this->table('learn_path').".MoveTime".
                     " FROM ".$this->table('learn_path')." WHERE ".$where;
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

    /**
     * 內部查詢用
     * @param string $where SQL WHERE子句
     * @return array 查詢結果
     */
    protected function queryBelongByWhere($where)
    {
        $sqlString = "SELECT ".$this->table('learn_topic_belong').".Weights FROM ".$this->table('learn_topic_belong')." WHERE ".$where;
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

    /**
     * 以下一個學習點和主題編號查詢屬於的權重資料
     * @param string $next_point 下一個學習點編號
     * @return array 查詢結果
     */
    public function queryBelongByID($next_point,$theme_number)
    {
        $whereClause = $this->table('learn_topic_belong').".ThID = ".$this->connDB->quote($theme_number)." AND ".$this->table('learn_topic_belong').".TID = ".$this->connDB->quote($next_point);
        $AllOfResult = $this->queryBelongByWhere($whereClause);

        if(count($AllOfResult) != 0) return $AllOfResult[0];
        else return null;
    }

    /**
     * 以目前的學習點編號查詢下一個學習點的資訊
     * @param string $currentPoint 目前的學習點編號
     * @return array
     */
    public function queryEdgeByID($currentPoint)
    {
        //echo "EEEEEEEEE";
        $AllOfResult = $this->queryEdgeByWhere($this->table('learn_path').".Ti = ".$this->connDB->quote($currentPoint));
        if(count($AllOfResult) != 0) return $AllOfResult;
        else return null;
    }

}
