<?php

namespace writerblog\Controller;

use Silex\Application;
use writerblog\Domain\Billet;
use writerblog\Form\Type\BilletType;
use Symfony\Component\HttpFoundation\Request;


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

    public function billetAddAction(Request $request, Application $app) {
        $billet = new Billet();
        $billet->setDateAjout(date('Y-m-d'));
        $billet->setNbComments(0);
        $billetForm = $app['form.factory']->create(BilletType::class, $billet);
        $billetForm->handleRequest($request);
        if ($billetForm->isSubmitted() && $billetForm->isValid()) {
            $app['dao.billet']->create($billet);
            $app['session']->getFlashBag()->add('success', 'Your billet was successfully added.');
        }
        $billetFormView = $billetForm->createView();
        return $app['twig']->render('billet_form.html.twig', array(
            'billetForm' => $billetFormView
        ));
    }

    public function billetEditAction($id, Request $request, Application $app) {
        $billet = $app['dao.billet']->read($id);
        $billet->setDateModif(date('Y-m-d'));
        $billet->getTitle();
        $billet->getContent();
        $billetForm = $app['form.factory']->create(BilletType::class, $billet);        
        $billetForm->handleRequest($request);
        if ($billetForm->isSubmitted() && $billetForm->isValid()) {
            $app['dao.billet']->update($billet);
            $app['session']->getFlashBag()->add('success', 'Your billet was successfully updated.');
        }
        $billetFormView = $billetForm->createView();
        return $app['twig']->render('billet_form.html.twig', array(
            'billetForm' => $billetFormView
        ));
    }

    public function billetDeleteAction($id, Request $request, Application $app) {
        $app['dao.comment']->deleteCommentsByIdBillet($id);
        $app['dao.billet']->deleteBillet($id);
        $app['session']->getFlashBag()->add('success', 'Your billet was successfully deleted.');
        return $app->redirect($app['url_generator']->generate('admin'));
        
    }
}