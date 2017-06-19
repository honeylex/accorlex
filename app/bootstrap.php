<?php

use Accorlex\Article\Controller\ArticleController;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;

ini_set('display_errors', 1);

$app = new Application;
$app->register(new ServiceControllerServiceProvider);
$app['debug'] = $appDebug;
$app['message_bus'] = require __DIR__.'/message_bus.php';

$app->get('/', [ArticleController::class, 'read']);

return $app;
