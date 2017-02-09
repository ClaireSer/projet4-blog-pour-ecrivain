<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
});
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/logs/writerblog.log',
    'monolog.name' => 'writerblog',
    'monolog.level' => $app['monolog.level']
));

// Register services
$app['dao.billet'] = function ($app) {
    return new writerblog\DAO\BilletDAO($app['db']);
};
$app['dao.comment'] = function ($app) {
    $commentDAO = new writerblog\DAO\CommentDAO($app['db']);
    $commentDAO->setBilletDAO($app['dao.billet']);
    return $commentDAO;
};
