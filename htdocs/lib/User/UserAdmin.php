<?php
/**
 * UserAdmin.php
 */

namespace UElearning\User;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/User/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
require_once UELEARNING_LIB_ROOT.'/Util/Password.php';
use UElearning\Exception;
use UElearning\Database;
use UElearning\Util;

/**
 * 管理使用者的操作
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class UserAdmin {

    /**
     * 建立使用者
     *
     * 建立使用者範例:
     *
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/User/UserAdmin.php';
     *     use UElearning\User;
     *     use UElearning\Exception;
     *
     *     try {
     *         $userAdmin = new User\UserAdmin();
     *         $userAdmin->create(
     *             array( 'user_id' => 'eric',
     *                    'password' => 'pass123',
     *                    'group_id' => 'admin',
     *                    'enable' => true,
     *                    'nickname' => '艾瑞克',
     *                    'email' => 'eric@example.com' )
     *         );
     *
     *     }
     *     // 若已有重複帳號名稱
     *     catch (Exception\UserIdExistException $e) {
     *          echo 'Is exist user: ',  $e->getUserId();
     *     }
     *
     * @param array $userInfoArray 使用者資訊陣列，格式為:
     *     array( 'user_id'            => 'root',
     *            'password'           => 'pass123',
     *            'group_id'           => 'user',
     *            'class_id'           => '5-2',             // (optional)
     *            'enable'             => true,              // (optional) 預設為true
     *            'learnStyle_mode'    => 3,                 // (optional)
     *            'material_mode'      => 'normal',          // (optional)
     *            'nickname'           => '元兒～',           // (optional)
     *            'enable_noAppoint'   => true,              // (optional)
     *            'realname'           => 'Eric Chiu',       // (optional)
     *            'email'              => 'eric@example.tw', // (optional)
     *            'memo'               => '' )               // (optional)
     * @throw UElearning\Exception\UserIdExistException
     * @since 2.0.0
     */
    public function create($userInfoArray) {

        // 檢查必填項目有無填寫
        if(isset($userInfoArray)) {

            // 若無填寫
            if( !isset($userInfoArray['user_id'])  ||
                !isset($userInfoArray['password']) ||
                !isset($userInfoArray['group_id']) ) {
                throw new Exception\NoDataException();
            }
            // 若此id已存在
            else if($this->isExist($userInfoArray['user_id'])) {
                throw new Exception\UserIdExistException(
                    $userInfoArray['user_id'] );
            }
            // 沒有問題
            else {

                // 處理未帶入的資料
                if( !isset($userInfoArray['class_id']) ){
                    $userInfoArray['class_id'] = null;
                }
                if( !isset($userInfoArray['enable']) ){
                    $userInfoArray['enable'] = true;
                }
                if( !isset($userInfoArray['learnStyle_mode']) ){
                    $userInfoArray['learnStyle_mode'] = null;
                }
                if( !isset($userInfoArray['material_mode']) ){
                    $userInfoArray['material_mode'] = null;
                }
                if( !isset($userInfoArray['enable_noAppoint']) ){
                    $userInfoArray['enable_noAppoint'] = true;
                }
                if( !isset($userInfoArray['nickname']) ){
                    $userInfoArray['nickname'] = null;
                }
                if( !isset($userInfoArray['realname']) ){
                    $userInfoArray['realname'] = null;
                }
                if( !isset($userInfoArray['email']) ){
                    $userInfoArray['email'] = null;
                }
                if( !isset($userInfoArray['memo']) ){
                    $userInfoArray['memo'] = null;
                }

                // 進行密碼加密
                $passUtil = new Util\Password();
                $passwdEncrypted = $passUtil->encrypt( $userInfoArray['password'] );

                // 新增一筆使用者資料進資料庫
                $db = new Database\DBUser();
                $db->insertUser(
                    array(
                        'user_id'            => $userInfoArray['user_id'],
                        'password'           => $passwdEncrypted,
                        'group_id'           => $userInfoArray['group_id'],
                        'class_id'           => $userInfoArray['class_id'],
                        'enable'             => $userInfoArray['enable'],
                        'learnStyle_mode'    => $userInfoArray['learnStyle_mode'],
                        'material_mode'      => $userInfoArray['material_mode'],
                        'enable_noAppoint'   => $userInfoArray['enable_noAppoint'],
                        'nickname'           => $userInfoArray['nickname'],
                        'realname'           => $userInfoArray['realname'],
                        'email'              => $userInfoArray['email'],
                        'memo'               => $userInfoArray['memo']
                    )
                );

            }
        }
        else throw Exception\NoDataException();
    }

    /**
     * 是否已有相同名稱的帳號名稱
     *
     * @param string $userName 帳號名稱
     * @return bool 已有相同的帳號名稱
     * @since 2.0.0
     */
    public function isExist($userName) {

        $db = new Database\DBUser();
        $info = $db->queryUser($userName);

        if( $info != null ) return true;
        else return false;
    }

    /**
     * 移除此使用者
     *
     * 範例:
     *
     *     try {
     *         $userAdmin = new User\UserAdmin();
     *         $userAdmin->remove('eric');
     *     }
     *     catch (Exception\UserNoFoundException $e) {
     *         echo 'No Found user: ',  $e->getUserId();
     *     }
     *
     * @param string $userName 帳號名稱
     * @throw UElearning\Exception\UserNoFoundException
     * @since 2.0.0
     */
    public function remove($userName) {

        // 若有此使用者
        if($this->isExist($userName)) {

            // TODO: 檢查所有關聯的資料，確認是否可以移除

            // 移除資料庫中的使用者
            $db = new Database\DBUser();
            $db->deleteUser($userName);
        }
        // 若沒有這位使用者
        else {
            throw new Exception\UserNoFoundException($userName);
        }
    }

}
