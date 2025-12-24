# Configuración de Gemini API

## Pasos para obtener y configurar la API Key de Gemini

### 1. Obtener la API Key

1. Ve a [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Inicia sesión con tu cuenta de Google
3. Haz clic en "Create API Key" o "Obtener clave de API"
4. Selecciona un proyecto de Google Cloud (o crea uno nuevo)
5. Copia la API Key generada

### 2. Configurar en el proyecto

1. Abre el archivo `.env` en la raíz del proyecto
2. Busca la línea `GEMINI_API_KEY=`
3. Pega tu API Key después del signo igual:
   ```
   GEMINI_API_KEY=tu_api_key_aqui
   ```
4. Guarda el archivo

### 3. Reiniciar el servidor

Si el servidor Laravel está corriendo, reinícialo para que cargue la nueva configuración:

```bash
# Detén el servidor con Ctrl+C
# Luego vuelve a iniciarlo
php artisan serve
```

### 4. Probar el asistente

1. Ve a http://127.0.0.1:8000/estudiantes
2. Escribe un mensaje en el chat
3. Deberías recibir respuestas personalizadas del asistente de orientación vocacional

## Notas Importantes

- **Gratuito**: Gemini API tiene una capa gratuita generosa
- **Límites**: Revisa los límites de uso en la consola de Google AI
- **Seguridad**: Nunca compartas tu API Key públicamente ni la subas a repositorios públicos
- **Privacidad**: El archivo `.env` está en `.gitignore` por defecto

## Solución de Problemas

### Error: "API key no configurada"
- Verifica que agregaste la API Key en el archivo `.env`
- Asegúrate de que no haya espacios antes o después de la clave
- Reinicia el servidor Laravel

### Error de conexión
- Verifica tu conexión a internet
- Comprueba que la API Key sea válida
- Revisa los logs en `storage/logs/laravel.log`

### Error 429 (Rate Limit)
- Has excedido el límite de llamadas a la API
- Espera unos minutos antes de volver a intentar
- Considera actualizar a un plan de pago si necesitas más cuota

## Enlaces Útiles

- [Google AI Studio](https://makersuite.google.com/)
- [Documentación de Gemini API](https://ai.google.dev/docs)
- [Límites y cuotas](https://ai.google.dev/pricing)
