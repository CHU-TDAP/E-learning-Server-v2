<?php
/**
 * 此位使用者的相關操作
 */

namespace UElearning\Target;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 標的專用類別
 * 
 * 一個物件即代表一個標的
 * 
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class Target {
    
    /**
     * 標的ID
     * @type int 
     */
	protected $tId;
    
    // ------------------------------------------------------------------------
    
	/**
	 * 查詢到此標的的所有資訊的結果
	 * 
	 * 由 $this->getQuery() 抓取資料表中所有資訊，並放在此陣列裡
	 * @type array
	 */
	protected $queryResultArray;
    
	/**
	 * 從資料庫取得此標的查詢
	 *
     * @throw UElearning\Exception\UserNoFoundException 
	 * @since 2.0.0
	 */
	protected function getQuery(){
        // TODO: getQuery
//        // 從資料庫查詢使用者
//        $db       = new Database\DBUser();
//        $userInfo = $db->queryUser($this->uId);
//
//        // 判斷有沒有這位使用者
//        if( $userInfo != null ) {
//            $this->queryResultArray = $userInfo;
//        }
//        else throw new Exception\UserNoFoundException($this->uId);
	}
    
    /**
	 * 從資料庫更新此標的設定
	 *
	 * @since 2.0.0
	 */
	protected function setUpdate($field, $value){
        // TODO: setUpdate
//        /// 將新設定寫進資料庫裡
//		$db = new Database\DBUser();
//        $db->changeUserData($this->uId, $field, $value);
//        $this->getQuery();
	}
    
    // ========================================================================
	
	/**
	 * 建構子
	 *
	 * @param int $inputTID 標的ID
     * @since 2.0.0
	 */
	public function __construct($inputTID){
		$this->tId = $inputTID;
		$this->getQuery();
	}
	
    // ========================================================================
	
	/**
	 * 取得標的ID
	 *
	 * @return int 標的ID
     * @since 2.0.0
	 */
	public function getId(){
		return $this->tId;
	}
    
    
    /**
	 * 取得標的所在的區域ID
	 *
	 * @return int 標的所在的區域ID
     * @since 2.0.0
	 */
	public function getAreaId(){
		return $this->queryResultArray['area_id'];
	}
    
    /**
	 * 取得標的所在的廳ID
	 *
	 * @return int 標的所在的廳ID
     * @since 2.0.0
	 */
	public function getHallId(){
		return $this->queryResultArray['hall_id'];
	}
    
    /**
	 * 取得標的地圖上的編號
	 *
	 * @return int 地圖上的標的編號
     * @since 2.0.0
	 */
	public function getNumber(){
		return $this->queryResultArray['number'];
	}
    // ========================================================================
	
	/**
	 * 取得標的名稱
	 *
	 * @return string 標的名稱
     * @since 2.0.0
	 */
	public function getName(){
		return $this->queryResultArray['name'];
	}
	
	///**
	// * 設定標的名稱
	// *
	// * @param string $name 標的名稱
    // * @since 2.0.0
	// */
	//public function setName($name){
	//	$this->setUpdate('name', $name);
	//}
    
    // ========================================================================
    
    /**
	 * 取得標的的地圖圖片檔路徑
	 *
	 * @return string 地圖圖片檔路徑
     * @since 2.0.0
	 */
	public function getMapUrl(){
		return $this->queryResultArray['map_url'];
	}
    
    /**
	 * 取得預估的學習時間
	 *
	 * @return int 預估的學習時間(分)
     * @since 2.0.0
	 */
	public function getLearnTime(){
		return $this->queryResultArray['learn_time'];
	}
    
    /**
	 * 取得學習標的的人數限制
	 *
	 * @return int 學習標的的人數限制
     * @since 2.0.0
	 */
	public function getPLj(){
		return $this->queryResultArray['PLj'];
	}
    
    /**
	 * 取得學習標的的人數限制
	 *
	 * @return int 學習標的的人數限制
     * @since 2.0.0
	 */
	public function getMaxPeople(){
		return $this->getPLj();
	}
    
    // ------------------------------------------------------------------------
    
    /**
	 * 取得學習標的目前人數
	 *
	 * @return int 學習標的目前人數
     * @since 2.0.0
	 */
	public function getMj(){
		return $this->queryResultArray['Mj'];
	}
    
    /**
	 * 取得學習標的目前人數
	 *
	 * @return int 學習標的目前人數
     * @since 2.0.0
	 */
	public function getCurrentPeople(){
		return $this->getMj();
	}
    
    /**
	 * 設定學習標的目前人數
	 * 
	 * @param int $number 學習標的目前人數
     * @since 2.0.0
	 */
	function setMj($number){
		//return $this->queryResultArray['Mj'];
	}
    
    /**
	 * 設定學習標的目前人數
	 *
	 * @param int $number 學習標的目前人數
     * @since 2.0.0
	 */
	public function setCurrentPeople($number){
		//return $this->getMj();
	}
    
    // TODO: 加人數、減人數
    
    /**
	 * 目前此標的人數是否已滿
	 *
	 * @return bool 目前人數已滿
     * @since 2.0.0
	 */
	public function isFullPeople(){
		// TODO: isFull
	}
    
    // ------------------------------------------------------------------------
    
    /**
	 * 取得學習標的飽和率上限
	 *
	 * @return int 學習標的飽和率上限
     * @since 2.0.0
	 */
	public function getS(){
		return $this->queryResultArray['S'];
	}
    
    /**
	 * 取得學習標的滿額指標
	 *
	 * @return int 學習標的滿額指標
     * @since 2.0.0
	 */
	public function getFi(){
		return $this->queryResultArray['Fi'];
	}
    
}