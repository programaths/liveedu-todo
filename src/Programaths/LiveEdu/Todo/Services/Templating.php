<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 21:31
 */

namespace Programaths\LiveEdu\Todo\Services;


use Symfony\Component\HttpFoundation\Response;
use Transphporm\Builder;

class Templating implements TemplatingInterface
{
    private $template;

    public function __construct($xml, $tss)
    {
        $this->template = new Builder($xml, $tss);
    }

    public function render($model,$status=200){
        $result = $this->template->output($model);
        $headerMap = [];
        foreach ($result->headers as $header){
            $headerMap[$header[0]] = $header[1];
        }
        return Response::create($result->body,$status,$headerMap);

    }
}