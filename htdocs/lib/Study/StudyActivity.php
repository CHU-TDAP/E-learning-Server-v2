<?php
/**
 * StudyActivity.php
 */

namespace UElearning\Study;

//require_once UELEARNING_LIB_ROOT.'/Database/DBTarget.php';
//require_once UELEARNING_LIB_ROOT.'/Target/Exception.php';
use UElearning\Database;
use UElearning\Exception;
use UElearning\User;

/**
 * 學習階段類別
 * 
 * 一個物件即代表這一個主題
 * 
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Target
 */
class StudyActivity {
    /**
     * 學習階段流水號ID
     * @type int 
     */
	protected $id;
    
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
    
    // ========================================================================
	
	/**
	 * 建構子
	 *
	 * @param int $inputID 學習階段流水號ID
     * @since 2.0.0
	 */
	public function __construct($inputID){
		$this->id = $inputAID;
		$this->getQuery();
	}
	
    // ========================================================================
    // 控制這次學習階段:
    
    /**
	 * 設定這次學習時間要延長多久
	 *
	 * @return int 延長時間(分)
     * @since 2.0.0
	 */
	public function setDelay(){
		//return $this->queryResultArray['name'];
	}
    
    /**
	 * 設定累加這次學習時間要延長多久
	 *
	 * @return int 延長時間(分)
     * @since 2.0.0
	 */
	public function addDelay(){
		//return $this->queryResultArray['name'];
	}
    
    /**
	 * 結束這次學習
	 *
     * @since 2.0.0
	 */
	public function finishActivity(){
		//return $this->queryResultArray['name'];
	}
    
    /**
	 * 撤銷這次學習
	 *
     * @since 2.0.0
	 */
	public function cancelActivity(){
		//return $this->queryResultArray['name'];
	}
    
    // ========================================================================
	// 取得資料: 
    
	/**
	 * 取得學習階段流水號ID
	 *
	 * @return int 學習階段流水號ID
     * @since 2.0.0
	 */
	public function getId(){
		return $this->id;
	}
    
    /**
	 * 取得這次是誰在學習物件
	 *
	 * @return \UElearning\User\User 使用者物件
     * @since 2.0.0
	 */
	public function getUser(){
        
        $userId = $this->queryResultArray['user_id'];;
		return new User\User($userId);
	}
    
    /**
	 * 取得這次是誰在學習
	 *
	 * @return string 使用者ID
     * @since 2.0.0
	 */
	public function getUserId(){
		return $this->queryResultArray['user_id'];
	}

    ///**
	// * 取得這次是學哪個主題物件
	// *
	// * @return int 主題物件
    // * @since 2.0.0
	// */
	//public function getTheme(){
    //    $tId = $this->queryResultArray['theme_id'];
	//	return new Target\User($userId);;
	//}
    
    /**
	 * 取得這次是學哪個主題
	 *
	 * @return int 主題ID
     * @since 2.0.0
	 */
	public function getThemeId(){
		return $this->queryResultArray['theme_id'];
	}
    
    /**
	 * 取得這次學習是什麼時候開始的
	 *
	 * @return string 開始學習時間
     * @since 2.0.0
	 */
	public function getStartTime(){
		//return $this->queryResultArray['build_time'];
	}
    
    /**
	 * 取得這次學習是什麼時候結束的
	 *
	 * @return string 結束學習時間
     * @since 2.0.0
	 */
	public function getEndTime(){
		//return $this->queryResultArray['build_time'];
	}
    
    /**
	 * 取得這次學習所需時間
	 *
	 * @return int 所需學習時間(分)
     * @since 2.0.0
	 */
	public function getLearnTime(){
		//return $this->queryResultArray['name'];
	}
    
    /**
	 * 取得這次學習時間要延長多久
	 *
	 * @return int 延長時間(分)
     * @since 2.0.0
	 */
	public function getDelay(){
		//return $this->queryResultArray['name'];
	}
    
    /**
	 * 取得這次學習的導引風格
	 *
	 * @return int 將推薦幾個學習點
     * @since 2.0.0
	 */
	public function getLearnStyle(){
		return $this->queryResultArray['learnStyle_mode'];
	}
    
    /**
	 * 在這次學習，是否拒絕使用者前往非推薦的學習點
	 *
	 * @return bool 是否拒絕前往非推薦的學習點
     * @since 2.0.0
	 */
	public function isForceLearnStyle(){
		return $this->queryResultArray['learnStyle_force'];
	}
    
    /**
	 * 取得這次學習的教材風格
	 *
	 * @return string 教材風格
     * @since 2.0.0
	 */
	public function getMaterialStyle(){
		return $this->queryResultArray['material_mode'];
	}
    
}