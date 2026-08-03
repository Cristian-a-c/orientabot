<?php
// Archivo de página de inicio para OrientaBot
require __DIR__.'/../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Vocation - OrientaBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
/* --- Global Styles --- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fc;
    overflow-x: hidden;
    height: 100vh;
}

/* --- Navbar --- */
.custom-navbar {
    background: linear-gradient(90deg, #4a6cf7 0%, #6f42c1 100%);
    padding: 10px 20px;
}

.btn-custom {
    background-color: rgba(255, 255, 255, 0.2);
    color: white !important;
    border-radius: 8px;
    padding: 5px 20px !important;
    font-weight: 500;
    margin-right: 5px;
    border: 1px solid rgba(255,255,255,0.3);
    transition: all 0.3s;
}

.btn-custom:hover, .btn-custom.active {
    background-color: rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 10px rgba(255,255,255,0.2);
}

.btn-darker {
    background-color: rgba(0, 0, 0, 0.1);
}

/* --- Main Layout & Background --- */
.main-container {
    height: calc(100vh - 70px);
    position: relative;
    overflow: hidden;
}

/* Circular Background Effect */
.bg-decoration {
    position: absolute;
    width: 120vh;
    height: 120vh;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(111, 66, 193, 0.05) 0%, rgba(111, 66, 193, 0.15) 50%, transparent 70%);
    top: 50%;
    left: 30%;
    transform: translate(-50%, -50%);
    z-index: -1;
}

/* --- Typography Left Side --- */
.badge-title {
    background: linear-gradient(90deg, #6f42c1 0%, #5a32a3 100%);
    color: #00ffcc;
    display: inline-block;
    padding: 10px 20px;
    font-size: 1.5rem;
    font-weight: bold;
    border-radius: 5px;
    margin-bottom: 20px;
    box-shadow: 4px 4px 0px rgba(0,0,0,0.1);
}

.main-title {
    color: #8e44ad;
    font-weight: 800;
    line-height: 0.9;
    letter-spacing: -2px;
}

.sub-title {
    color: #9b59b6;
    font-weight: 700;
    margin-top: 10px;
    letter-spacing: 1px;
}

/* Floating Robot Icon Main */
.robot-circle-container {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
    z-index: 0;
}

.robot-circle {
    width: 200px;
    height: 200px;
    background-color: #531dab;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 5rem;
    position: relative;
    box-shadow: 0 10px 30px rgba(83, 29, 171, 0.4);
}

.robot-circle .bubble-icon {
    position: absolute;
    bottom: 40px;
    right: 50px;
    font-size: 2rem;
    color: white;
}

/* --- Chat Interface --- */
.chat-card {
    width: 380px;
    height: 600px;
    border: none;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    overflow: hidden;
    background-color: #f0f2f5;
}

.chat-header {
    background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
    color: white;
}

.bot-avatar-sm {
    width: 40px;
    height: 40px;
    background: white;
    color: #6f42c1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.chat-body {
    flex: 1;
    overflow-y: auto;
    background-color: #eef1f7;
    display: flex;
    flex-direction: column;
}

/* Messages */
.msg-bubble {
    max-width: 80%;
    padding: 12px 16px;
    border-radius: 15px;
    font-size: 0.9rem;
    position: relative;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.user-msg {
    background-color: #e4e6eb;
    color: #050505;
    border-bottom-right-radius: 2px;
}

.bot-msg {
    background-color: #4527a0;
    color: white;
    border-bottom-left-radius: 2px;
}

.bot-avatar-xs {
    width: 30px;
    height: 30px;
    background: #6f42c1;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    margin-top: auto;
}

.user-avatar-xs {
    display: flex;
    align-items: center;
    justify-content: center;
}

.small-time {
    font-size: 0.7rem;
    margin-top: -10px;
}

.msg-actions {
    color: #6f42c1;
    font-size: 0.8rem;
    opacity: 0.7;
}

.msg-actions i {
    margin-left: 5px;
    cursor: pointer;
}

/* Footer */
.chat-footer {
    background: white;
    border-top: 1px solid #ddd;
}

.quick-replies .badge {
    cursor: pointer;
    font-weight: normal;
    padding: 8px 10px;
    text-decoration: none;
}

.input-group {
    background-color: #f0f2f5;
    border-radius: 20px;
    padding: 5px;
}

/* --- Login Modal Styles --- */
.form-floating > .form-control {
    border: 1px solid #e0e0e0;
    background-color: #f8f9fa;
}

.form-floating > .form-control:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
}

.form-floating > label {
    color: #999;
}

.modal-content {
    animation: slideInUp 0.3s ease-out;
}

@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.btn-start-chat {
    background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
    color: white;
    border: none;
    padding: 15px 40px;
    font-size: 1.2rem;
    font-weight: bold;
    border-radius: 50px;
    box-shadow: 0 5px 20px rgba(111, 66, 193, 0.4);
    transition: all 0.3s;
    margin-top: 30px;
}

.btn-start-chat:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(111, 66, 193, 0.6);
}

/* --- Login Card Styles --- */
.login-card {
    width: 100%;
    max-width: 380px;
    border: none;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    overflow: hidden;
    background-color: white;
}

.login-card .card-header {
    background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
    color: white;
    padding: 20px 15px 15px;
    border: none;
}

.user-icon-container {
    width: 60px;
    height: 60px;
    background: white;
    color: #6f42c1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.login-card .form-floating > .form-control {
    border: 2px solid #e0e0e0;
    background-color: #f8f9fa;
    border-radius: 10px;
    height: 55px;
}

.login-card .form-floating > .form-control:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
    background-color: white;
}

.login-card .form-floating > label {
    color: #666;
    padding-left: 15px;
}
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link btn-custom" href="#">Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-custom" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Ayuda</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 text-white">
                    <i class="fa-regular fa-user fa-lg"></i>
                    <i class="fa-solid fa-gear fa-lg"></i>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid main-container d-flex align-items-center justify-content-center">
        <div class="bg-decoration"></div>

        <div class="row w-100 align-items-center">
            
            <div class="col-lg-6 text-section ps-5">
                <div class="badge-title">
                    DESCUBRE TU VOCACIÓN
                </div>
                <h1 class="display-1 fw-bold main-title">CHAT<br>VOCATION</h1>
                <h3 class="sub-title">ORIENTABOT</h3>

                <div class="robot-circle-container">
                    <div class="robot-circle">
                        <i class="fa-solid fa-robot"></i>
                        <i class="fa-solid fa-comment-dots bubble-icon"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                <div class="login-card card">
                    <div class="card-header text-center">
                        <div class="user-icon-container">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <h5 class="mt-2 mb-1">Bienvenido a OrientaBot</h5>
                        <p class="text-muted small mb-0">Ingresa tus datos para comenzar</p>
                    </div>
                    
                    <div class="card-body p-3">
                        <form id="studentForm" action="/estudiantes.php" method="POST">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="nombreInput" name="nombre" placeholder="Nombre completo" required minlength="3">
                                <label for="nombreInput"><i class="fa-solid fa-user me-2"></i>Nombre Completo</label>
                            </div>
                            
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="dniInput" name="dni" placeholder="DNI" required pattern="[0-9]{8}" maxlength="8">
                                <label for="dniInput"><i class="fa-solid fa-id-card me-2"></i>DNI</label>
                            </div>

                            <div class="alert alert-info small mb-2 py-2" role="alert">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                Tus datos serán utilizados para generar un reporte personalizado de orientación vocacional al finalizar.
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-start-chat w-100">
                                    <i class="fa-solid fa-comments me-2"></i>Comenzar Orientación
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p class="small text-muted mb-0">
                                <i class="fa-solid fa-shield-halved me-1"></i>
                                Tus datos están protegidos y serán usados solo para tu orientación vocacional.
                            </p>
                            <p class="small mt-2">
                                ¿Ya tienes un reporte? <a href="/recuperar-reporte.php" class="fw-bold text-decoration-none" style="color: #4a6cf7;">Recupéralo aquí</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Ayuda -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-circle-question me-2"></i>Guía de Orientación Vocacional</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Cómo usar el asistente -->
                    <div class="mb-4">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fa-solid fa-circle-question me-2"></i>¿Cómo usar el asistente?
                        </h5>
                        <p class="text-muted">
                            Este asistente está diseñado para ayudarte a descubrir tu vocación profesional a través de una conversación natural. Puedes preguntarle sobre tus intereses, habilidades y las carreras que mejor se adaptan a ti.
                        </p>
                    </div>

                    <!-- Temas que puedes consultar -->
                    <div class="mb-4">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fa-solid fa-list me-2"></i>Temas que puedes consultar
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fa-solid fa-check-circle text-success me-2"></i>
                                <strong>Exploración de intereses:</strong> Identifica qué actividades te apasionan
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-check-circle text-success me-2"></i>
                                <strong>Habilidades y aptitudes:</strong> Descubre tus fortalezas naturales
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-check-circle text-success me-2"></i>
                                <strong>Carreras profesionales:</strong> Conoce opciones de estudio y trabajo
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-check-circle text-success me-2"></i>
                                <strong>Campo laboral:</strong> Entiende las oportunidades de cada área
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-check-circle text-success me-2"></i>
                                <strong>Requisitos académicos:</strong> Averigua qué necesitas estudiar
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-check-circle text-success me-2"></i>
                                <strong>Compatibilidad personal:</strong> Encuentra carreras que se ajusten a ti
                            </li>
                        </ul>
                    </div>

                    <!-- Ejemplos de preguntas -->
                    <div class="mb-4">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fa-solid fa-message me-2"></i>Ejemplos de preguntas
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold small">SOBRE CARRERAS:</h6>
                                        <p class="small text-muted mb-0 fst-italic">"¿Qué carreras existen relacionadas con tecnología?"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold small">SOBRE INTERESES:</h6>
                                        <p class="small text-muted mb-0 fst-italic">"Me gusta ayudar a las personas, ¿qué puedo estudiar?"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold small">COMPARACIÓN:</h6>
                                        <p class="small text-muted mb-0 fst-italic">"¿Cuál es la diferencia entre Ingeniería de Sistemas e Ingeniería de Software?"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold small">SOBRE HABILIDADES:</h6>
                                        <p class="small text-muted mb-0 fst-italic">"Soy bueno en matemáticas, ¿qué carreras me recomiendas?"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold small">CAMPO LABORAL:</h6>
                                        <p class="small text-muted mb-0 fst-italic">"¿Qué hace un profesional de Medicina?"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold small">ÁREAS CREATIVAS:</h6>
                                        <p class="small text-muted mb-0 fst-italic">"Me interesa el arte y la creatividad, ¿qué opciones tengo?"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consejos -->
                    <div class="mb-3">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fa-solid fa-check-circle me-2"></i>Consejos para mejores resultados
                        </h5>
                        <div class="alert alert-info mb-2">
                            <strong><i class="fa-solid fa-pen me-2"></i>Sé específico:</strong>
                            <p class="small mb-0 mt-1">Cuanto más detalles des sobre tus intereses y habilidades, mejores recomendaciones recibirás.</p>
                        </div>
                        <div class="alert alert-warning mb-2">
                            <strong><i class="fa-solid fa-arrows-rotate me-2"></i>Explora diferentes temas:</strong>
                            <p class="small mb-0 mt-1">No te limites a una sola área, pregunta sobre varias opciones para ampliar tu perspectiva.</p>
                        </div>
                        <div class="alert alert-success mb-2">
                            <strong><i class="fa-solid fa-file-lines me-2"></i>Revisa tu reporte:</strong>
                            <p class="small mb-0 mt-1">Al finalizar, consulta tu reporte personalizado con todas las recomendaciones.</p>
                        </div>
                        <div class="alert alert-secondary mb-0">
                            <strong><i class="fa-solid fa-user-doctor me-2"></i>Consulta con profesionales:</strong>
                            <p class="small mb-0 mt-1">Este sistema es una herramienta de apoyo. Complementa con asesoría profesional.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" style="background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%); border: none;" data-bs-dismiss="modal" onclick="document.getElementById('nombreInput').focus()">
                        <i class="fa-solid fa-rocket me-2"></i>Comenzar Ahora
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                
                <div class="modal-header text-white border-0 d-flex flex-column align-items-center justify-content-center pt-4" 
                    style="background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px; color: #6f42c1;">
                        <i class="fa-solid fa-user-astronaut fa-2x"></i>
                    </div>
                    <h5 class="modal-title fw-bold">Bienvenido de nuevo</h5>
                    <p class="small opacity-75">Ingresa a tu cuenta de OrientaBot</p>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="loginForm">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-4" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Correo Electrónico</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-4" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Contraseña</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="rememberCheck">
                                <label class="form-check-label small text-muted" for="rememberCheck">Recordarme</label>
                            </div>
                            <a href="#" class="small text-decoration-none" style="color: #6f42c1;">¿Olvidaste tu contraseña?</a>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold" 
                                    style="background: #6f42c1; border: none;">
                                INGRESAR
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="small text-muted">O ingresa con</p>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-outline-light text-dark border shadow-sm rounded-circle" style="width: 40px; height: 40px;"><i class="fa-brands fa-google"></i></button>
                            <button class="btn btn-outline-light text-dark border shadow-sm rounded-circle" style="width: 40px; height: 40px;"><i class="fa-brands fa-facebook-f text-primary"></i></button>
                            <button class="btn btn-outline-light text-dark border shadow-sm rounded-circle" style="width: 40px; height: 40px;"><i class="fa-brands fa-linkedin-in text-info"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light justify-content-center border-0 py-3">
                    <span class="small text-muted">¿No tienes cuenta? <a href="#" class="fw-bold text-decoration-none" style="color: #4a6cf7;">Regístrate aquí</a></span>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Redirigir al chat cuando se escriba en el input
        document.getElementById('messageInput').addEventListener('click', function() {
            window.location.href = "/estudiantes.php";
        });
    </script>
</body>
</html>
