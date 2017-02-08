<?php

namespace writerblog\Controller;

use Silex\Application;

class HomeController {
    
    public function indexAction(Application $app) {
        $billets = $app['dao.billet']->readAll();
        return $app['twig']->render('index.html.twig', array('billets' => $billets));
    }

    public function billetAction($id, Application $app) {
        $billet = $app['dao.billet']->read($id);
        return $app['twig']->render('billet.html.twig', array('billet' => $billet));
    }
}
