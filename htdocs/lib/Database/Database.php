<?php 
/**
 * 整體資料庫操作
 *
 * 此檔案針對整體資料庫的功能，像是建立此資料庫、建立表格、清空...等等
 * 
 */

namespace UElearning\Database;

require_once UELEARNING_LIB_ROOT.'/Database/MySQLDB.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exceptions.php';
use UElearning\Database\Exception;

/**
 * 資料庫操作抽象類別
 * 
 * 請根據一個資料表創建一個類別，並繼承此類別。
 * 所有對於資料表的操作（包含查詢、新增、修改、刪除），一律使用新創已繼承的類別物件。
 * 
 * 基本的操作方式例如:
 * 
 *     use UElearning\Database;
 *     $db = new Database\[資料表類別](array(
 *         'type' => 'mysql',
 *         'host' => 'localhost',
 *         'port' => '3306',
 *         'user' => 'user',
 *         'password' => '123456',
 *         'dbname' => 'chu-elearning',
 *         'prefix' => 'chu_'
 *         ));
 *
 * 實際範例可參考 `DBAdmin` 類別的說明文件
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
abstract class Database {
    
    /**
     * 資料庫伺服器類型
     * 
     * 目前支援的:
     * * mysql 
     * 
     * @type string 
     */
    protected $db_type;
    
    /**
     * 資料庫伺服器位址
     * @type string
     */
    protected $db_host;
    
    /**
     * 資料庫伺服器連結埠
     * @type string
     */
    protected $db_port;
    
    /**
     * 資料庫帳號
     * @type string
     */
    protected $db_user;
    
    /**
     * 資料庫密碼
     * @type string
     */
    protected $db_passwd;
    
    /**
     * 資料庫名稱
     * @type string
     */
    protected $db_name;
    
    /**
     * 資料表前綴字元
     * @type string
     */
    protected $db_prefix;
    
    // ------------------------------------------------------------------------
    
    /**
     * 資料庫連結物件
     * @type UElearning\Database\PDODB
     */
    protected $connDB;
    
    // ========================================================================
    
    /**
     * 連接資料庫
     * 
     * @param array $conf (optional) 資料庫相關參數，格式為:
     *     array( 'type' => 'mysql',
     *            'host' => 'localhost',
     *            'port' => '3306',
     *            'user' => 'user',
     *            'password' => '123456',
     *            'dbname' => 'chu-elearning',
     *            'prefix' => 'chu_' )
     * 若不填寫將會直接使用設定在`config.php`的常數
     * 
     * @throws UElearning\Database\Exception\DatabaseNoSupportException
     * @author Yuan Chiu <chyuaner@gmail.com>
     * @since 2.0.0
     */
    public function __construct($conf = null) {
        
        // 將資料庫設定資訊帶入
        if(isset($conf)) {
            $this->db_type   = $conf['type'];
            $this->db_host   = $conf['host'];
            $this->db_port   = $conf['port'];
            $this->db_user   = $conf['user'];
            $this->db_passwd = $conf['password'];
            $this->db_name   = $conf['dbname'];
            $this->db_prefix = $conf['prefix'];
        }
        else {
            $this->db_type   = DB_TYPE;
            $this->db_host   = DB_HOST;
            $this->db_port   = DB_PORT;
            $this->db_user   = DB_USER;
            $this->db_passwd = DB_PASS;
            $this->db_name   = DB_NAME;
            $this->db_prefix = DB_PREFIX;
        }
        
        // 檢查是否有支援所設定的DBMS
        if($this->db_type == 'mysql') {
            $this->connDB = new MySQLDB($this->db_name
                                        , $this->db_host
                                        , $this->db_port
                                        , $this->db_user
                                        , $this->db_passwd);
        }
        else {
            throw new Exception\DatabaseNoSupportException($this->db_type);
        }
    }
    
    /**
     * 轉為完整的資料表名稱（包含前綴字元）
     * 
     * @param string $tableName 資料表名稱
     * @return string 完整的資料表名稱
     * 
     * @author Yuan Chiu <chyuaner@gmail.com>
     * @since 2.0.0
     */ 
    public function table($tableName) {
       return $this->db_prefix.$tableName;
    }
    
    /**
     * 測試資料庫有無連接成功
     * 
     * @since 2.0.0
     */ 
    public function connectTest() {
        // TODO: Fill code in
        
    }
}