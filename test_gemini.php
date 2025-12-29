<?php

// Script de prueba para verificar la conexión con Gemini API
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'];

echo "Probando conexión con Gemini API...\n";
echo "API Key: " . substr($apiKey, 0, 10) . "...\n\n";

// Primero, obtener la lista de modelos disponibles
echo "=== OBTENIENDO LISTA DE MODELOS DISPONIBLES ===\n\n";

$listUrl = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";

$ch = curl_init($listUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    
    echo "Modelos disponibles que soportan generateContent:\n\n";
    
    $availableModels = [];
    foreach ($result['models'] as $model) {
        if (in_array('generateContent', $model['supportedGenerationMethods'] ?? [])) {
            $modelName = str_replace('models/', '', $model['name']);
            $availableModels[] = $modelName;
            echo "  - " . $modelName . "\n";
        }
    }
    
    echo "\n=== PROBANDO PRIMER MODELO ===\n\n";
    
    if (!empty($availableModels)) {
        $testModel = $availableModels[0];
        echo "Probando modelo: $testModel\n";
        
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$testModel}:generateContent?key={$apiKey}";
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Hola, responde brevemente si puedes ayudar con orientación vocacional.']
                    ]
                ]
            ]
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "HTTP Code: $httpCode\n";
        
        if ($httpCode === 200) {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                echo "✓ ÉXITO!\n";
                echo "Respuesta: " . $result['candidates'][0]['content']['parts'][0]['text'] . "\n\n";
                echo "*** USAR ESTE MODELO EN ChatController.php: $testModel ***\n";
            }
        } else {
            echo "✗ Error\n";
            echo "Respuesta: " . $response . "\n";
        }
    }
} else {
    echo "Error al obtener lista de modelos. HTTP Code: $httpCode\n";
    echo "Respuesta: " . $response . "\n";
}
