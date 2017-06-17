<?php

use Silex\Application;
use Accorlex\Home\Controller\ResourceController;

$app = new Application;

$app['debug'] = $appDebug;

$app->get('/', [ResourceController::class, 'read']);

return $app;
