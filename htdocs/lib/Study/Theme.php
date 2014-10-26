<?php
/**
 * Theme.php
 */

namespace UElearning\Study;

//require_once UELEARNING_LIB_ROOT.'/Database/DBTarget.php';
//require_once UELEARNING_LIB_ROOT.'/Target/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 主題專用類別
 * 
 * 一個物件即代表這一個主題
 * 
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class Theme {
    
    /**
     * 主題ID
     * @type int 
     */
	protected $tId;
    
    // ------------------------------------------------------------------------
    
	/**
	 * 查詢到所有資訊的結果
	 * 
	 * 由 $this->getQuery() 抓取資料表中所有資訊，並放在此陣列裡
	 * @type array
	 */
	protected $queryResultArray;
    
	/**
	 * 從資料庫取得查詢
	 *
     * @throw UElearning\Exception\AreaNoFoundException 
	 * @since 2.0.0
	 */
	protected function getQuery(){
        // TODO: 從資料庫取得查詢
        //// 從資料庫查詢
        //$db = new Database\DBTarget();
        //$areaInfo = $db->queryArea($this->aId);
//
        //// 判斷有沒有這個
        //if( $areaInfo != null ) {
        //    $this->queryResultArray = $areaInfo;
        //}
        //else throw new Exception\AreaNoFoundException($this->aId);
	}
    
    /**
	 * 從資料庫更新設定
	 *
	 * @since 2.0.0
	 */
	protected function setUpdate($field, $value){
        // TODO: 從資料庫更新設定
        // 將新設定寫進資料庫裡
		//$db = new Database\DBTarget();
        //$db->changeTargetData($this->tId, $field, $value);
        //$this->getQuery();
	}
    
    // ========================================================================
	
	/**
	 * 建構子
	 *
	 * @param int $inputTID 主題ID
     * @since 2.0.0
	 */
	public function __construct($inputTID){
		$this->tId = $inputAID;
		$this->getQuery();
	}
	
    // ========================================================================
	
	/**
	 * 取得主題ID
	 *
	 * @return int 主題ID
     * @since 2.0.0
	 */
	public function getId(){
		return $this->tId;
	}
    
	/**
	 * 取得主題名稱
	 *
	 * @return string 主題名稱
     * @since 2.0.0
	 */
	public function getName(){
		return $this->queryResultArray['name'];
	}
	
    /**
	 * 取得主題介紹
	 *
	 * @return string 主題介紹
     * @since 2.0.0
	 */
	public function getIntroduction(){
		//return $this->queryResultArray['name'];
	}
    
    /**
	 * 取得此主題學習所需時間
	 *
	 * @return int 所需學習時間(分)
     * @since 2.0.0
	 */
	public function getLearnTime(){
		//return $this->queryResultArray['name'];
	}
    
    /**
	 * 取得建立時間
	 *
	 * @return string 建立時間
     * @since 2.0.0
	 */
	public function getCreateTime(){
		return $this->queryResultArray['build_time'];
	}
    
    /**
	 * 取得修改時間
	 *
	 * @return string 修改時間
     * @since 2.0.0
	 */
	public function getModifyTime(){
		return $this->queryResultArray['modify_time'];
	}
    
}