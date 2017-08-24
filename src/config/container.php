<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 20:33
 */

use Programaths\LiveEdu\Todo\Controllers\TodoController;
use Programaths\LiveEdu\Todo\Controllers\UserController;
use Programaths\LiveEdu\Todo\Services\RouteTemplating;
use Programaths\LiveEdu\Todo\Services\TodoRepository;
use Programaths\LiveEdu\Todo\Services\UserProvider;
use Programaths\LiveEdu\Todo\Services\UserRepository;

$app = new \Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider());

$app->extend('monolog', function(Monolog\Logger $monolog, $app) {
    $monolog->pushHandler(new \Monolog\Handler\ErrorLogHandler());

    return $monolog;
});

$app['security.firewalls'] = array(
    'admin' => [
        'pattern' => '^/api/v2-0/users',
        'stateless' => true,
        'guard'=>[
            'authenticators' => array(
                'app.token_authenticator'
            )
        ],
        'users' => function() use($app) {
            return $app['user.provider'];
        }
    ]
);

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Sorien\Provider\PimpleDumpProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' =>
        [
            'driver'    => 'pdo_pgsql',
            'host'      => 'localhost',
            'dbname'    => 'livetodo',
            'user'      => 'livetodo',
            'password'  => '1234',
            'charset'   => 'utf8',
        ],
]);

$app['user.provider'] = function () use($app){
    return new UserProvider($app['user.repository']);
};

$app['user.repository'] = function() use($app) {
    return new UserRepository($app['db']);
};

$app['user.controller'] = function() use($app) {
    return new UserController($app['user.repository']);
};

$app['todo.repository'] = function() use($app) {
    return new TodoRepository($app['db']);
};

$app['todo.controller'] = function() use($app) {
    return new TodoController($app['todo.repository']);
};

$app['templating'] = function () use ($app) {
    return new RouteTemplating([
        'templateMap' => [
            'user_get' => [
                'xml' => 'user.html',
                'tss' => 'user.tss'
            ],
            'user_all' => [
                'xml' => 'user_all.html',
                'tss' => 'user_all.tss'
            ],
            'todo_get' => [
                'xml' => 'todo.html',
                'tss' => 'todo.tss'
            ],
            'todo_all' => [
                'xml' => 'todo_all.html',
                'tss' => 'todo_all.tss'
            ],
        ],
        'baseFolder' => __DIR__.'/templates'
    ]);
};

