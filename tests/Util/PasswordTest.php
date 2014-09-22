<?php
/**
 * PasswordTest.php
 *
 * @package         UElearning
 * @author          Yuan Chiu <chyuaner@gmail.com>
 */
namespace UElearning;

require_once UELEARNING_LIB_ROOT.'/Util/Password.php';
use UElearning\Util\Password;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    
    protected $passUtil;
    
    public function setUp(){
        // 建立密碼函式物件
        $this->passUtil = new Password();
    }
    
    /**
     * 檢查密碼與加密後是否一樣
     * 
     * @dataProvider pass_dataProvider
     */ 
    public function testCheckSame($data){
        
        // 加密字串
        $encode = $this->passUtil->encrypt($value);
        // 比對和加密後是否吻合
        $this->assertEquals($this->passUtil->checkSame($encode, $value), true);
    }
    
    
    /**
     * 測試時要填的資料
     */ 
    public function pass_dataProvider(){
        
        // 隨機產生測試數據
        $num = 10; // 產生幾筆測試字串
        $passUtil = new Password();
        $data_array = array();
        
        for($i=0; $i<$num; $i++) {
            $generator_text = $passUtil->generator(50);
            array_push($data_array, array( $generator_text ));
        }
        
        
        return $data_array;
        
        // 固定測試字串
        /*return array(
            array('123'),
            array('sa'),
            array('asdfmlsdm'),
            array('dsamvlkscml')
        );*/
    }
}