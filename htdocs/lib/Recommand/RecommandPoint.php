<?php

namespace UElearning\Recommand;

require_once UELEARNING_ROOT.'/config.php';
require_once UELEARNING_LIB_ROOT.'/Target/Target.php';
require_once UELEARNING_LIB_ROOT.'/Database/DBRecommand.php';
require_once UELEARNING_LIB_ROOT.'/Study/Theme.php';
require_once UELEARNING_LIB_ROOT.'/Study/Study.php';
require_once UELEARNING_LIB_ROOT.'/Study/StudyActivity.php';
use UElearning\Target;
use UElearning\Study;
use UElearning\Database;

/**
 * 推薦學習點
 * Usage:
 * $recommand = new RecommandPoint();
 */

class RecommandPoint
{
    /**
     * 正規化參數
     *
     * @access private
     * @type double
     */
    private $gamma;

    /**
     * 調和參數(常數)
     *
     * @access private
     * @type double
     */
    const ALPHA=0.5;

    private $recommand;


    public function __construct()
    {
        $gamma = 0;
        $this->recommand = new Database\DBRecommand();
    }

    /**
     * 計算正規化參數
     * @return double 正規化參數
     */
    private function computeNormalizationParameter($theme_number)
    {
        $normal = 0;  //正規化之後的GAMMA值
        $EntitySum = 0;  //實體學習點分別算銓重之後的值
        $VirtualSum = 0;  //虛擬學習點分別算銓重之後的值

        $edge = $this->recommand->queryEdgeByID('0');
        $theme = new Study\Theme($theme_number);

        for($i=0;$i<count($edge);$i++)
        {
            $next_point = $edge[$i]["next_point"];
            $move_time = $edge[$i]["move_time"];
            $next_target = new Target\Target($next_point);
            $belong = $this->recommand->queryBelongByID($next_point,$theme->getId());
            $weight = $belong["weight"];

            $VirtualSum += $weight / $next_target->getLearnTime();

            if($next_target->isNumberOfPeopleZero()) $Rj = 0;
            else $Rj = $next_target->getMj() / $next_target->getPLj();

            $EntitySum += $weight * ($next_target->getS() - $Rj + 1) / ($move_time + $next_target->getLearnTime());
        }
        return $EntitySum/$VirtualSum;
    }

    /**
     * 推薦學習點
     * @return array 學習點清單
     */
    public function recommand($current_point,$theme_number,$activity_number)
    {

    }
}
