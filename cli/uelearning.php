#!/usr/bin/env php
<?php

// set to run indefinitely if needed
set_time_limit(0);

/* Optional. It’s better to do it in the php.ini file */
date_default_timezone_set('America/Los_Angeles'); 

// include the composer autoloader
require_once __DIR__ . '/vendor/autoload.php'; 

// import the Symfony Console Application 
use Symfony\Component\Console\Application; 

$app = new Application();
$app->run();
?>