<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/veemix', function(Request $request) {
    $path = $request->input('path');
    $x =  require(app_path('../' . $path) . '.v.php');
    $y = new $x();
    $class = new ReflectionClass($y);
    $methods = $class->getMethods();

    $response = [];

    foreach($methods as $method) {
        if(Str::startsWith($method->name, 'get') && ctype_upper(substr($method->name, 3, 1))) {
            $responseKey = strtolower(substr($method->name, 3, 1)) . substr($method->name, 4);
            $response[$responseKey] = $y->{$method->name}();
        }
    }

    return response()->json($response);
});

Route::post('/veemix', function(Request $request) {
    $path = $request->input('path');
    $x =  require(app_path('../' . $path) . '.v.php');
    $y = new $x();

    $response = $y->{$request->input('method')}($request);

    return response()->json($response);
});
