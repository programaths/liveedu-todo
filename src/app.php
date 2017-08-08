<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 8/08/17
 * Time: 20:27
 */

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

include __DIR__.'/../vendor/autoload.php';

$app = new \Silex\Application();

$app->get('/',function(Request $request){
    $helloWorld = new \Programaths\LiveEdu\Todo\HelloWorld();
    return JsonResponse::create($helloWorld->helloWorld());
});

$app->post('/',function (Request $request){
    return JsonResponse::create();
});

return $app;