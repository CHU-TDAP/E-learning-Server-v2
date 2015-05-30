<?php
/**
 * DBUserSession.php
 *
 * 此檔案針對使用者登入階段資料表的功能。
 */

namespace UElearning\Database;

require_once UELEARNING_LIB_ROOT.'/Database/Database.php';
require_once UELEARNING_LIB_ROOT.'/Database/Exception.php';

/**
 * 使用者帳號資料表
 *
 * 對資料庫中的使用者資料表進行操作。
 *
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      Database
 */
class DBUserSession extends Database {

    /**
     * 新增登入資料
     * @param string $token 登入token
     * @param string $uId   帳號ID
     * @param string $agent 登入所使用的裝置
     */
    public function login($token, $uId, $agent) {

        //紀錄登入階段進資料庫
        $sqlString = "INSERT INTO ".$this->table('user_session').
         " (`UsID`, `UToken`, `UID`, `UAgent`, `ULoginDate`, `ULogoutDate`)
         VALUES (NULL , :token, :uid , :agent , NOW() , NULL)";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":token", $token);
        $query->bindParam(":uid", $uId);
        $query->bindParam(":agent", $agent);
        $query->execute();
    }

    /**
     * 標注此登入階段為登出
     * @param string $token 登入token
     */
    public function logout($token) {

        $sqlString = "UPDATE ".$this->table('user_session').
         " SET `UToken` = NULL, `ULogoutDate` = NOW()
         WHERE `UToken` = :token";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":token", $token);
        $query->execute();
    }

    /**
     * 標注此帳號所有的登入階段為登出
     * @param string $uid 帳號ID
     * @return int 修改幾筆資料
     */
    public function logoutByUserId($uid) {

        $sqlString = "UPDATE ".$this->table('user_session').
         " SET `UToken` = NULL, `ULogoutDate` = NOW()
         WHERE `UID` = :uid AND `UToken` IS NOT NULL";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(":uid", $uid);
        $query->execute();
        return $query->rowCount();
    }

    /**
     * 以token查詢
     * @param string $token 登入token
     * @return array 登入階段資料陣列，格式為:
     *     array(
     *         'session_id'  => <登入編號>,
     *         'token'       => <登入Token>,
     *         'user_id'     => <使用者>,
     *         'agent'       => <用哪個裝置登入>,
     *         'login_date'  => <登入時間>,
     *         'logout_date' => <登出時間>
     *     );
     */
    public function queryByToken($token) {
        $sqlString = "SELECT * FROM ".$this->table('user_session').
                     " WHERE `UToken` = :token";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':token', $token);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {
            $queryResult = $queryResultAll[0];

            $result = array(
                'session_id'  => $queryResult['UsID'],
                'token'       => $queryResult['UToken'],
                'user_id'     => $queryResult['UID'],
                'agent'       => $queryResult['UAgent'],
                'login_date'  => $queryResult['ULoginDate'],
                'logout_date' => $queryResult['ULogoutDate']
            );

            return $result;
        }
        else return null;
    }

    /**
     * 以使用者ID查詢
     * @param string $uId 使用者ID
     * @return array 登入階段資料陣列，格式為:
     *     array(
     *         array(
     *             'session_id'  => <登入編號>,
     *             'token'       => <登入Token>,
     *             'user_id'     => <使用者>,
     *             'agent'       => <用哪個裝置登入>,
     *             'login_date'  => <登入時間>,
     *             'logout_date' => <登出時間>
     *         )
     *     );
     */
    public function queryByUserId($uId) {
        $sqlString = "SELECT * FROM ".$this->table('user_session').
                     " WHERE `UID` = :uid";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':uid', $uId);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {

            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {
                array_push($result,
                    array(
                        'session_id'  => $thisResult['UsID'],
                        'token'       => $thisResult['UToken'],
                        'user_id'     => $thisResult['UID'],
                        'agent'       => $thisResult['UAgent'],
                        'login_date'  => $thisResult['ULoginDate'],
                        'logout_date' => $thisResult['ULogoutDate']
                    )
                );
            }
            return $result;
        }
        else return null;
    }

    /**
     * 以使用者ID查詢，目前所有已登入的登入階段
     * @param string $uId 使用者ID
     * @return array 登入階段資料陣列，格式為:
     *     array(
     *         array(
     *             'session_id'  => <登入編號>,
     *             'token'       => <登入Token>,
     *             'user_id'     => <使用者>,
     *             'agent'       => <用哪個裝置登入>,
     *             'login_date'  => <登入時間>,
     *             'logout_date' => <登出時間>
     *         )
     *     );
     */
    public function queryLoginByUserId($uId) {
        $sqlString = "SELECT * FROM ".$this->table('user_session').
                     " WHERE `UID` = :uid AND `UToken` IS NOT NULL";

        $query = $this->connDB->prepare($sqlString);
        $query->bindParam(':uid', $uId);
        $query->execute();

        $queryResultAll = $query->fetchAll();
        // 如果有查到一筆以上
        if( count($queryResultAll) >= 1 ) {

            // 製作回傳結果陣列
            $result = array();
            foreach($queryResultAll as $key => $thisResult) {
                array_push($result,
                    array(
                        'session_id'  => $thisResult['UsID'],
                        'token'       => $thisResult['UToken'],
                        'user_id'     => $thisResult['UID'],
                        'agent'       => $thisResult['UAgent'],
                        'login_date'  => $thisResult['ULoginDate'],
                        'logout_date' => $thisResult['ULogoutDate']
                    )
                );
            }
            return $result;
        }
        else return null;
    }

}
