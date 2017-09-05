<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 24/08/17
 * Time: 21:19
 */

$app['app.token_authenticator'] = function () use($app) {
    return new \Programaths\LiveEdu\Todo\Services\PlainAuthListener($app['security.default_encoder']);
};

$app['security.default_encoder'] = function(){
    return new \Programaths\LiveEdu\Todo\Services\PasswordVerifyEncoder();
};