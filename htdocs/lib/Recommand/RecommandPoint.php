<?php

require_once __DIR__.'/../config.php';
require_once UELEARNING_LIB_ROOT.'/Target/Target.php';
use UElearning\Target;
use UElearning\Exception;

/**
 * 推薦學習點
 * Usage:
 *	$recommand = new RecommandPoint();
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
	private const $ALPHA = 0.5;
    
    private 
	
	
	public function __construct()
	{
		
	}
	
	/**
	 * 計算正規化參數
	 * @return double 正規化參數
	 */
	private function computeNormalizationParameter()
	{
        $
	}
	
	/**
	 * 推薦學習點
	 * @return array 學習點清單
	 */
	public function recommand($   )
	{
		
	}
}