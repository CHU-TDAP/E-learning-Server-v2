<?php namespace UElearning\Database;
/**
 * @file
 * 整體資料庫操作
 *
 * 此檔案針對整體資料庫的功能，像是建立此資料庫、建立表格、清空...等等
 * 
 * @package         UElearning
 * @subpackage      Database
 */
require_once UELEARNING_LIB_ROOT.'Database/MySQLDB.php';
require_once UELEARNING_LIB_ROOT.'Database/Exceptions.php';

/**
 * 資料庫整體管理
 * 
 * 所有對於資料庫的操作（包含查詢、新增、修改、刪除），一律使用這個物件來操作。
 * 
 * 例如:
 * 
 *     use UElearning\Database;
 *     $db = new Database(array(
 *         'type' => 'mysql',
 *         'host' => 'localhost',
 *         'port' => '3306',
 *         'user' => 'user',
 *         'password' => '123456',
 *         'dbname' => 'chu-elearning',
 *         'prefix' => 'chu_'
 *         ));
 *
 * 一個資料表為一個類別，如果要對某資料表進行操作，請參閱
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         3.0
 * @package         UElearning
 * @subpackage      Database
 */
class Database {
    
    /**
     * 資料庫伺服器類型
     * 
     * 目前支援的:
     * * mysql 
     * 
     * @type string 
     */
    private $db_type;
    
    /**
     * 資料庫伺服器位址
     * @type string
     */
    private $db_host;
    
    /**
     * 資料庫伺服器連結埠
     * @type string
     */
    private $db_port;
    
    /**
     * 資料庫帳號
     * @type string
     */
    private $db_user;
    
    /**
     * 資料庫密碼
     * @type string
     */
    private $db_passwd;
    
    /**
     * 資料庫名稱
     * @type string
     */
    private $db_name;
    
    /**
     * 資料表前綴字元
     * @type string
     */
    private $db_prefix;
    
    // ------------------------------------------------------------------------
    
    /**
     * 資料庫連結物件
     * @type UElearning\Database\PDODB
     */
    private $connDB;
    
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
     * @author Yuan Chiu <chyuaner@gmail.com>
     * @since 3.0.0
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
            $this->connDB = new MySQLDB($this->db_name, $this->db_host, $this->db_port, $this->db_user, $this->db_passwd);
        }
        else {
            throw new DatabaseNoSupportException($this->db_type);
        }
    }
    
    /**
     * 轉為完整的資料表名稱（包含前綴字元）
     * 
     * @param string $tableName 資料表名稱
     * @return string 完整的資料表名稱
     * 
     * @author Yuan Chiu <chyuaner@gmail.com>
     * @since 3.0.0
     */ 
    public function table($tableName) {
       return $this->db_prefix.$tableName;
    }
    
    /**
     * 測試資料庫有無連接成功
     * 
     * @since 3.0.0
     */ 
    public function connectTest() {
        // TODO: Fill code in
        
    }
    
    /**
     * 建立資料庫
     * 
     * 在資料庫系統內建立一個資料庫。
     * （注意！需有建立資料庫的權限）
     * 
     */ 
    public function createDB() {
        // TODO: Fill code in
        
    }
    
    /**
     * 建立所有所需的資料表
     * 
     */ 
    public function createAllTable() {
        // TODO: Fill code in
        
    }
}