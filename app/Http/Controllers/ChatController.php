<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            $systemPrompt = "Eres un asistente virtual especializado en orientación vocacional para estudiantes de secundaria en Perú.

DIRECTRICES IMPORTANTES:
1. Cuando te pregunten sobre carreras relacionadas a un área, SIEMPRE proporciona una lista clara y organizada de al menos 8-10 carreras específicas.
2. Para cada carrera mencionada, incluye una breve descripción (1-2 líneas) de lo que hace el profesional.
3. Organiza las carreras por categorías cuando sea posible (Ingenierías, Ciencias de la Salud, etc.).
4. Sé específico con los nombres de las carreras (ej: 'Ingeniería de Sistemas' en vez de solo 'Ingeniería').
5. Incluye información sobre el campo laboral y perspectivas de cada carrera cuando sea relevante.
6. Responde de forma completa y estructurada, utilizando listas numeradas o con viñetas.
7. Mantén un tono motivador y cercano, pero profesional.

FORMATO DE RESPUESTA PARA LISTAS DE CARRERAS:
Cuando te pregunten sobre carreras de un área, responde así:
'¡Excelente pregunta! Aquí te presento las principales carreras relacionadas con [área]:

**[Categoría 1]:**
1. **Nombre de Carrera**: Breve descripción de lo que hace el profesional.
2. **Otra Carrera**: Descripción concisa.

**[Categoría 2]:**
1. **Nombre de Carrera**: Descripción.

Después puedes agregar información adicional sobre perspectivas laborales, habilidades necesarias, etc.'

Tu objetivo es ayudar a los estudiantes a descubrir sus intereses y orientarlos hacia carreras universitarias adecuadas.";

            // Construir el mensaje para Gemini
            $prompt = $systemPrompt . "\n\nEstudiante: " . $userMessage . "\n\nAsistente:";

            // Llamar a la API de Gemini
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
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
                            'maxOutputTokens' => 2048,
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
                    
                    // Guardar la conversación en la base de datos
                    session_start();
                    if (isset($_SESSION['estudiante_nombre']) && isset($_SESSION['estudiante_dni'])) {
                        DB::table('conversaciones')->insert([
                            'estudiante_nombre' => $_SESSION['estudiante_nombre'],
                            'estudiante_dni' => $_SESSION['estudiante_dni'],
                            'mensaje_usuario' => $userMessage,
                            'respuesta_asistente' => $assistantMessage,
                            'fecha_conversacion' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    
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
