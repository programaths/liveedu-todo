<?php

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

$app->get('/',function(Request $request) use($app) {
    $helloWorld = new \Programaths\LiveEdu\Todo\HelloWorld();
    return JsonResponse::create($helloWorld->helloWorld());
});

$app->before(function (Request $request) {

    $contentType = $request->headers->get('Content-Type','application/json');
    if (0 === strpos($contentType, 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
        return;
    }
});

$app->view(function ($controllerResult, Request $request) use ($app) {
    $accept = $request->headers->get('Accept','application/json');
    if (0 === strpos($accept, 'text/html')) {
        $routeName = $request->get('_route');
        return $app['templating']->getByRoute($routeName)
            ->render($controllerResult);
    }
    return JsonResponse::create($controllerResult);
});

$app->post('/api/v2-0/users','user.controller:post')
    ->bind('user_post');

$app->patch('/api/v2-0/users','user.controller:patch')
    ->bind('user_patch');

$app->delete('/api/v2-0/users/{id}','user.controller:delete')
    ->bind('user_delete');

$app->get('/api/v2-0/users/{id}','user.controller:get')
    ->assert('id','\d+')
    ->bind('user_get');

$app->get('/api/v2-0/users/','user.controller:all')
    ->bind('user_all');



$app->get('/api/v2-0/todos/{id}','todo.controller:get')
    ->assert('id','\d+')
    ->bind('todo_get');

$app->delete('/api/v2-0/todos/{id}','todo.controller:delete')
    ->bind('todo_delete');

$app->patch('/api/v2-0/todos','todo.controller:patch')
    ->bind('todo_patch');

$app->post('/api/v2-0/users','todo.controller:post')
    ->bind('todo_post');