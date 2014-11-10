<?php
require_once __DIR__.'/../../config.php';
require UELEARNING_ROOT.'/vendor/autoload.php';
require_once UELEARNING_LIB_ROOT.'/User/User.php';
use UElearning\User;
use UElearning\Exception;

$app = new \Slim\Slim(array(
    'templates.path' => './' // 設定Path
));

// 設定成將使用JSON格式輸出
function APIrequest() {
    $app = \Slim\Slim::getInstance();
    $app->view(new \JsonApiView());
    $app->add(new \JsonApiMiddleware());
}

function APIdisableFunc($app) {
    $app->render(405,array(
        'error'       => true,
        'message'     => 'This function is not enable.',
        'message_cht' => '此功能尚未開放'
    ));
}

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
            'message'     => 'No this function.',
            'message_cht' => '沒有此功能'
        ));
    });
}

// 內部出錯
$app->error(function (\Exception $e) use ($app) {
    //$app->render('error.php');
});


// 測試用 Say hello!~~~
$app->get('/Hello/:name', function ($name) use ($app) {
    $app->view(new \JsonApiView());
    $app->add(new \JsonApiMiddleware());

    $app->render(200,array(
        'error'   => false,
        'message' => 'Hello, $name'
    ));
});

// ============================================================================

$app->group('/Users', 'APIrequest', function () use ($app) {

    // 建立帳號
    $app->post('/', function () use ($app) {
        APIdisableFunc($app);
    });

    // 取得帳號資訊
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
                'message'     => '\''.$user_id.'\' is not found',
                'message_cht' => '找不到\''.$user_id.'\'使用者'
            ));
        }


    });

    $app->post('/:user_id/Login/', function ($user_id) use ($app) {
        // TODO: 登入
        APIdisableFunc($app);
    });
});

$app->group('/UTokens', 'APIrequest', function () use ($app) {

    $app->get('/:token', function ($token) {
        //echo "Login Token: $token";
        // TODO: 登入Token
        APIdisableFunc($app);
    });

});


$app->run();
