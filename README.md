# OrientaBot — Asistente Virtual de Orientación Académica

Chatbot de orientación académica desarrollado con **Laravel** en el backend/frontend y la **API de Google Gemini** como motor de inteligencia artificial para generar respuestas. El estudiante formula una consulta y el sistema devuelve una orientación pertinente basada en IA.

> Proyecto desarrollado para la **Universidad Nacional Micaela Bastidas de Apurímac (UNAMBA)**, Escuela Profesional de Ingeniería Informática y Sistemas.

---

## Tabla de contenido

1. [Tecnologías utilizadas](#tecnologías-utilizadas)
2. [Requisitos previos](#requisitos-previos)
3. [Instalación](#instalación)
4. [Variables de entorno](#variables-de-entorno)
5. [Ejecución](#ejecución)
6. [Estructura del proyecto](#estructura-del-proyecto)
7. [Datos de prueba y credenciales de demostración](#datos-de-prueba-y-credenciales-de-demostración)
8. [Prototipo de Figma](#prototipo-de-figma)
9. [Integrantes y responsabilidades](#integrantes-y-responsabilidades)
10. [Licencia](#licencia)

---

## Tecnologías utilizadas

| Categoría        | Tecnología                          |
|-------------------|--------------------------------------|
| Backend           | Laravel (PHP)                       |
| Frontend          | Blade / Laravel + Vite (JS/CSS)     |
| Base de datos     | MySQL / MariaDB                     |
| IA / NLP          | Google Gemini API                   |
| Entorno local     | Laragon                             |
| Gestor de paquetes PHP | Composer                       |
| Gestor de paquetes JS  | npm                             |

> [COMPLETAR: indicar versiones exactas, ej. PHP 8.2, Laravel 10.x, Node 18.x]

---

## Requisitos previos

Antes de instalar el proyecto, asegúrate de tener instalado:

- PHP `>= [COMPLETAR versión]`
- Composer
- Node.js y npm
- MySQL / MariaDB (o usar Laragon, que ya los incluye)
- Una API Key válida de **Google Gemini** ([obtener aquí](https://ai.google.dev/))

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone [COMPLETAR: URL del repositorio]
cd [COMPLETAR: nombre-carpeta]

# 2. Instalar dependencias de PHP
composer install

# 3. Instalar dependencias de JavaScript
npm install

# 4. Copiar el archivo de variables de entorno
cp .env.example .env

# 5. Generar la clave de aplicación de Laravel
php artisan key:generate

# 6. Configurar la base de datos y la API Key de Gemini
# Editar el archivo .env con tus credenciales (ver sección siguiente)

# 7. Crear la base de datos y ejecutar migraciones + datos de prueba
php artisan migrate --seed

# 8. Compilar los assets del frontend
npm run build
```

---

## Variables de entorno

Copia `.env.example` a `.env` y completa los siguientes valores:

```env
APP_NAME=OrientaBot
APP_ENV=local
APP_KEY=          # se genera con php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=orientabot
DB_USERNAME=root
DB_PASSWORD=

GEMINI_API_KEY=[COMPLETAR: tu API key de Google Gemini]
```

> ⚠️ Nunca subas el archivo `.env` real (con claves reales) al repositorio. Solo `.env.example` con valores vacíos o de ejemplo.

---

## Ejecución

```bash
# Levantar el servidor de Laravel
php artisan serve

# En otra terminal, levantar Vite (si se usa modo desarrollo)
npm run dev
```

La aplicación quedará disponible en: `http://localhost:8000`

---

## Estructura del proyecto

```
orientabot/
├── frontend/          # Vistas Blade, assets, componentes JS/CSS
├── backend/           # Lógica Laravel: controladores, modelos, rutas
├── database/          # Migraciones, seeders, script de creación de BD
├── documentation/      # Documentación técnica y funcional del proyecto
├── tests/              # Pruebas automatizadas
├── screenshots/         # Capturas de pantalla del sistema funcionando
├── .env.example
├── README.md
└── LICENSE
```

> [COMPLETAR: ajustar según la estructura real de carpetas del repositorio]

---

## Datos de prueba y credenciales de demostración

| Rol       | Usuario / Correo             | Contraseña        |
|-----------|-------------------------------|--------------------|
| Estudiante | [COMPLETAR: correo demo]     | [COMPLETAR: clave demo, NO la personal] |
| Administrador (si aplica) | [COMPLETAR]     | [COMPLETAR]        |

Los datos de prueba se cargan automáticamente al ejecutar:

```bash
php artisan migrate --seed
```

---

## Prototipo de Figma

🔗 [COMPLETAR: enlace al prototipo de Figma]

---

## Integrantes y responsabilidades

| Integrante | Responsabilidad |
|------------|------------------|
| [COMPLETAR] | [COMPLETAR: ej. Backend / Integración Gemini] |
| [COMPLETAR] | [COMPLETAR: ej. Frontend / UI] |
| [COMPLETAR] | [COMPLETAR: ej. Base de datos / Documentación] |

**Asesor(a):** Ing. Maryluz Cuentas Toledo

---

## Licencia

Este proyecto fue desarrollado con **fines académicos** en el marco del curso/proyecto de la Escuela Profesional de Ingeniería Informática y Sistemas — UNAMBA. Su uso está restringido a fines educativos y de evaluación académica.

[COMPLETAR: si se desea, añadir licencia formal, ej. MIT License]
