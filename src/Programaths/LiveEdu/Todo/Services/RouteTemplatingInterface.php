<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 22:01
 */

namespace Programaths\LiveEdu\Todo\Services;

use Programaths\LiveEdu\Todo\Exceptions\RouteNotFound;

interface RouteTemplatingInterface
{
    /**
     * @param $route
     * @return \Programaths\LiveEdu\Todo\Services\TemplatingInterface
     * @throws RouteNotFound
     */
    public function getByRoute($route);
}