<?php
require_once __DIR__.'/../../config.php';
require UELEARNING_ROOT.'/vendor/autoload.php';
require_once __DIR__.'/src/ApiTemplates.php';
require_once UELEARNING_LIB_ROOT.'/User/User.php';
require_once UELEARNING_LIB_ROOT.'/User/UserSession.php';
use UElearning\User;
use UElearning\Exception;

$app = new \Slim\Slim(array(
    'templates.path' => './' // 設定Path
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
        'msg' => 'Hello, $name'
    ));
});

// ============================================================================

$app->group('/users', 'APIrequest', function () use ($app, $app_template) {

    /*
     * 建立帳號
     * POST http://localhost/api/v2/users
     */
    $app->post('/', function () use ($app) {
        $app_template->noEnableFunc();
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
        else if($cType == 'application/json') {
            $postData = $app->request->getBody();
            $postDataArray = json_decode($postData);
            $password = $postDataArray->password;
            $browser  = isset($postDataArray->browser)
                            ? $postDataArray->browser : 'api';
        }
        else {
            $app->render(400, array(
                    'Content-Type'=> $cType,
                    'error'       => true,
                    'msg'     => '',
                    'msg_cht' => '輸入參數的Content-Type不在支援範圍內 或是沒有輸入',
                    'substatus'   => 102
                )
            );
        }
        if(!isset($browser)) { $browser = 'api'; }

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
    $app->get('/:token', function ($token) use ($app, $app_template) {
        //echo "Login Token: $token";
        // TODO: 登入Token
        $app_template->noEnableFunc();
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
