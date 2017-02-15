<?php

namespace writerblog\Controller;

use Silex\Application;
use writerblog\Domain\Billet;
use writerblog\Form\Type\BilletType;
use writerblog\Form\Type\CommentType;
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

    public function commentEditAction($id, Request $request, Application $app) {
        $comment = $app['dao.comment']->read($id);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Your comment was successfully updated.');
        }
        $commentFormView = $commentForm->createView();
        return $app['twig']->render('comment_form.html.twig', array(
            'commentForm' => $commentFormView
        ));
    }

    public function commentDeleteAction($id, Request $request, Application $app) {
        // get billet object from a comment
        $comment = $app['dao.comment']->read($id);
        $billet = $comment->getBillet();
        // update amount of comments (remove one comment) to the associate billet 
        $nbComments = $billet->getNbComments() - 1;
        $billet->setNbComments($nbComments);
        // delete comment
        $app['dao.comment']->delete($id);
        // display the right amount of comments
        $app['dao.billet']->update($billet);
        // success message
        $app['session']->getFlashBag()->add('success', 'Your comment was successfully deleted.');        
        return $app->redirect($app['url_generator']->generate('admin'));        
    }
}

        