<?php
/**
 * UserSession.php
 */

namespace UElearning\User;

require_once UELEARNING_LIB_ROOT.'/User/User.php';
require_once UELEARNING_LIB_ROOT.'/User/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Util/Password.php';
require_once UELEARNING_LIB_ROOT.'/Database/DBUserSession.php';
use UElearning\Util;
use UElearning\Database;
use UElearning\Exception;

/**
 * 使用者登入階段管理
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class UserSession {

    /**
     * 使用者登入
     *
     * 範例:
     *
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/User/UserSession.php';
     *     use UElearning\User;
     *     use UElearning\Exception;
     *
     *     try {
     *         $session = new User\UserSession();
     *         $loginToken = $session->login('yuan', 'password', 'browser');
     *         echo 'Token: '.$loginToken;
     *     }
     *     catch (Exception\UserNoFoundException $e) {
     *         echo 'No Found user: '. $e->getUserId();
     *     }
     *     catch (Exception\UserPasswordErrException $e) {
     *         echo 'User Password wrong: '. $e->getUserId();
     *     }
     *     catch (Exception\UserNoActivatedException $e) {
     *         echo 'User No Activiated: '. $e->getUserId();
     *     }
     *
     * @param  string  $userId     帳號名稱
     * @param  string  $password   密碼
     * @param  string  $agent      用什麼裝置登入
     * @return string  登入session  token
     * @throw  UElearning\Exception\UserNoFoundException
     * @throw  UElearning\Exception\UserPasswordErrException
     * @throw  UElearning\Exception\UserNoActivatedException
     * @since  2.0.0
     */
    public function login($userId, $password, $agent) {

        $user = new User($userId);

        // 登入密碼錯誤的話
        if( !$user->isPasswordCorrect($password) ) {
            throw new Exception\UserPasswordErrException($userId);
        }
        // 此帳號已被停用
        else if( !$user->isEnable() ) {
            throw new Exception\UserNoActivatedException($userId);
        }
        // 沒問題，登入此帳號
        else {

            // 使用資料庫
            $db = new Database\DBUserSession();

            // 產生登入token
            $passUtil = new Util\Password();
            $token = null;
            // 防止產生出重複的token
            do {
                $token = $passUtil->generator(32);
            }
            while ($db->queryByToken($token));

            // 登入資訊寫入資料庫
            $db->login($token, $userId, $agent);

            return $token;
        }
    }

    // ========================================================================

    /**
     * 使用者登出
     *
     * 範例:
     *
     *     try {
     *         $session = new User\UserSession();
     *         $session->logout('YdcfzqUuuRAR]4h6u4^Ew-qa4A-kvD5C');
     *     }
     *     catch (Exception\LoginTokenNoFoundException $e) {
     *         echo 'No Login by token: '. $e->getToken();
     *     }
     *
     * @param string $token 登入階段token
     * @throw \UElearning\Exception\LoginTokenNoFoundException
     * @since 2.0.0
     */
    public function logout($token) {

        $db = new Database\DBUserSession();

        // 如果有找到此登入階段
        if( $db->queryByToken($token) ) {
            $db->logout($token);
        }
        // 沒有此登入階段
        else {
            throw new Exception\LoginTokenNoFoundException($token);
        }
    }

    /**
     * 將其他已登入的裝置登出
     * @param string $token 登入階段token
     * @return int 已登出數量
     * @throw \UElearning\Exception\LoginTokenNoFoundException
     * @since 2.0.0
     */
    public function logoutOtherSession($token) {

        // 先從token查詢到使用者是誰
        $user_id = $this->getUserId($token);

        $db = new Database\DBUserSession();
        // 如果有找到此登入階段
        if( $db->queryByToken($token) ) {
            // 查詢者個使用者的所有登入階段
            $allSession = $db->queryLoginByUserId($user_id);

            // 紀錄已登出數量
            $logoutTotal = 0;
            if(isset($allSession)) {
                // 將所有非此Token的裝置登出
                foreach($allSession as $key=>$thisSession) {
                    if($thisSession['token'] != $token) {
                        $this->logout($thisSession['token']);
                        $logoutTotal++;
                    }
                }
            };
            return $logoutTotal;
        }
        // 沒有此登入階段
        else {
            throw new Exception\LoginTokenNoFoundException($token);
        }
    }

    /**
     * 取得使用者物件
     *
     * 範例:
     *
     *     try {
     *         // 正常寫法
     *         $userSession = new User\UserSession();
     *         $user = $userSession->getUser(‘YZ8@(3fYb[!f!A^E4^6b4LuqxSXgZ2FJ’);
     *
     *         // 簡短寫法（PHP 5.4以上才支援）
     *         //$user = (new User\UserSession())->getUser('YZ8@(3fYb[!f!A^E4^6b4LuqxSXgZ2FJ');
     *
     *         // 撈帳號資料
     *         echo '暱稱: '.$user->getNickName(); // 取得暱稱
     *         echo '本名: '.$user->getRealName(); // 取得本名
     *     }
     *     catch (Exception\LoginTokenNoFoundException $e) {
     *         echo 'No Found Token: '. $e->getToken();
     *     }
     *
     * @param string $token 登入階段token
     * @return User 使用者物件
     * @throw \UElearning\Exception\LoginTokenNoFoundException
     * @since 2.0.0
     */
    public function getUser($token) {
        $userId = $this->getUserId($token);
        return new User($userId);
    }

    /**
     * 取得使用者ID
     * @param string $token 登入階段token
     * @return string 使用者ID
     * @throw \UElearning\Exception\LoginTokenNoFoundException
     * @since 2.0.0
     */
    public function getUserId($token) {
        $db = new Database\DBUserSession();
        $sessionArray = $db->queryByToken($token);
        if(isset($sessionArray)) return $sessionArray['user_id'];
        else throw new Exception\LoginTokenNoFoundException($token);
    }

    /**
     * 取得登入資訊
     * @param string $token 登入階段token
     * @return Array 此登入階段資訊
     * @since 2.0.0
     */
    public function getTokenInfo($token) {
        $db = new Database\DBUserSession();
        $sessionArray = $db->queryByToken($token);
        if(isset($sessionArray)) return $sessionArray;
        else throw new Exception\LoginTokenNoFoundException($token);
    }

    // ========================================================================

    /**
     * 取得所有此使用者已登入的登入階段資訊
     * @param string $userId 使用者帳號名稱
     * @return Array 已登入的所有登入階段資訊
     * @since 2.0.0
     */
    public function getUserLoginInfo($userId) {
        // TODO: 取得所有此使用者已登入的登入階段資訊
    }

    /**
     * 取得此使用者登入的裝置數
     * @param string $userId 使用者帳號名稱
     * @return int 所有以登入的數量
     * @since 2.0.0
     */
    public function getCurrentLoginTotalByUserId($userId) {

        // 確保若無此使用者則丟例外
        $user = new User($userId);

        // 查詢者個使用者的所有登入階段
        $db = new Database\DBUserSession();
        $allSession = $db->queryLoginByUserId($userId);

        // 回傳目前已登入的裝置數
        if(isset($allSession)) {
            return count($allSession);
        }
        else return 0;
    }

    /**
     * 取得所有此使用者全部的登入階段資訊
     *
     * 用於查詢登入紀錄的時候使用
     * @param string $userId 使用者帳號名稱
     * @return Array 已登入的所有登入階段資訊
     * @since 2.0.0
     */
    public function getUserAllInfo($userId) {
        // TODO: 取得所有此使用者全部的登入階段資訊
    }

    /**
     * 將此使用者全部登入階段登出
     * @param string $userId 使用者帳號名稱
     * @return int 已登出數量
     * @throw  UElearning\User\Exception\UserNoFoundException
     * @since 2.0.0
     */
    public function logoutByUser($userId) {

        // 確保若無此使用者則丟例外
        $user = new User($userId);

        // 登出此使用者所有登入階段
        $db = new Database\DBUserSession();

        $logoutTotal = 0;
        $logoutTotal = $db->logoutByUserId($userId);
        return $logoutTotal;
    }
}
