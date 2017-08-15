<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 21:47
 */

namespace Programaths\LiveEdu\Todo\Services;

interface TemplatingInterface
{
    public function render($model, $status = 200);
}