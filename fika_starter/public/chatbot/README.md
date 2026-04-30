# Módulo: Chatbot

**Responsable:** Ken
**Rama de Git:** la misma que pedidos, o `feature/chatbot` separada

## Ficheros que tienes que crear aquí

| Archivo | Para qué sirve |
|---|---|
| `widget.php` | Trozo de HTML/CSS/JS del chat flotante. Se incluye desde el footer si quieres que aparezca en toda la web. |
| `responder.php` | Endpoint que recibe el mensaje del usuario, lo manda a la API de IA y devuelve la respuesta como JSON. |
| `historial.php` | Para el panel admin: muestra conversaciones recientes (lee de `chat_mensajes`). |

## Dos versiones del chatbot

### Versión segura (empieza por aquí)

Árbol de respuestas predefinidas en JS, sin IA. Botones tipo:
"¿Cómo reservo una mesa?", "¿Qué horarios tenéis?", etc. Cada botón
responde con un texto fijo. Funciona seguro y se entrega.

### Versión ambiciosa (si llega tiempo)

Llamada a una API externa de IA con `cURL`. Opciones gratuitas:
- **Groq** (https://console.groq.com) - gratis, rápido, modelo Llama
- **Google Gemini** (https://ai.google.dev) - gratis con cuota generosa
- **OpenAI** - de pago pero hay créditos gratis al registrarse

**Esqueleto de `responder.php` con Groq:**

```php
<?php
require_once __DIR__ . '/../../includes/auth.php';
header('Content-Type: application/json');

$mensaje = trim($_POST['mensaje'] ?? '');
if ($mensaje === '') {
    echo json_encode(['error' => 'Mensaje vacío']);
    exit;
}

// El prompt de sistema le dice al bot quién es y qué sabe
$systemPrompt = "Eres el asistente virtual de Fika, una cafetería de estudio.
Ayudas a los usuarios con dudas sobre reservas, cursos y pedidos.
Sé breve y amable. Si no sabes algo, di que el usuario lo consulte en recepción.";

$apiKey = 'TU_API_KEY_AQUI';   // mejor en config, no aquí

$payload = [
    'model'    => 'llama-3.1-8b-instant',
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user',   'content' => $mensaje],
    ],
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ],
]);

$resp = curl_exec($ch);
curl_close($ch);

$data = json_decode($resp, true);
$respuesta = $data['choices'][0]['message']['content'] ?? 'Error en el chatbot.';

// Guardar en BBDD para historial (opcional pero queda muy bien en defensa)
$pdo = getDB();
$sesion = session_id();
$userId = $_SESSION['user_id'] ?? null;
$pdo->prepare("INSERT INTO chat_mensajes (usuario_id, sesion, rol, mensaje) VALUES (?,?,?,?)")
    ->execute([$userId, $sesion, 'user', $mensaje]);
$pdo->prepare("INSERT INTO chat_mensajes (usuario_id, sesion, rol, mensaje) VALUES (?,?,?,?)")
    ->execute([$userId, $sesion, 'assistant', $respuesta]);

echo json_encode(['respuesta' => $respuesta]);
```

## ⚠️ Importante: la API key NO se sube a GitHub

Crea el archivo `config/config.local.php` (ya está en `.gitignore`)
con tu API key y léelo desde `config.php`. Así no se filtra al repo.
