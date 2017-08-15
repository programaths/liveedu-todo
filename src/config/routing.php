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