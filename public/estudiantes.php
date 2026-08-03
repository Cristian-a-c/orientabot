<?php

// Cargar el chat funcional con Gemini
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Http\Request;

// Capturar datos del estudiante si vienen por POST
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && isset($_POST['dni'])) {
    $_SESSION['estudiante_nombre'] = $_POST['nombre'];
    $_SESSION['estudiante_dni'] = $_POST['dni'];
    $_SESSION['sesion_inicio'] = date('Y-m-d H:i:s');
}

// Si no hay datos de sesión, redirigir al inicio
if (!isset($_SESSION['estudiante_nombre'])) {
    header('Location: /inicio.php');
    exit;
}

$request = Request::create('/estudiantes', 'GET');
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);
