<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Orientación Vocacional</title>
    <link rel="stylesheet" href="/estudiantes/css/styles_interaction.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="header-text">
                    <h1>Orientación Vocacional</h1>
                    <p>Tu guía personalizada de carreras</p>
                </div>
            </div>
            <button class="new-chat-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
        </div>
    </header>

    <!-- Messages Area -->
    <main class="messages-container" id="messagesContainer">
        <div class="messages-wrapper">
            <!-- Initial assistant message -->
            <div class="message-group assistant">
                <div class="avatar assistant-avatar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="message assistant-message">
                    <p>¡Hola! Soy tu asistente de orientación vocacional. Estoy aquí para ayudarte a descubrir tus intereses, habilidades y las carreras que mejor se adaptan a ti. ¿Qué te gustaría saber hoy?</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Suggestions -->
    <div class="suggestions" id="suggestions">
        <div class="suggestions-grid">
            <button class="suggestion-btn" onclick="setSuggestion('¿Qué carrera es mejor para mí?')">
                ¿Qué carrera es mejor para mí?
            </button>
            <button class="suggestion-btn" onclick="setSuggestion('Quiero conocer mis habilidades')">
                Quiero conocer mis habilidades
            </button>
            <button class="suggestion-btn" onclick="setSuggestion('Opciones en tecnología')">
                Opciones en tecnología
            </button>
            <button class="suggestion-btn" onclick="setSuggestion('Carreras relacionadas con arte')">
                Carreras relacionadas con arte
            </button>
        </div>
    </div>

    <!-- Input Area -->
    <footer class="input-area">
        <div class="input-wrapper">
            <div class="input-container">
                <button class="icon-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
                <textarea 
                    id="messageInput" 
                    placeholder="Pregunta lo que quieras..."
                    rows="1"></textarea>
                <button class="icon-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                        <line x1="12" y1="19" x2="12" y2="23"></line>
                        <line x1="8" y1="23" x2="16" y2="23"></line>
                    </svg>
                </button>
                <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
            <p class="disclaimer">Este sistema es una herramienta de apoyo. Consulta con un orientador profesional para decisiones importantes.</p>
        </div>
    </footer>

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
                avatar.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>`;
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

            messagesContainer.querySelector('.messages-wrapper').appendChild(messageGroup);
            scrollToBottom();
        }

        function showTypingIndicator() {
            const indicator = document.createElement('div');
            indicator.className = 'message-group assistant typing-indicator';
            indicator.id = 'typingIndicator';
            indicator.innerHTML = `
                <div class="avatar assistant-avatar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="message assistant-message">
                    <div class="typing-dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            `;
            messagesContainer.querySelector('.messages-wrapper').appendChild(indicator);
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
    </script>
</body>
</html>