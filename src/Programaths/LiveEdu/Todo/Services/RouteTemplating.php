<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 21:49
 */

namespace Programaths\LiveEdu\Todo\Services;
use Programaths\LiveEdu\Todo\Exceptions\RouteNotFound;

class RouteTemplating implements RouteTemplatingInterface
{
    /**
     * @var array
     */
    private $templateMap;

    private $renderers;
    private $baseFolder;

    public function __construct(array $config)
    {
        $this->templateMap = $config['templateMap'];
        $this->baseFolder = $config['baseFolder'];
    }

    /**
     * @param $route
     * @return \Programaths\LiveEdu\Todo\Services\TemplatingInterface
     * @throws RouteNotFound
     */
    public function getByRoute($route){
        if(!isset($this->templateMap[$route])){
            throw new RouteNotFound();
        }
        if(isset($this->renderers[$route])){
            return $this->renderers[$route];
        }
        $tplConfig = $this->templateMap[$route];
        $this->renderers[$route] = new Templating($this->baseFolder.'/'. $tplConfig['xml'],$this->baseFolder.'/'. $tplConfig['tss']);
        return $this->renderers[$route];
    }
}