<?php
/**
 * ClassGroupAdmin.php
 */

namespace UElearning\User;

require_once UELEARNING_LIB_ROOT.'/Database/DBUser.php';
require_once UELEARNING_LIB_ROOT.'/User/ClassGroup.php';
require_once UELEARNING_LIB_ROOT.'/Exception.php';
use UElearning\Database;
use UElearning\Exception;

/**
 * 管理班級群組的操作
 *
 * @author          Yuan Chiu <chyuaner@gmail.com>
 * @version         2.0.0
 * @package         UElearning
 * @subpackage      User
 */
class ClassGroupAdmin {

    /**
     * 建立班級
     *
     * 建立班級群組範例:
     *
     *     require_once __DIR__.'/../config.php';
     *     require_once UELEARNING_LIB_ROOT.'/User/ClassGroupAdmin.php';
     *     use UElearning\User;
     *
     *     try {
     *         $groupAdmin = new User\ClassGroupAdmin();
     *         $newId = null;
     *         $newId = $groupAdmin->create(
     *             array( 'name' => '學生',
     *                    'memo' => null
     *         ));
     *         echo '你剛建立:'.$newId;
     *     }
     *     // 若已有重複班級ID
     *     catch (Exception\ClassIdExistException $e) {
     *         echo 'Is exist class: ',  $e->getGroupId();
     *     }
     *
     * @param array $classGroupArray 班級群組資訊陣列，格式為:
     *     array( 'class_id'              => 12,
     *            'name'                  => '學生',
     *            'memo'                  => null )
     * @return int 剛剛新增進去的ID
     * @throw UElearning\Exception\ClassIdExistException
     * @since 2.0.0
     */
    public function create($classGroupArray) {

        // 檢查有無填寫
        if(isset($classGroupArray)) {

            // 若此id已存在
            if( isset($classGroupArray['class_id']) &&
                      $this->isExist($classGroupArray['class_id']) ) {

                throw new Exception\ClassIdExistException(
                    $classGroupArray['class_id'] );
            }
            // 沒有問題
            else {
                // 處理未帶入的資料
                if( !isset($classGroupArray['class_id']) ){
                    $classGroupArray['class_id'] = null;
                }

                // 處理未帶入的資料
                if( !isset($classGroupArray['name']) ){
                    $classGroupArray['name'] = null;
                }
                // 處理未帶入的資料
                if( !isset($classGroupArray['memo']) ){
                    $classGroupArray['memo'] = null;
                }

                // 新增一筆使用者資料進資料庫
                $db = new Database\DBUser();
                $id = $db->insertClassGroup(
                    array( 'class_id' => $classGroupArray['class_id'],
                           'name'     => $classGroupArray['name'],
                           'memo'     => $classGroupArray['memo']
                    )
                );

                // 回傳剛剛新增的ID
                return $id;
            }
        }
        else throw Exception\NoDataException();
    }

    /**
     * 是否已有相同名稱的班級ID
     *
     * @param  int  $class_id 班級ID
     * @return bool 已有相同的班級ID
     * @since 2.0.0
     */
    public function isExist($class_id) {

        $db = new Database\DBUser();
        $info = $db->queryClassGroup($class_id);

        if( $info != null ) return true;
        else return false;
    }

    /**
     * 移除此班級
     *
     * 範例:
     *
     *     try {
     *         $groupAdmin = new User\ClassGroupAdmin();
     *         $groupAdmin->remove(2);
     *
     *     }
     *     catch (Exception\ClassNoFoundException $e) {
     *         echo 'No Found class: ',  $e->getGroupId();
     *     }
     *
     * @param int $class_id 班級ID
     * @throw UElearning\Exception\ClassNoFoundException
     * @since 2.0.0
     */
    public function remove($class_id) {

        // 若有此使用者
        if($this->isExist($class_id)) {

            // TODO: 檢查所有關聯的資料，確認是否可以移除

            // 移除資料庫中的使用者
            $db = new Database\DBUser();
            $db->deleteClassGroup($class_id);
        }
        // 若沒有這位使用者
        else {
            throw new Exception\ClassNoFoundException($class_id);
        }

    }

    /**
     * 取得所有的班級ID清單
     *
     * @return array 班級ID清單
     * @since 2.0.0
     */
    public function getIDList() {

        $db = new Database\DBUser();
        $queryResult = $db->queryAllClassGroup();

        if(isset($queryResult)) {

            $output = array();
            foreach($queryResult as $key => $value) {
                array_push($output, $value['class_id']);
            }

            return $output;
        }
        else {

            return null;
        }
    }

    /**
     * 取得所有的班級資訊清單
     *
     * @return array 班級資訊清單陣列，格式為:
     *
     *     array(
     *         array(
     *             'class_id'         => <班級ID>,
     *             'name'             => <班級顯示名稱>,
     *             'memo'             => <備註>,
     *             'build_time'       => <建立時間>,
     *             'modify_time'      => <修改時間>
     *         )
     *     );
     *
     * @since 2.0.0
     */
    public function getInfoList() {

        $db = new Database\DBUser();
        $queryResult = $db->queryAllClassGroup();
        return $queryResult;
    }

    /**
     * 取得所有的班級清單
     *
     * @return array 班級物件
     * @since 2.0.0
     */
    public function getObjectList() {

        $db = new Database\DBUser();
        $queryResult = $db->queryAllClassGroup();

        if(isset($queryResult)) {

            $output = array();
            foreach($queryResult as $key => $value) {
                $group = new ClassGroup($value['class_id']);
                array_push($output, $group);
            }

            return $output;
        }
        else {

            return null;
        }
    }

}
