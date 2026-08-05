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
            // Cargar credenciales desde la configuración de Laravel
            $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
            $model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-1.5-flash'));
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'API key no configurada. Por favor configura GEMINI_API_KEY en el archivo .env'
                ], 500);
            }

            // Prompt del sistema con contexto enfocado en la UNAMBA
            $systemPrompt = "Eres OrientaBot, el asistente virtual de orientación vocacional de la Universidad Nacional Micaela Bastidas de Apurímac (UNAMBA), en Abancay, Perú.

TU MISIÓN:
Ayudar a estudiantes de secundaria a descubrir sus intereses y orientarlos, PRIORITARIAMENTE, hacia las carreras que ofrece actualmente la UNAMBA.

CARRERAS QUE DICTA LA UNAMBA ACTUALMENTE (sede Abancay):

**Facultad de Ingenierías:**
1. **Ingeniería Informática y Sistemas**: diseño y desarrollo de software, sistemas de información, redes e infraestructura tecnológica.
2. **Ingeniería Civil**: planificación, diseño y construcción de infraestructura (edificaciones, carreteras, puentes).
3. **Ingeniería de Minas**: exploración, explotación y gestión de recursos minerales, relevante para la región Apurímac.
4. **Ingeniería Agroindustrial**: transformación y procesamiento de productos agropecuarios con valor agregado.
5. **Ingeniería en Agroecología y Desarrollo Rural**: producción agrícola sostenible y desarrollo de comunidades rurales.

**Facultad de Administración:**
6. **Administración**: gestión de organizaciones públicas y privadas, planeamiento estratégico y recursos.

**Facultad de Educación y Ciencias Sociales:**
7. **Educación Inicial Intercultural Bilingüe**: formación de docentes para el nivel inicial, con enfoque intercultural (castellano/quechua).
8. **Ciencia Política y Gobernabilidad**: análisis político, gestión pública y fortalecimiento institucional.

**Facultad de Medicina Veterinaria y Zootecnia:**
9. **Medicina Veterinaria y Zootecnia**: salud animal, producción pecuaria y sanidad agropecuaria.

DIRECTRICES IMPORTANTES:
1. Cuando el estudiante mencione un interés o habilidad, identifica cuál(es) de estas 9 carreras de la UNAMBA se ajustan mejor, y explica por qué, con una descripción breve del campo laboral.
2. Si el interés del estudiante no encaja claramente con ninguna de estas 9 carreras (por ejemplo, medicina humana, derecho, psicología), sé honesto: indica que esa carrera específica todavía no se dicta en la UNAMBA, y sugiere la(s) carrera(s) de la UNAMBA más cercana(s) a su interés como alternativa a considerar.
3. Resalta cuando una carrera tenga relevancia particular para la región Apurímac.
4. Responde de forma completa, estructurada, con listas numeradas o viñetas, y un tono motivador pero profesional.
5. Nunca inventes carreras que la UNAMBA no dicte actualmente.";

            // Endpoint dinámico
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            // Petición a Gemini con ignorado de verificación SSL para entorno local
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemPrompt]
                        ]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $userMessage]
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
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $assistantMessage = $data['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Manejo seguro de la sesión con helpers nativos de Laravel
                    $nombre = session('estudiante_nombre', $_SESSION['estudiante_nombre'] ?? null);
                    $dni = session('estudiante_dni', $_SESSION['estudiante_dni'] ?? null);

                    if ($nombre && $dni) {
                        DB::table('conversaciones')->insert([
                            'estudiante_nombre' => $nombre,
                            'estudiante_dni' => $dni,
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
                    'message' => 'Error al conectar con el servicio de IA: ' . $response->status()
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
