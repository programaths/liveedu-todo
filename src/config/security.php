<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 24/08/17
 * Time: 21:19
 */

$app['app.token_authenticator'] = function ($app) {
    return new \Programaths\LiveEdu\Todo\Services\PlainAuthListener();
};
