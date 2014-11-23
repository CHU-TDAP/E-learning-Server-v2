<?php
require_once __DIR__.'/../../config.php';
require UELEARNING_ROOT.'/vendor/autoload.php';
require_once __DIR__.'/src/ApiTemplates.php';
require_once UELEARNING_LIB_ROOT.'/User/User.php';
require_once UELEARNING_LIB_ROOT.'/User/UserSession.php';
require_once UELEARNING_LIB_ROOT.'/User/UserAdmin.php';
use UElearning\User;
use UElearning\Exception;

$app = new \Slim\Slim(array(
    'templates.path' => './', // 設定Path
    'debug' => true
));
$app_template = new ApiTemplates($app);

// 設定成將使用JSON格式輸出
function APIrequest() {
    $app = \Slim\Slim::getInstance();
    $app->view(new \JsonApiView());
    $app->add(new \JsonApiMiddleware());
}


/*
 * 測試用 Say hello!~~~
 * GET http://localhost/api/v2/hello/{string}
 */
$app->get('/hello/:name', 'APIrequest', function ($name) use ($app) {
    $app->render(200,array(
        'error'   => false,
        'msg' => 'Hello, '.$name
    ));
});

// ============================================================================

$app->group('/users', 'APIrequest', function () use ($app, $app_template) {

    /*
     * 建立帳號
     * POST http://localhost/api/v2/users
     */
    $app->post('/', function () use ($app) {
        // 取得帶來的參數
        $cType = $app->request->getContentType();
        if($cType == 'application/x-www-form-urlencoded') {
            $user_id          = $_POST['user_id'];
            $password         = $_POST['password'];
            $group_id         = $_POST['group_id'];
            $class_id         = isset($_POST['class_id'])
                                    ? $_POST['class_id'] : null;
            $enable           = isset($_POST['enable'])
                                    ? $_POST['enable'] : null;
            $learnStyle_mode  = isset($_POST['learnStyle_mode'])
                                    ? $_POST['learnStyle_mode'] : null;
            $material_mode    = isset($_POST['material_mode'])
                                    ? $_POST['material_mode'] : null;
            $enable_noAppoint = isset($_POST['enable_noAppoint'])
                                    ? $_POST['enable_noAppoint'] : null;
            $nickname         = isset($_POST['nickname'])
                                    ? $_POST['nickname'] : null;
            $realname         = isset($_POST['realname'])
                                    ? $_POST['realname'] : null;
            $email            = isset($_POST['email'])
                                    ? $_POST['email'] : null;
            $memo             = isset($_POST['memo'])
                                    ? $_POST['memo'] : null;;
        }
        else /*if($cType == 'application/json')*/ {
            $postData = $app->request->getBody();
            $postDataArray = json_decode($postData);
            $user_id          = $postDataArray->user_id;
            $password         = $postDataArray->password;
            $group_id         = $postDataArray->group_id;
            $class_id         = isset($postDataArray->class_id)
                                    ? $postDataArray->class_id : null;
            $enable           = isset($postDataArray->enable)
                                    ? $postDataArray->enable : null;
            $learnStyle_mode  = isset($postDataArray->learnStyle_mode)
                                    ? $postDataArray->learnStyle_mode : null;
            $material_mode    = isset($postDataArray->material_mode)
                                    ? $postDataArray->material_mode : null;
            $enable_noAppoint = isset($postDataArray->enable_noAppoint)
                                    ? $postDataArray->enable_noAppoint : null;
            $nickname         = isset($postDataArray->nickname)
                                    ? $postDataArray->nickname : null;
            $realname         = isset($postDataArray->realname)
                                    ? $postDataArray->realname : null;
            $email            = isset($postDataArray->email)
                                    ? $postDataArray->email : null;
            $memo             = isset($postDataArray->memo)
                                    ? $postDataArray->memo : null;
        }
        /*else {
            $app->render(400, array(
                    'Content-Type'=> $cType,
                    'error'       => true,
                    'msg'     => '',
                    'msg_cht' => '輸入參數的Content-Type不在支援範圍內 或是沒有輸入',
                    'substatus'   => 102
                )
            );
        }*/

        // 建立使用者帳號
        try {
            $userAdmin = new User\UserAdmin();
            $userAdmin->create(
                array( 'user_id'            => $user_id,
                       'password'           => $password,
                       'group_id'           => $group_id,
                       'class_id'           => $class_id,
                       'enable'             => $enable,
                       'learnStyle_mode'    => $learnStyle_mode,
                       'material_mode'      => $material_mode,
                       'enable_noAppoint'   => $enable_noAppoint,
                       'nickname'           => $nickname,
                       'realname'           => $realname,
                       'email'              => $email,
                       'memo'               => $memo)
            );

            // 顯示建立成功
            $app->render(200,array(
                'user_id'            => $user_id,
                'group_id'           => $group_id,
                'class_id'           => $class_id,
                'enable'             => $enable,
                'learnStyle_mode'    => $learnStyle_mode,
                'material_mode'      => $material_mode,
                'enable_noAppoint'   => $enable_noAppoint,
                'nickname'           => $nickname,
                'realname'           => $realname,
                'email'              => $email,
                'memo'               => $memo,
                'error'   => false,
                'msg'     => '\''.$user_id.'\' is created.',
                'msg_cht' => '\''.$user_id.'\'使用者已成功建立'
            ));

        }
        // 若已有重複帳號名稱
        catch (Exception\UserIdExistException $e) {
             $app->render(409,array(
                'user_id'     => $user_id,
                'error'       => true,
                'msg'     => '\''.$user_id.'\' is exist.',
                'msg_cht' => '\''.$user_id.'\'使用者名稱已被使用'
            ));
        }
    });

    /*
     * 取得帳號資訊
     * GET http://localhost/api/v2/users/{帳號ID}
     */
    $app->get('/:user_id', function ($user_id) use ($app) {

        try {
            $user = new User\User($user_id);

            $app->render(200,array(
                'user_id'     => $user_id,
                'nickname'    => $user->getNickName(),
                'class_name'  => $user->getClassName(),
                'error'       => false
            ));
        }
        catch (Exception\UserNoFoundException $e) {
            $app->render(404,array(
                'user_id'     => $user_id,
                'error'       => true,
                'msg'     => '\''.$user_id.'\' is not found',
                'msg_cht' => '找不到\''.$user_id.'\'使用者'
            ));
        }
    });

    /*
     * 登入帳號
     * POST http://localhost/api/v2/users/{帳號ID}/login
     */
    $app->post('/:user_id/login', function ($user_id) use ($app) {

        // 取得帶來的參數
        $cType = $app->request->getContentType();
        if($cType == 'application/x-www-form-urlencoded') {
            $password = $_POST['password'];
            $browser  = isset($_POST['browser']) ? $_POST['browser'] : 'api';
        }
        else /*if($cType == 'application/json')*/ {
            $postData = $app->request->getBody();
            $postDataArray = json_decode($postData);
            $password = $postDataArray->password;
            $browser  = isset($postDataArray->browser)
                            ? $postDataArray->browser : 'api';
        }
        /*else {
            $app->render(400, array(
                    'Content-Type'=> $cType,
                    'error'       => true,
                    'msg'     => '',
                    'msg_cht' => '輸入參數的Content-Type不在支援範圍內 或是沒有輸入',
                    'substatus'   => 102
                )
            );
        }*/

        // 進行登入
        try {
            $session = new User\UserSession();
            $loginToken = $session->login($user_id, $password, $browser);

            $app->render(201,array(
                'user_id'     => $user_id,
                'token'       => $loginToken,
                'browser'     => $browser,
                'error'       => false,
                'msg'     => '\''.$user_id.'\' is logined',
                'msg_cht' => '\''.$user_id.'\'使用者已登入'
            ));
        }
        catch (Exception\UserNoFoundException $e) {
            $app->render(404,array(
                'user_id'     => $user_id,
                'browser'     => $browser,
                'error'       => true,
                'msg'     => '\''.$user_id.'\' is not found',
                'msg_cht' => '找不到\''.$user_id.'\'使用者'
            ));
        }
        catch (Exception\UserPasswordErrException $e) {
            $app->render(401,array(
                'user_id'     => $user_id,
                'browser'     => $browser,
                'error'       => true,
                'msg'     => 'Input \''.$user_id.'\' password is wrong',
                'msg_cht' => '\''.$user_id.'\'使用者密碼錯誤',
                'substatus'   => 201
            ));
        }
        catch (Exception\UserNoActivatedException $e) {
            $app->render(401,array(
                'user_id'     => $user_id,
                'browser'     => $browser,
                'error'       => true,
                'msg'     => '\''.$user_id.'\' is not enable',
                'msg_cht' => '\''.$user_id.'\'帳號目前未啟用',
                'substatus'   => 202
            ));
        }
    });

});

$app->group('/tokens', 'APIrequest', function () use ($app, $app_template) {

    /*
     * 取得已登入的帳號資訊
     * GET http://localhost/api/v2/tokens/{登入Token}
     */
    $app->get('/:token', function ($token) use ($app) {

        try {
            // 正常寫法
            $userSession = new User\UserSession();
            $user = $userSession->getUser($token);

            $app->render(200,array(
                'token'              => $token,
                'user_id'            => $user->getId(),
                'nickname'           => $user->getNickName(),
                'group_id'           => $user->getGroupID(),
                'group_name'         => $user->getGroupName(),
                'class_id'           => $user->getClassId(),
                'class_name'         => $user->getClassName(),
                'enable'             => $user->isEnable(),
                'build_time'         => $user->getCreateTime(),
                'modify_time'        => $user->getModifyTime(),
                'learnStyle_mode'    => $user->getLearnStyle(),
                'material_mode'      => $user->getMaterialStyle(),
                'enable_noAppoint'   => $user->isEnableNoAppoint(),
                'realname'           => $user->getRealName(),
                'email'              => $user->getEmail(),
                'memo'               => $user->getMemo(),
                'error'              => false
            ));
        }
        catch (Exception\LoginTokenNoFoundException $e) {
            $app->render(404,array(
                'token'   => $token,
                'error'   => true,
                'msg'     => 'No \''.$token.'\' session. Please login again.',
                'msg_cht' => '沒有\''.$token.'\'登入階段，請重新登入'
            ));
        }
    });

    /*
     * 登出此登入階段
     * DELETE http://localhost/api/v2/tokens/{登入Token}
     */
    $app->delete('/:token', function ($token) use ($app) {

        try {
            $session = new User\UserSession();
            $user_id = $session->getUserId($token);
            $session->logout($token);

            $app->render(201,array(
                'token'   => $token,
                'user_id' => $user_id,
                'error'   => false,
                'msg'     => '\''.$user_id.'\' this session is logout.',
                'msg_cht' => '\''.$user_id.'\'此登入階段已登出'
            ));
        }
        catch (Exception\LoginTokenNoFoundException $e) {
            $app->render(404,array(
                'token'   => $token,
                'error'   => true,
                'msg'     => 'No \''.$token.'\' session. Please login again.',
                'msg_cht' => '沒有\''.$token.'\'登入階段，請重新登入'
            ));
        }
    });

    /*
     * 登出此此使用者其他登入階段
     * DELETE http://localhost/api/v2/tokens/{登入Token}/session/other
     */
    $app->delete('/:token/session/other', function ($token) use ($app) {

        try {
            $session = new User\UserSession();
            $user_id = $session->getUserId($token);
            $logoutTotal = $session->logoutOtherSession($token);
            $inLoginTotal = $session->getCurrentLoginTotalByUserId($user_id);

            $app->render(201,array(
                'token'        => $token,
                'user_id'      => $user_id,
                'logout_total' => $logoutTotal,
                'login_total'  => $inLoginTotal,
                'error'        => false,
                'msg'          => '\''.$user_id.'\' other session is logout.',
                'msg_cht'      => '\''.$user_id.'\'此登入階段之外的皆已登出'
            ));
        }
        catch (Exception\LoginTokenNoFoundException $e) {
            $app->render(404,array(
                'token'   => $token,
                'error'   => true,
                'msg'     => 'No \''.$token.'\' session. Please login again.',
                'msg_cht' => '沒有\''.$token.'\'登入階段，請重新登入'
            ));
        }
    });

});

// ============================================================================

// 取得Client要求的格式
$requestType = $app->request->headers->get('Accept');
// 若要求網頁版
if(strpos($requestType, 'text/html') !== false) {

    // API首頁
    $app->get('/', function () use ($app) {
        include('html/index.html');
    });

    // 沒有此功能
    $app->notFound(function () use ($app) {
        include('html/404.html');
    });
}
// 要求其他格式時，將以JSON為主
else {

    // API首頁
    $app->get('/', 'APIrequest', function () use ($app) {
        $app->render(200, array(
            'title'   => '',
            'version' => '2.0',
            'error'   => false,
        ));
    });

    // 沒有此功能
    $app->notFound(function () use ($app) {
        $app->view(new \JsonApiView());
        $app->add(new \JsonApiMiddleware());

        $app->render(404,array(
            'error'       => true,
            'msg'     => 'No this function.',
            'msg_cht' => '沒有此功能'
        ));
    });
}

// 內部出錯
$app->error(function (\Exception $e) use ($app) {
    //$app->render('error.php');
});


$app->run();
