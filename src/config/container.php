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
use Programaths\LiveEdu\Todo\Services\UserRepository;

$app = new \Silex\Application();
$app['debug'] = true;


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
            ]
        ],
        'baseFolder' => __DIR__.'/templates'
    ]);
};
