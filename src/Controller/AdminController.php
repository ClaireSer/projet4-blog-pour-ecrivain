<?php

namespace writerblog\Controller;

use Silex\Application;


class AdminController {

    public function indexAction(Application $app) {
        $billets = $app['dao.billet']->readAll();
        return $app['twig']->render('admin.html.twig', array(
            // 'billets' => $billets
        ));
    }
}