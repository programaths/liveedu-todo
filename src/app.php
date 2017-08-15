<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 8/08/17
 * Time: 20:27
 */

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Programaths\LiveEdu\Todo\Services\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

include __DIR__.'/../vendor/autoload.php';

include __DIR__.'/config/container.php';
include __DIR__.'/config/routing.php';

return $app;