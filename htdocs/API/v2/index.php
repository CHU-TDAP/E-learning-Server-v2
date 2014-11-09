<?php
require_once __DIR__.'/../../config.php';
require UELEARNING_ROOT.'/vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/', function () {
    echo "Test";
});

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->run();
