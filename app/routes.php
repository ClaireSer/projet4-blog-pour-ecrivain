<?php

$app->get('/', "writerblog\Controller\HomeController::indexAction")
->bind('home');

$app->match('/billet/{id}', "writerblog\Controller\HomeController::billetAction")
->bind('billet');

$app->get('/login', "writerblog\Controller\HomeController::loginAction")
->bind('login');

$app->get('/admin', "writerblog\Controller\AdminController::indexAction")
->bind('admin');


// billet controller 
$app->match('/admin/billet/{id}/edit', "writerblog\Controller\AdminController::billetEditAction")
->bind('admin_billet_edit');

$app->match('/admin/billet/add', "writerblog\Controller\AdminController::billetAddAction")
->bind('admin_billet_add');

$app->get('/admin/billet/{id}/delete', "writerblog\Controller\AdminController::billetDeleteAction")
->bind('admin_billet_delete');


// // comment controller 
$app->match('/admin/comment/{id}/edit', "writerblog\Controller\AdminController::commentEditAction")
->bind('admin_comment_edit');

$app->get('/admin/comment/{id}/delete', "writerblog\Controller\AdminController::commentDeleteAction")
->bind('admin_comment_delete');


// user controller
$app->match('/admin/user/{id}/edit', "writerblog\Controller\AdminController::userEditAction")
->bind('admin_user_edit');

$app->match('/admin/user/add', "writerblog\Controller\AdminController::userAddAction")
->bind('admin_user_add');

$app->get('/admin/user/{id}/delete', "writerblog\Controller\AdminController::userDeleteAction")
->bind('admin_user_delete');

