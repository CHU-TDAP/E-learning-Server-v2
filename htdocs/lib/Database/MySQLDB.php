<?php
/**
 * MySQLDB.php
 *
 * 有用到的Define:
 * DB_NAME, DB_HOST, DB_USER, DB_PASS
 */

namespace UElearning\Database;

use \PDO;

/**
 * 資料庫連接專用類別，採用自PDO
 *
 * @extends PDO
 * @author Yuan Chiu <chyuaner@gmail.com>
 * @version 2.0.0
 * @see https://github.com/shuliu/myPDO
 * @package         UElearning
 * @subpackage      Database
 */
class MySQLDB extends PDO {

    /**
     * 連結資料庫
     *
     * @param string $dbname 資料庫名稱
     * @param string $host 資料庫伺服器位址
     * @param int $port 資料庫伺服器連接埠
     * @param string $user 資料庫伺服器帳號
     * @param string $passwd 資料庫伺服器密碼
     * @author Yuan Chiu <me@yuaner.tw>
     * @since 2.0.0
     */
    public function __construct($dbname, $host, $port, $user, $passwd){
        parent::__construct('mysql:dbname='.$dbname
                            .';host='.$host.';port='.$port
                            .';charset=utf8', DB_USER, DB_PASS);

        //配合PHP< 5.3.6 PDO沒有charset用的
        //參考: http://gdfan1114.wordpress.com/2013/06/24/php-5-3-6-%E7%89%88-pdo-%E9%85%8D%E5%90%88%E5%AD%98%E5%8F%96%E8%B3%87%E6%96%99%E5%BA%AB%E6%99%82%E7%9A%84%E4%B8%AD%E6%96%87%E5%95%8F%E9%A1%8C/
        $this->exec('set names utf8');
    }

    // ========================================================================
    /**
     * 錯誤訊息的陣列
     *
     * 改寫Adodb -> ErrorMsg
     *
     * @access public
     * @return array 錯誤訊息
     *
     * @since 2.0.0
     * @author shuliu <https://github.com/shuliu>
     * @see https://github.com/shuliu/myPDO/blob/master/PDO.class.php
     */
    public function errorMsg(){
        $err = parent ::errorinfo();
        if( $err[0]!='00000' ){
            return array('errorCode'=>$err[0]
                         ,'number'=>$err[1]
                         ,'message'=>$err[2]);
        }else{
            return null;
        }
    }

 }
