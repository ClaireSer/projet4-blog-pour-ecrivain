<?php

namespace writerblog\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController {
    
    public function indexAction(Application $app) {
        $billets = $app['dao.billet']->readAll();
        return $app['twig']->render('index.html.twig', array('billets' => $billets));
    }

    public function billetAction($id, Application $app) {
        $billet = $app['dao.billet']->read($id);
        $comments = $app['dao.comment']->readAllByIdBillet($id);
        
        return $app['twig']->render('billet.html.twig', array(
            'billet' => $billet,
            'comments' => $comments
        ));
    }

    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
            // 'error'         => $app['security.last_error']($request),
            // 'last_username' => $app['session']->get('_security.last_username')
        ));
    }
}
