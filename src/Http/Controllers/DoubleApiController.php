<?php

namespace Sopamo\Double\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use Sopamo\Double\PHPLoader;

class DoubleApiController extends BaseController
{
    public function loadData(PHPLoader $loader, Request $request) {
        $source = $loader->getSource($request->input('path'));
        $instance = new $source();
        $class = new ReflectionClass($instance);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $response = [];
        $originalConfig = [];
        $baseRequestData = $request->request->getIterator()->getArrayCopy();
        if($request->request->has('config')) {
            $originalConfig = $request->request->get('config');
            unset($baseRequestData['config']);
        }
        // Execute all "get" methods and add the results to the response array
        foreach($methods as $method) {
            if(Str::startsWith($method->name, 'get') && ctype_upper(substr($method->name, 3, 1))) {
                $responseKey = strtolower(substr($method->name, 3, 1)) . substr($method->name, 4);

                // If the request data contains a config for this method, make sure the final request we send to the method implementation
                // directly contains the config data
                $requestData = $baseRequestData;
                if($originalConfig && isset($originalConfig[$method->name])) {
                    $requestData = [
                        ...$requestData,
                        ...$originalConfig[$method->name]
                    ];
                }
                $request->request->replace($requestData);

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
