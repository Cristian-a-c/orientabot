<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Inicializar Laravel
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

session_start();

// Verificar que hay datos de sesión
if (!isset($_SESSION['estudiante_nombre'])) {
    header('Location: /inicio.php');
    exit;
}

$nombre = $_SESSION['estudiante_nombre'];
$dni = $_SESSION['estudiante_dni'];
$sesionInicio = $_SESSION['sesion_inicio'] ?? 'No disponible';

// Obtener conversaciones del estudiante
$conversaciones = DB::table('conversaciones')
    ->where('estudiante_dni', $dni)
    ->orderBy('fecha_conversacion', 'desc')
    ->get();

// Analizar carreras mencionadas en las conversaciones
$carrerasMencionadas = [];
foreach ($conversaciones as $conv) {
    $texto = strtolower($conv->respuesta_asistente);
    
    // Carreras que dicta actualmente la UNAMBA (sede Abancay)
    $carreras = [
        'Ingeniería Informática y Sistemas' => ['informátic', 'computación', 'sistemas', 'software', 'programación'],
        'Ingeniería Civil' => ['civil', 'construcción', 'edificacion', 'infraestructura'],
        'Ingeniería de Minas' => ['minas', 'minería', 'minero'],
        'Ingeniería Agroindustrial' => ['agroindustr'],
        'Ingeniería en Agroecología y Desarrollo Rural' => ['agroecolog', 'desarrollo rural'],
        'Administración' => ['administra', 'gestión', 'negocio', 'empresa'],
        'Educación Inicial Intercultural Bilingüe' => ['educación', 'docente', 'profesor', 'inicial'],
        'Ciencia Política y Gobernabilidad' => ['política', 'gobernabilidad', 'gestión pública'],
        'Medicina Veterinaria y Zootecnia' => ['veterinari', 'zootecni']
    ];
    
    foreach ($carreras as $carrera => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($texto, $keyword) !== false) {
                if (!isset($carrerasMencionadas[$carrera])) {
                    $carrerasMencionadas[$carrera] = 0;
                }
                $carrerasMencionadas[$carrera]++;
                break;
            }
        }
    }
}

arsort($carrerasMencionadas);
$topCarreras = array_slice($carrerasMencionadas, 0, 5, true);

$totalConversaciones = count($conversaciones);
$compatibilidadGeneral = min(95, max(60, 60 + ($totalConversaciones * 5) + (count($topCarreras) * 3)));

$areasPorCarrera = [
    'Facultad de Ingenierías' => ['Ingeniería Informática y Sistemas', 'Ingeniería Civil', 'Ingeniería de Minas', 'Ingeniería Agroindustrial', 'Ingeniería en Agroecología y Desarrollo Rural'],
    'Facultad de Administración' => ['Administración'],
    'Facultad de Educación y Ciencias Sociales' => ['Educación Inicial Intercultural Bilingüe', 'Ciencia Política y Gobernabilidad'],
    'Facultad de Medicina Veterinaria y Zootecnia' => ['Medicina Veterinaria y Zootecnia']
];

$afinidadPorArea = [];
foreach ($areasPorCarrera as $area => $carrerasArea) {
    $puntaje = 0;
    foreach ($carrerasArea as $carrera) {
        if (isset($carrerasMencionadas[$carrera])) {
            $puntaje += $carrerasMencionadas[$carrera];
        }
    }
    if ($puntaje > 0) {
        $afinidadPorArea[$area] = $puntaje;
    }
}
arsort($afinidadPorArea);

$areaPrincipal = !empty($afinidadPorArea) ? array_key_first($afinidadPorArea) : 'Facultad de Ingenierías';

$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$fechaFormateada = date('d', strtotime($sesionInicio)) . ' de ' . $meses[date('n', strtotime($sesionInicio)) - 1] . ' de ' . date('Y', strtotime($sesionInicio));

$aptitudes = [
    'Análisis lógico' => 85,
    'Creatividad' => 75,
    'Liderazgo' => 65,
    'Trabajo en equipo' => 80,
    'Comunicación' => 70
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Orientación Vocacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 30px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .report-header {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            padding: 50px 40px;
            text-align: center;
            position: relative;
        }
        .report-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .report-header .student-info {
            font-size: 1.1rem;
            font-weight: 500;
        }
        .report-body {
            padding: 40px;
        }
        
        /* Profile Summary Section */
        .profile-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
        }
        .profile-text {
            flex: 1;
            padding-right: 30px;
        }
        .profile-text h3 {
            color: #7c3aed;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .profile-text p {
            color: #4b5563;
            font-size: 1.05rem;
            line-height: 1.6;
        }
        .compatibility-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }
        .compatibility-circle .percentage {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .compatibility-circle .label {
            font-size: 0.9rem;
            text-align: center;
            line-height: 1.2;
        }
        
        /* Skills Section */
        .skills-section {
            margin-bottom: 40px;
        }
        .section-title {
            color: #3b82f6;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 3px solid #e5e7eb;
        }
        .skills-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .skill-card {
            background: #faf5ff;
            border-radius: 15px;
            padding: 25px;
        }
        .skill-card h4 {
            color: #7c3aed;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .skill-item {
            margin-bottom: 15px;
        }
        .skill-label {
            font-size: 0.95rem;
            color: #4b5563;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .skill-bar {
            height: 30px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .skill-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            transition: width 1s ease;
        }
        
        /* History Section */
        .history-section {
            margin-top: 40px;
        }
        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            color: #6f42c1;
            margin-bottom: 5px;
        }
        .accordion-item {
            border: 1px solid #e5e7eb;
            margin-bottom: 10px;
            border-radius: 10px !important;
            overflow: hidden;
        }
        .accordion-button {
            background: #f9fafb;
            color: #374151;
            font-weight: 500;
        }
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
        }
        .coming-soon {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .coming-soon i {
            font-size: 4rem;
            color: #6f42c1;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .skills-grid {
                grid-template-columns: 1fr;
            }
            .profile-summary {
                flex-direction: column;
                text-align: center;
            }
            .profile-text {
                padding-right: 0;
                margin-bottom: 20px;
            }
            .compatibility-circle {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>Reporte de Orientación Vocacional Personalizado</h1>
            <div class="student-info">
                Estudiante: <?php echo ucwords(htmlspecialchars($nombre)); ?> | 
                DNI: <?php echo htmlspecialchars($dni); ?> | 
                Fecha: <?php echo $fechaFormateada; ?>
            </div>
        </div>
        
        <div class="report-body">
            <?php if (count($conversaciones) > 0): ?>
            
            <!-- Profile Summary -->
            <div class="profile-summary">
                <div class="profile-text">
                    <h3>Según tu perfil, el campo con mayor afinidad es:</h3>
                    <p><strong><?php echo $areaPrincipal; ?></strong>, con enfoque en la resolución de problemas, análisis y desarrollo de soluciones innovadoras.</p>
                </div>
                <div class="compatibility-circle">
                    <div class="percentage"><?php echo $compatibilidadGeneral; ?>%</div>
                    <div class="label">Compatibilidad<br>General</div>
                </div>
            </div>

            <!-- Skills and Affinities -->
            <div class="skills-section">
                <h2 class="section-title">Perfil y áreas de compatibilidad</h2>
                <div class="skills-grid">
                    <!-- Aptitudes -->
                    <div class="skill-card">
                        <h4>Aptitudes y habilidades clave</h4>
                        <?php foreach ($aptitudes as $aptitud => $porcentaje): ?>
                        <div class="skill-item">
                            <div class="skill-label"><?php echo $aptitud; ?></div>
                            <div class="skill-bar">
                                <div class="skill-bar-fill" style="width: <?php echo $porcentaje; ?>%">
                                    <?php echo $porcentaje; ?>%
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Afinidad por área -->
                    <div class="skill-card">
                        <h4>Afinidad por área de estudio</h4>
                        <?php 
                        if (!empty($afinidadPorArea)) {
                            $maxAfinidad = max($afinidadPorArea);
                            foreach ($afinidadPorArea as $area => $valor): 
                                $porcentaje = round(($valor / $maxAfinidad) * 100);
                        ?>
                        <div class="skill-item">
                            <div class="skill-label"><?php echo $area; ?></div>
                            <div class="skill-bar">
                                <div class="skill-bar-fill" style="width: <?php echo $porcentaje; ?>%">
                                    <?php echo $porcentaje; ?>%
                                </div>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                        } else {
                            echo '<p class="text-muted small">Continúa conversando para identificar tus áreas de mayor afinidad.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Carreras Recomendadas -->
            <?php if (count($topCarreras) > 0): ?>
            <div class="mt-4">
                <h3 class="section-title"><i class="fa-solid fa-graduation-cap me-2"></i>Carreras Recomendadas</h3>
                <div class="row">
                    <?php foreach ($topCarreras as $carrera => $menciones): ?>
                    <div class="col-md-6 mb-3">
                        <div class="info-card" style="border-left: 4px solid #7c3aed;">
                            <h5 class="text-primary"><?php echo htmlspecialchars($carrera); ?></h5>
                            <small class="text-muted">Mencionada <?php echo $menciones; ?> vez(es) en tus conversaciones</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Historial de Conversaciones -->
            <div class="history-section mt-4">
                <h3 class="section-title"><i class="fa-solid fa-history me-2"></i>Historial de Conversaciones</h3>
                <div class="accordion" id="conversacionesAccordion">
                    <?php foreach (array_slice($conversaciones->toArray(), 0, 10) as $index => $conv): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                                <strong>Consulta <?php echo count($conversaciones) - $index; ?>:</strong>&nbsp;<?php echo substr(htmlspecialchars($conv->mensaje_usuario), 0, 60); ?>...
                                <small class="text-muted ms-auto me-3"><?php echo date('d/m/Y H:i', strtotime($conv->fecha_conversacion)); ?></small>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" data-bs-parent="#conversacionesAccordion">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong class="text-primary">Tu pregunta:</strong>
                                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($conv->mensaje_usuario)); ?></p>
                                </div>
                                <div>
                                    <strong class="text-success">Respuesta del asistente:</strong>
                                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($conv->respuesta_asistente)); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="coming-soon">
                <i class="fa-solid fa-chart-line"></i>
                <h3>Análisis de Conversaciones</h3>
                <p class="lead">El sistema está recopilando tus respuestas y preferencias.</p>
                <p>Al finalizar tu orientación, aquí verás:</p>
                <ul class="text-start" style="max-width: 600px; margin: 20px auto;">
                    <li>Carreras profesionales recomendadas según tus intereses</li>
                    <li>Análisis de tus habilidades y aptitudes</li>
                    <li>Sugerencias personalizadas de instituciones educativas</li>
                    <li>Plan de desarrollo profesional</li>
                </ul>
                
                <a href="/estudiantes.php" class="btn btn-lg mt-4" style="background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%); color: white; border: none; padding: 15px 40px; border-radius: 50px;">
                    <i class="fa-solid fa-comments me-2"></i>Continuar Orientación
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>