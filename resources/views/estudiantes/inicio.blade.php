<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Vocation - OrientaBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('estudiantes/css/styles_inicio.css') }}">
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
                        <a class="nav-link btn-custom active" href="{{ url('/estudiantes') }}">ChatBot</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-custom" href="#">Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-custom" href="#">Ayuda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-custom btn-darker" href="#" data-bs-toggle="modal" data-bs-target="#loginModal" id="loginBtnNavbar">Login</a>
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
            
            <div class="col-lg-7 col-md-6 text-section ps-5">
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

            <div class="col-lg-5 col-md-6 d-flex justify-content-center">
                <div class="chat-card card">
                    <div class="chat-header d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bot-avatar-sm">
                                <i class="fa-solid fa-robot"></i>
                            </div>
                            <div class="bot-info text-white">
                                <h6 class="m-0 fw-bold">OrientaBot</h6>
                                <small class="status-dot"><i class="fa-solid fa-circle text-success" style="font-size: 8px;"></i> Online</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-minus text-white" style="cursor: pointer;"></i>
                    </div>

                    <div class="chat-body p-3" id="chatBody">
                        <div class="text-center text-muted mb-3" style="font-size: 0.8rem;">Hoy</div>
                        
                        <div class="d-flex justify-content-end mb-3">
                            <div class="msg-bubble user-msg">
                                Hola,
                            </div>
                        </div>
                        <div class="text-end text-muted mb-3 small-time">7:20 <i class="fa-solid fa-check"></i></div>

                        <div class="d-flex justify-content-start mb-3">
                            <div class="bot-avatar-xs me-2"><i class="fa-solid fa-robot"></i></div>
                            <div class="msg-bubble bot-msg">
                                Hola, soy OrientaBot, tu asistente de orientación profesional con IA. Estoy listo para ayudarte a evaluar tus habilidades y explorar opciones de carrera.
                            </div>
                        </div>
                        <div class="msg-actions text-end mb-2">
                           <i class="fa-regular fa-copy"></i> <i class="fa-regular fa-thumbs-up"></i> <i class="fa-regular fa-thumbs-down"></i>
                        </div>
                        
                         <div class="d-flex flex-column align-items-end mb-3">
                            <div class="msg-bubble user-msg text-start">
                                mi nombre es juan y tengo habilidades en números
                            </div>
                             <div class="user-avatar-xs mt-1">
                                <img src="https://i.pravatar.cc/150?img=11" alt="User" class="rounded-circle" width="25">
                            </div>
                        </div>
                        <div class="text-end text-muted mb-3 small-time">7:20 <i class="fa-solid fa-check-double text-primary"></i></div>

                    </div>

                    <div class="chat-footer p-2">
                        <div class="quick-replies d-flex gap-2 mb-2">
                            <a href="{{ url('/estudiantes/chat') }}" class="badge bg-light text-dark border text-decoration-none">👋 Qué es OrientaBot?</a>
                            <a href="{{ url('/estudiantes/chat') }}" class="badge bg-light text-dark border text-decoration-none">💰 Precios</a>
                            <a href="{{ url('/estudiantes/chat') }}" class="badge bg-light text-dark border text-decoration-none">📄 FAQs</a>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control border-0 bg-light" placeholder="Escribe tu mensaje aquí..." id="messageInput" readonly>
                            <a href="{{ url('/estudiantes/chat') }}" class="btn btn-link text-primary" type="button">
                                <i class="fa-regular fa-paper-plane"></i>
                            </a>
                        </div>
                    </div>
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
        // Redirigir al chat cuando se escriba en el input o se haga clic en enviar
        document.getElementById('messageInput').addEventListener('click', function() {
            window.location.href = "{{ url('/estudiantes/chat') }}";
        });
    </script>
</body>
</html>
