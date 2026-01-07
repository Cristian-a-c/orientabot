<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Orientación Vocacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 10px rgba(74, 108, 247, 0.3);
        }

        .header-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.125rem;
        }

        .header-text p {
            font-size: 0.875rem;
            color: #6c757d;
            margin: 0;
        }

        .header-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .header-btn {
            background: white;
            border: 2px solid #e9ecef;
            color: #6c757d;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-btn:hover {
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(74, 108, 247, 0.3);
        }

        .header-btn i {
            font-size: 1rem;
        }

        /* Chat Container */
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 1.5rem;
            overflow: hidden;
        }

        .messages-container {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            overflow-y: auto;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .message-group {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .message-group.user {
            justify-content: flex-end;
        }

        .message-group.assistant {
            justify-content: flex-start;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .assistant-avatar {
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            color: white;
        }

        .user-avatar {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
        }

        .message {
            max-width: 70%;
            padding: 1rem 1.25rem;
            border-radius: 16px;
            font-size: 0.9375rem;
            line-height: 1.6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .assistant-message {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #212529;
        }

        .user-message {
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            color: white;
        }

        .message p {
            margin: 0;
            white-space: pre-wrap;
        }

        /* Typing Indicator */
        .typing-dots {
            display: flex;
            gap: 0.375rem;
            padding: 0.5rem 0;
        }

        .dot {
            width: 10px;
            height: 10px;
            background: #6c757d;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out;
        }

        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        /* Suggestions */
        .suggestions {
            padding: 0 0 1rem;
        }

        .suggestions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.75rem;
        }

        .suggestion-btn {
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid #e9ecef;
            border-radius: 12px;
            color: #495057;
            font-size: 0.875rem;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .suggestion-btn:hover {
            background: white;
            border-color: #6f42c1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.2);
        }

        /* Input Area */
        .input-area {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .input-container {
            display: flex;
            align-items: flex-end;
            gap: 0.75rem;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            padding: 0.75rem 1rem;
            transition: border-color 0.3s;
        }

        .input-container:focus-within {
            border-color: #6f42c1;
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
        }

        .icon-btn {
            background: transparent;
            border: none;
            color: #6c757d;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .icon-btn:hover {
            background: #f8f9fa;
            color: #6f42c1;
        }

        textarea {
            flex: 1;
            background: transparent;
            border: none;
            color: #212529;
            outline: none;
            resize: none;
            font-size: 0.9375rem;
            font-family: inherit;
            max-height: 128px;
            overflow-y: auto;
        }

        textarea::placeholder {
            color: #adb5bd;
        }

        .send-btn {
            background: #e9ecef;
            border: none;
            color: #6c757d;
            padding: 0.625rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .send-btn.active {
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
        }

        .send-btn.active:hover {
            transform: scale(1.05);
        }

        .disclaimer {
            text-align: center;
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 0.75rem;
        }

        /* Help Panel */
        .help-panel {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .help-panel.active {
            right: 0;
        }

        .help-content {
            padding: 2rem;
        }

        .help-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .help-header h2 {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #4a6cf7 0%, #6f42c1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .close-help-btn {
            background: #f8f9fa;
            border: none;
            color: #6c757d;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-help-btn:hover {
            background: #e9ecef;
            color: #212529;
        }

        .help-section {
            margin-bottom: 2rem;
        }

        .help-section h3 {
            font-size: 1.125rem;
            color: #212529;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .help-section p {
            font-size: 0.9375rem;
            color: #495057;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .help-section ul {
            list-style: none;
            padding: 0;
        }

        .help-section li {
            font-size: 0.9375rem;
            color: #495057;
            padding: 0.75rem 0;
            padding-left: 1.75rem;
            position: relative;
            line-height: 1.6;
        }

        .help-section li::before {
            content: "✓";
            position: absolute;
            left: 0.5rem;
            color: #6f42c1;
            font-weight: bold;
            font-size: 1.125rem;
        }

        .help-section li strong {
            color: #212529;
        }

        .examples-grid {
            display: grid;
            gap: 0.875rem;
        }

        .example-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .example-card:hover {
            background: white;
            border-color: #6f42c1;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.15);
        }

        .example-card strong {
            display: block;
            color: #6f42c1;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .example-card p {
            margin: 0;
            font-size: 0.9375rem;
            color: #495057;
            font-style: italic;
        }

        .tips-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tip-item {
            display: flex;
            gap: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.25rem;
            border-left: 4px solid #6f42c1;
        }

        .tip-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .tip-item strong {
            display: block;
            color: #212529;
            margin-bottom: 0.375rem;
            font-size: 0.9375rem;
        }

        .tip-item p {
            margin: 0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Scrollbar */
        .messages-container::-webkit-scrollbar,
        .help-panel::-webkit-scrollbar {
            width: 8px;
        }

        .messages-container::-webkit-scrollbar-track,
        .help-panel::-webkit-scrollbar-track {
            background: transparent;
        }

        .messages-container::-webkit-scrollbar-thumb,
        .help-panel::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 4px;
        }

        .messages-container::-webkit-scrollbar-thumb:hover,
        .help-panel::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chat-container {
                padding: 1rem;
            }

            .suggestions-grid {
                grid-template-columns: 1fr;
            }

            .header-text h1 {
                font-size: 1.125rem;
            }

            .header-text p {
                font-size: 0.75rem;
            }

            .message {
                max-width: 85%;
            }

            .help-panel {
                width: 100%;
                right: -100%;
            }

            .help-panel.active {
                right: 0;
            }

            .header-buttons {
                gap: 0.25rem;
            }

            .header-btn span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo">
                    <i class="fa-solid fa-graduation-cap fa-lg"></i>
                </div>
                <div class="header-text">
                    <h1>Orientación Vocacional</h1>
                    <p>Bienvenido(a), {{ ucwords(session('estudiante_nombre', 'Estudiante')) }}</p>
                </div>
            </div>
            <div class="header-buttons">
                <button class="header-btn" onclick="toggleHelpPanel()" title="Ver guía de ayuda">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>Ayuda</span>
                </button>
                <button class="header-btn" onclick="window.location.href='/reportes.php'" title="Ver mi reporte">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Mi Reporte</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Help Panel -->
    <div class="help-panel active" id="helpPanel">
        <div class="help-content">
            <div class="help-header">
                <h2>📚 Guía de Orientación Vocacional</h2>
                <button class="close-help-btn" onclick="toggleHelpPanel()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="help-section">
                <h3>🎯 ¿Cómo usar el asistente?</h3>
                <p>Este asistente está diseñado para ayudarte a descubrir tu vocación profesional a través de una conversación natural. Puedes preguntarle sobre:</p>
            </div>

            <div class="help-section">
                <h3>💡 Temas que puedes consultar</h3>
                <ul>
                    <li><strong>Exploración de intereses:</strong> Identifica qué actividades te apasionan</li>
                    <li><strong>Habilidades y aptitudes:</strong> Descubre tus fortalezas naturales</li>
                    <li><strong>Carreras profesionales:</strong> Conoce opciones de estudio y trabajo</li>
                    <li><strong>Campo laboral:</strong> Entiende las oportunidades de cada área</li>
                    <li><strong>Requisitos académicos:</strong> Averigua qué necesitas estudiar</li>
                    <li><strong>Compatibilidad personal:</strong> Encuentra carreras que se ajusten a ti</li>
                </ul>
            </div>

            <div class="help-section">
                <h3>📝 Ejemplos de preguntas</h3>
                <div class="examples-grid">
                    <div class="example-card" onclick="setSuggestion('¿Qué carreras existen relacionadas con tecnología?')">
                        <strong>Sobre carreras:</strong>
                        <p>"¿Qué carreras existen relacionadas con tecnología?"</p>
                    </div>
                    <div class="example-card" onclick="setSuggestion('Me gusta ayudar a las personas, ¿qué puedo estudiar?')">
                        <strong>Sobre intereses:</strong>
                        <p>"Me gusta ayudar a las personas, ¿qué puedo estudiar?"</p>
                    </div>
                    <div class="example-card" onclick="setSuggestion('¿Cuál es la diferencia entre Ingeniería de Sistemas e Ingeniería de Software?')">
                        <strong>Comparación:</strong>
                        <p>"¿Cuál es la diferencia entre Ingeniería de Sistemas e Ingeniería de Software?"</p>
                    </div>
                    <div class="example-card" onclick="setSuggestion('Soy bueno en matemáticas y me gusta resolver problemas, ¿qué carreras me recomiendas?')">
                        <strong>Sobre habilidades:</strong>
                        <p>"Soy bueno en matemáticas y me gusta resolver problemas, ¿qué carreras me recomiendas?"</p>
                    </div>
                    <div class="example-card" onclick="setSuggestion('¿Qué hace un profesional de Medicina?')">
                        <strong>Campo laboral:</strong>
                        <p>"¿Qué hace un profesional de Medicina?"</p>
                    </div>
                    <div class="example-card" onclick="setSuggestion('Me interesa el arte y la creatividad, ¿qué opciones tengo?')">
                        <strong>Áreas creativas:</strong>
                        <p>"Me interesa el arte y la creatividad, ¿qué opciones tengo?"</p>
                    </div>
                </div>
            </div>

            <div class="help-section">
                <h3>✅ Consejos para mejores resultados</h3>
                <div class="tips-list">
                    <div class="tip-item">
                        <span class="tip-icon">💬</span>
                        <div>
                            <strong>Sé específico:</strong>
                            <p>Cuanto más detalles des sobre tus intereses y habilidades, mejores recomendaciones recibirás.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <span class="tip-icon">🔄</span>
                        <div>
                            <strong>Explora diferentes temas:</strong>
                            <p>No te limites a una sola área, pregunta sobre varias opciones para ampliar tu perspectiva.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <span class="tip-icon">📊</span>
                        <div>
                            <strong>Revisa tu reporte:</strong>
                            <p>Al finalizar, consulta tu reporte personalizado con todas las recomendaciones.</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <span class="tip-icon">🎓</span>
                        <div>
                            <strong>Consulta con profesionales:</strong>
                            <p>Este sistema es una herramienta de apoyo. Complementa con asesoría profesional.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="chat-container">
        <!-- Messages Area -->
        <div class="messages-container" id="messagesContainer">
            <!-- Initial assistant message -->
            <div class="message-group assistant">
                <div class="avatar assistant-avatar">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="message assistant-message">
                    <p>¡Hola {{ ucwords(session('estudiante_nombre', 'Estudiante')) }}! 👋 Soy tu asistente de orientación vocacional. Estoy aquí para ayudarte a descubrir tus intereses, habilidades y las carreras que mejor se adaptan a ti. ¿Qué te gustaría saber hoy?</p>
                </div>
            </div>
        </div>

        <!-- Suggestions -->
        <div class="suggestions" id="suggestions">
            <div class="suggestions-grid">
                <button class="suggestion-btn" onclick="setSuggestion('¿Qué carrera es mejor para mí?')">
                    <i class="fa-solid fa-lightbulb me-2"></i>¿Qué carrera es mejor para mí?
                </button>
                <button class="suggestion-btn" onclick="setSuggestion('Quiero conocer mis habilidades')">
                    <i class="fa-solid fa-star me-2"></i>Quiero conocer mis habilidades
                </button>
                <button class="suggestion-btn" onclick="setSuggestion('Opciones en tecnología')">
                    <i class="fa-solid fa-laptop-code me-2"></i>Opciones en tecnología
                </button>
                <button class="suggestion-btn" onclick="setSuggestion('Carreras relacionadas con arte')">
                    <i class="fa-solid fa-palette me-2"></i>Carreras relacionadas con arte
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="input-area">
            <div class="input-container">
                <button class="icon-btn" title="Adjuntar archivo">
                    <i class="fa-solid fa-paperclip"></i>
                </button>
                <textarea 
                    id="messageInput" 
                    placeholder="Escribe tu pregunta aquí..."
                    rows="1"></textarea>
                <button class="icon-btn" title="Mensaje de voz">
                    <i class="fa-solid fa-microphone"></i>
                </button>
                <button class="send-btn" id="sendBtn" onclick="sendMessage()" title="Enviar mensaje">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
            <p class="disclaimer">
                <i class="fa-solid fa-info-circle me-1"></i>
                Este sistema es una herramienta de apoyo. Consulta con un orientador profesional para decisiones importantes.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const suggestions = document.getElementById('suggestions');
        let messageCount = 1;

        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 128) + 'px';
            updateSendButton();
        });

        // Send on Enter
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        function updateSendButton() {
            if (messageInput.value.trim()) {
                sendBtn.classList.add('active');
            } else {
                sendBtn.classList.remove('active');
            }
        }

        function setSuggestion(text) {
            messageInput.value = text;
            messageInput.focus();
            updateSendButton();
            // Cerrar panel de ayuda si está abierto
            document.getElementById('helpPanel').classList.remove('active');
        }

        async function sendMessage() {
            const text = messageInput.value.trim();
            if (!text) return;

            // Hide suggestions after first message
            if (messageCount === 1) {
                suggestions.style.display = 'none';
            }
            messageCount++;

            // Add user message
            addMessage(text, 'user');
            messageInput.value = '';
            messageInput.style.height = 'auto';
            updateSendButton();

            // Show typing indicator
            showTypingIndicator();

            try {
                // Enviar mensaje al backend
                const response = await fetch('/api/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: text
                    })
                });

                const data = await response.json();

                hideTypingIndicator();

                if (data.success) {
                    addMessage(data.message, 'assistant');
                } else {
                    addMessage('Lo siento, hubo un error al procesar tu mensaje. ' + (data.message || 'Por favor intenta nuevamente.'), 'assistant');
                }
            } catch (error) {
                hideTypingIndicator();
                console.error('Error:', error);
                addMessage('Lo siento, no pude conectarme con el servidor. Por favor verifica tu conexión e intenta nuevamente.', 'assistant');
            }
        }

        function addMessage(text, role) {
            const messageGroup = document.createElement('div');
            messageGroup.className = `message-group ${role}`;

            const avatar = document.createElement('div');
            avatar.className = `avatar ${role}-avatar`;
            
            if (role === 'assistant') {
                avatar.innerHTML = '<i class="fa-solid fa-robot"></i>';
            } else {
                avatar.textContent = 'Tú';
            }

            const message = document.createElement('div');
            message.className = `message ${role}-message`;
            
            // Convertir saltos de línea a <br> y preservar formato
            const formattedText = text.replace(/\n/g, '<br>');
            message.innerHTML = `<p>${formattedText}</p>`;

            if (role === 'assistant') {
                messageGroup.appendChild(avatar);
                messageGroup.appendChild(message);
            } else {
                messageGroup.appendChild(message);
                messageGroup.appendChild(avatar);
            }

            messagesContainer.appendChild(messageGroup);
            scrollToBottom();
        }

        function showTypingIndicator() {
            const indicator = document.createElement('div');
            indicator.className = 'message-group assistant typing-indicator';
            indicator.id = 'typingIndicator';
            indicator.innerHTML = `
                <div class="avatar assistant-avatar">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="message assistant-message">
                    <div class="typing-dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(indicator);
            scrollToBottom();
        }

        function hideTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function toggleHelpPanel() {
            const helpPanel = document.getElementById('helpPanel');
            helpPanel.classList.toggle('active');
        }
    </script>
</body>
</html>