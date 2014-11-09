<?php
require_once __DIR__.'/../../config.php';
require UELEARNING_ROOT.'/vendor/autoload.php';

$app = new \Slim\Slim(array(
    'templates.path' => './json'
));

$app->get('/', function () use ($app) {
    $requestType = $app->request->headers->get('Accept');
    if(strpos($requestType, 'text/html') !== FALSE) {
        include('html/index.html');
    }
    else {
        $app->view(new \JsonApiView());
        $app->add(new \JsonApiMiddleware());

        $app->render(200, array(
            'title'   => '',
            'version' => '2.0'
        ));
    }
});

$app->notFound(function () use ($app) {
    $requestType = $app->request->headers->get('Accept');
    if(strpos($requestType, 'text/html') !== FALSE) {
        include('html/404.html');
    }
    else {
        $app->view(new \JsonApiView());
        $app->add(new \JsonApiMiddleware());

        $app->render(404,array(
            'error'   => TRUE,
            'message' => 'user not found'
        ));
    }
});

$app->error(function (\Exception $e) use ($app) {
    //$app->render('error.php');
});


$app->group('/Users', function () use ($app) {

    $app->get('/:user_id', function ($user_id) {
        echo "Hello, $user_id";
    });

});

$app->group('/UTokens', function () use ($app) {

    $app->get('/:token', function ($token) {
        echo "Login Token: $token";
    });

});

// Say hello!~~~
$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->run();
