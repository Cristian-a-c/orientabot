<?php

// Redirigir a la aplicación Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Http\Request;

$request = Request::create('/estudiantes', 'GET');
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);
