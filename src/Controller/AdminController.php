<?php

namespace writerblog\Controller;

use Silex\Application;


class AdminController {

    public function indexAction(Application $app) {
        $billets = $app['dao.billet']->readAll();
        $comments = $app['dao.comment']->readAll();
        $users = $app['dao.user']->readAll();
        return $app['twig']->render('admin.html.twig', array(
            'billets' => $billets,
            'comments' => $comments,
            'users' => $users
        ));
    }
}