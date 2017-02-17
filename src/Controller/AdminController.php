<?php

namespace writerblog\Controller;

use Silex\Application;
use writerblog\Domain\Billet;
use writerblog\Domain\User;
use writerblog\Form\Type\BilletType;
use writerblog\Form\Type\CommentType;
use writerblog\Form\Type\UserType;
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
            'billetForm' => $billetFormView,
            'title' => 'Ajouter un billet',
            'messageButton' => 'Ajouter'
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
            'billetForm' => $billetFormView,
            'title' => 'Editer un billet',
            'messageButton' => 'Modifier'            
        ));
    }

    public function billetDeleteAction($id, Request $request, Application $app) {
        $app['dao.comment']->deleteAllByIdBillet($id);
        $app['dao.billet']->deleteBillet($id);
        $app['session']->getFlashBag()->add('success', 'Your billet was successfully deleted.');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    public function commentEditAction($id, Request $request, Application $app) {
        $comment = $app['dao.comment']->read($id);
        $billet = $comment->getBillet();
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Your comment was successfully updated.');
        }
        return $app['twig']->render('comment_form.html.twig', array(
            'commentForm' => $commentForm->createView(),
            'billet' => $billet
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

    public function userAddAction(Request $request, Application $app) {
        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password); 
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'A user was successfully created.');
        }
        return $app['twig']->render('user_form.html.twig', array(
            'userForm' => $userForm->createView(),
            'title' => 'Ajouter un utilisateur',
            'messageButton' => 'Ajouter'
        ));
    }

    public function userEditAction($id, Request $request, Application $app) {
        $user = $app['dao.user']->read($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $password = $app['security.encoder.bcrypt']->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password); 
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully updated');
        }
        return $app['twig']->render('user_form.html.twig', array(
            'userForm' => $userForm->createView(),
            'title' => 'Editer un utilisateur',
            'messageButton' => 'Modifier'
        ));
    }

    public function userDeleteAction($id, Request $request, Application $app) {
        // delete all comments from a user
        $app['dao.comment']->deleteAllByIdUser($id);
        // delete the user
        $app['dao.user']->delete($id);
        // update amount of comments to every billet
        $billets = $app['dao.billet']->readAll();
        foreach ($billets as $billet) {
            $nbComments = $app['dao.billet']->countComments($billet->getId());
            $billet->setNbComments($nbComments);
            $app['dao.billet']->update($billet);
        }
        // success message
        $app['session']->getFlashBag()->add('success', 'The user was successfully deleted');        
        return $app->redirect($app['url_generator']->generate('admin'));        
    }
}