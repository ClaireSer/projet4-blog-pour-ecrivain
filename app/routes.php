<?php

$app->get('/', "writerblog\Controller\HomeController::indexAction")
->bind('home');

$app->get('/billet/{id}', "writerblog\Controller\HomeController::billetAction")
->bind('billet');
