<?php

$app->get('/', "writerblog\Controller\HomeController::indexAction")
->bind('home');

$app->match('/billet/{id}', "writerblog\Controller\HomeController::billetAction")
->bind('billet');

$app->get('/login', "writerblog\Controller\HomeController::loginAction")
->bind('login');
