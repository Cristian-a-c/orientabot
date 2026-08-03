<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$reporte = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dni'])) {
    $dni = $_POST['dni'];
    
    // Buscar conversaciones del DNI
    $conversaciones = DB::table('conversaciones')
        ->where('estudiante_dni', $dni)
        ->orderBy('fecha_conversacion', 'desc')
        ->get();
    
    if (count($conversaciones) > 0) {
        $reporte = [
            'nombre' => $conversaciones[0]->estudiante_nombre,
            'dni' => $dni,
            'conversaciones' => $conversaciones,
            'total' => count($conversaciones)
        ];
        
        // Analizar carreras
        $carrerasMencionadas = [];
        foreach ($conversaciones as $conv) {
            $texto = strtolower($conv->respuesta_asistente);
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
        $reporte['carreras'] = array_slice($carrerasMencionadas, 0, 5, true);
    } else {
        $error = "No se encontraron reportes para el DNI: $dni";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto 30px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .report-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .report-header {
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .report-body {
            padding: 40px;
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
    </style>
</head>
<body>
    <div class="search-container">
        <div class="text-center mb-4">
            <i class="fa-solid fa-search fa-3x text-primary mb-3"></i>
            <h2>Recuperar Reporte de Orientación</h2>
            <p class="text-muted">Ingresa tu DNI para ver tu reporte anterior</p>
        </div>
        
        <form method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control form-control-lg" id="dniInput" name="dni" 
                       placeholder="DNI" required pattern="[0-9]{8}" maxlength="8">
                <label for="dniInput"><i class="fa-solid fa-id-card me-2"></i>DNI (8 dígitos)</label>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%); color: white; border: none; padding: 15px; border-radius: 10px;">
                    <i class="fa-solid fa-file-lines me-2"></i>Buscar Reporte
                </button>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <a href="/inicio.php" class="text-decoration-none">
                <i class="fa-solid fa-arrow-left me-2"></i>Volver al inicio
            </a>
        </div>
    </div>

    <?php if ($error): ?>
    <div class="report-container">
        <div class="alert alert-warning m-4">
            <i class="fa-solid fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($reporte): ?>
    <div class="report-container">
        <div class="report-header">
            <i class="fa-solid fa-file-lines fa-3x mb-3"></i>
            <h1>Reporte de Orientación Vocacional</h1>
            <p class="mb-0">Resultados del Sistema de Análisis</p>
        </div>
        
        <div class="report-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-label"><i class="fa-solid fa-user me-2"></i>Estudiante</div>
                        <div><?php echo htmlspecialchars($reporte['nombre']); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-label"><i class="fa-solid fa-id-card me-2"></i>DNI</div>
                        <div><?php echo htmlspecialchars($reporte['dni']); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-label"><i class="fa-solid fa-comments me-2"></i>Total de Consultas</div>
                <div class="fs-4 fw-bold text-primary"><?php echo $reporte['total']; ?> conversaciones</div>
            </div>

            <?php if (count($reporte['carreras']) > 0): ?>
            <div class="mt-4">
                <h3 class="mb-3"><i class="fa-solid fa-graduation-cap me-2"></i>Carreras Recomendadas</h3>
                <div class="row">
                    <?php foreach ($reporte['carreras'] as $carrera => $menciones): ?>
                    <div class="col-md-6 mb-3">
                        <div class="info-card" style="border-left: 4px solid #6f42c1;">
                            <h5 class="text-primary"><?php echo htmlspecialchars($carrera); ?></h5>
                            <small class="text-muted">Mencionada <?php echo $menciones; ?> vez(es)</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <h3 class="mb-3"><i class="fa-solid fa-history me-2"></i>Últimas 10 Conversaciones</h3>
                <div class="accordion" id="conversacionesAccordion">
                    <?php foreach (array_slice($reporte['conversaciones']->toArray(), 0, 10) as $index => $conv): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                                <strong>Consulta:</strong>&nbsp;<?php echo substr(htmlspecialchars($conv->mensaje_usuario), 0, 60); ?>...
                                <small class="text-muted ms-auto me-3"><?php echo date('d/m/Y H:i', strtotime($conv->fecha_conversacion)); ?></small>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong class="text-primary">Pregunta:</strong>
                                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($conv->mensaje_usuario)); ?></p>
                                </div>
                                <div>
                                    <strong class="text-success">Respuesta:</strong>
                                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($conv->respuesta_asistente)); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>