<?php

namespace Sopamo\Double\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use ReflectionClass;
use Sopamo\Double\PHPLoader;

class DoubleApiController extends BaseController
{
    public function loadData(PHPLoader $loader, Request $request) {
        $source = $loader->getSource($request->input('path'));
        $instance = new $source();
        $class = new ReflectionClass($instance);
        $methods = $class->getMethods();

        $response = [];
        // Execute all "get" methods and add the results to the response array
        foreach($methods as $method) {
            if(Str::startsWith($method->name, 'get') && ctype_upper(substr($method->name, 3, 1))) {
                $responseKey = strtolower(substr($method->name, 3, 1)) . substr($method->name, 4);
                $response[$responseKey] = $instance->{$method->name}($request);
            }
        }

        return Response::json($response);
    }

    public function runAction(PHPLoader $loader, Request $request) {
        $source = $loader->getSource($request->input('path'));
        $instance = new $source();

        // Call the requested method
        $response = $instance->{$request->input('method')}($request);

        return Response::json($response);
    }
}