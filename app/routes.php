<?php

$app->get('/', "writerblog\Controller\HomeController::indexAction")
->bind('home');

$app->get('/billet', "writerblog\Controller\HomeController::billetAction")
->bind('billet');