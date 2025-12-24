<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Procesa el mensaje del estudiante y obtiene respuesta de Gemini
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->input('message');
        
        try {
            // Obtener API key de Gemini
            $apiKey = env('GEMINI_API_KEY');
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'API key no configurada. Por favor configura GEMINI_API_KEY en el archivo .env'
                ], 500);
            }

            // Preparar el prompt con contexto de orientación vocacional
            $systemPrompt = "Eres un asistente virtual especializado en orientación vocacional para estudiantes de secundaria. 
Tu objetivo es ayudar a los estudiantes a descubrir sus intereses, habilidades y aptitudes para orientarlos hacia carreras universitarias adecuadas.
Debes ser amable, motivador y hacer preguntas reflexivas que ayuden al estudiante a conocerse mejor.
Proporciona información sobre diferentes carreras, requisitos, campos laborales y universidades cuando sea relevante.
Mantén un tono profesional pero cercano, apropiado para estudiantes de secundaria.";

            // Construir el mensaje para Gemini
            $prompt = $systemPrompt . "\n\nEstudiante: " . $userMessage . "\n\nAsistente:";

            // Llamar a la API de Gemini
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ],
                    'safetySettings' => [
                        [
                            'category' => 'HARM_CATEGORY_HARASSMENT',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_HATE_SPEECH',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ]
                    ]
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                
                // Extraer el texto de la respuesta
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $assistantMessage = $data['candidates'][0]['content']['parts'][0]['text'];
                    
                    return response()->json([
                        'success' => true,
                        'message' => $assistantMessage
                    ]);
                } else {
                    Log::error('Respuesta de Gemini sin texto esperado', ['response' => $data]);
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pudo obtener una respuesta válida del asistente.'
                    ], 500);
                }
            } else {
                Log::error('Error en API de Gemini', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al conectar con el servicio de IA. Por favor intenta nuevamente.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Excepción en ChatController', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar tu mensaje. Por favor intenta nuevamente.'
            ], 500);
        }
    }
}
