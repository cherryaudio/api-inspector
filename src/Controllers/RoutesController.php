<?php

namespace CherryAudio\ApiInspector\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class RoutesController extends Controller
{
    public $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function index()
    {
        return $this->getRoutes();
    }

    public function getRoutes()
    {
        return collect($this->router->getRoutes())
            ->map(function ($route) {
                return $this->getRouteInformation($route);
            })
            ->filter(function ($route) {
                return Str::startsWith($route['uri'], 'api') && $route['method'] === 'GET|HEAD';
            })
            ->all();
    }

    public function getRouteInformation(Route $route)
    {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'parameters' => $route->parameterNames(),
            'action' => ltrim($route->getActionName(), '\\'),
        ];
    }
}
