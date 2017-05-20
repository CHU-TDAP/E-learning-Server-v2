<?php

    /**
     * 除錯模式-管理
     *
     * 警告: 若網站要正式上線，請務必設定為false，否則會有嚴重的安全問題
     */
    define('DEBUG_ADMIN', false);
// 資料庫 =====================================================================
    /**
     * 資料庫類型
     */
    define('DB_TYPE', 'mysql'); // or mysql

    /**
     * 資料庫位址
     */
    define('DB_HOST', 'localhost');

    /**
     * 資料庫連結埠
     *
     * usually 5432 for PostgreSQL, 3306 for MySQL
     */
    define('DB_PORT', '');

    /**
     * 資料庫連結帳號
     */
    define('DB_USER', 'user');

    /**
     * 資料庫連結密碼
     */
    define('DB_PASS', 'passwd123');

    /**
     * 資料庫名稱
     */
    define('DB_NAME', 'UElearning');

    /**
     * 資料庫內資料表前綴字串
     *
     * 每一張表格名稱的起始字串，為了避開一些網頁空間只允許建立一個資料庫的限制。
     */
    define('DB_PREFIX', 'uel__');

// 學習導引設定 =================================================================

    /**
     * 預設導引模式
     *
     * 針對未個別設定過的使用者，採用預設的導引模式
     */
    define('LMODE', '1');

    /**
     * 預設教材模式
     *
     * 針對未個別設定過的使用者，採用預設的導引模式
     */
    define('MMODE', 'normal');

// 網站設定 ===================================================================
    /**
     * 網站標題
     */
    define('SITE_NAME', '無所不在學習導引系統');

    /**
     * 網站副標題
     */
    define('SITE_SUBNAME', '');

    /**
     * 網站標題簡稱
     */
    define('SITE_NAME_REFERRED', 'E-learning');

    /**
     * 網站首頁網址
     *
     * Warning: 網址後面務必加上"/"
     */
    define('SITE_URL', 'http://localhost/');

    /**
     * 本系統根網址
     *
     * 給絕對路徑用的。
     * Warning: 網址後面務必加上"/"
     */
    define('SITE_URL_ROOT', 'http://localhost/');

    /**
     * 要用哪種加密方式
     *
     * 目前提供選項:
     * <ul>
     *   <li>MD5</li>
     *   <li>SHA1</li>
     *   <li>CRYPT</li>
     * </ul>
     */
    define('ENCRYPT_MODE', 'SHA1');

    /**
     * 你的地區
     */
    date_default_timezone_set('Asia/Taipei');    //設定時區

// 路徑設定 ===================================================================
    /**
     * 網站根目錄
     */
    define('UELEARNING_ROOT', __DIR__);

    /**
     * 內部函式庫根目錄
     */
    define('UELEARNING_LIB_ROOT', __DIR__.'/lib');

    /**
     * 這份設定檔的路徑
     */
    define('UELEARNING_CONFIG_PATH', __FILE__);

// 連接HBase伺服器 =============================================================

    /**
     * 是否啟用介接學習歷程紀錄伺服器
     */
    define('UELEARNING_UEHBASE_ENABLE', false);

    /**
     * 介接學習歷程紀錄伺服器紀錄模組網址
     */
    define('UELEARNING_UEHBASE_URL', 'http://localhost/');
