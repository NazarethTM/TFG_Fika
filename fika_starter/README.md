# ☕ Fika · Cafetería de estudio

Trabajo Fin de Grado · DAW · Curso 2025/2026
Equipo: **Nazareth · Ken · Laura**
Defensa: **26 de mayo de 2026**

---

## Stack

- **Backend:** PHP 8 nativo (sin frameworks)
- **BBDD:** MySQL 8 / MariaDB (XAMPP)
- **Frontend:** HTML5, CSS3, JavaScript vanilla, Bootstrap 5
- **Control de versiones:** Git + GitHub

---

## 🚀 Cómo instalar el proyecto en local

1. Clona el repo dentro de la carpeta `htdocs` de XAMPP:
   ```
   C:\xampp\htdocs\TFG_FIKA
   ```
2. Arranca XAMPP (Apache + MySQL).
3. Abre phpMyAdmin (http://localhost/phpmyadmin) e importa `database/fika.sql`.
4. Comprueba que `config/config.php` tiene la `BASE_URL` correcta:
   ```php
   define('BASE_URL', 'http://localhost/TFG_FIKA/public');
   ```
5. Abre en el navegador: `http://localhost/TFG_FIKA/public/`

### Crear el primer admin

Como la contraseña no se guarda en SQL, regístrate con el formulario
de la web y luego en phpMyAdmin ejecuta:

```sql
UPDATE usuarios SET rol = 'admin' WHERE email = 'tu_email@fika.com';
```

---

## 📂 Estructura del proyecto

```
TFG_FIKA/
├── database/                  Esquema SQL
│   └── fika.sql
│
├── config/                    🔵 [los tres] · NO tocar sin avisar
│   ├── config.php             Constantes (BBDD, BASE_URL, sesión)
│   └── db.php                 Conexión PDO singleton
│
├── includes/                  🔵 [los tres] · piezas reutilizables
│   ├── auth.php               login, registro, comprobar rol
│   ├── funciones.php          helpers (e(), redirigir(), precio()...)
│   ├── header.php             cabecera HTML común
│   ├── navbar.php             barra de navegación
│   └── footer.php             cierre HTML común
│
├── public/                    Lo que ve el usuario en el navegador
│   ├── index.php              🔵 Home
│   ├── login.php              🔵 Iniciar sesión
│   ├── registro.php           🔵 Crear cuenta
│   ├── logout.php             🔵 Cerrar sesión
│   ├── nosotros.php           🟣 Laura · quiénes somos
│   ├── css/style.css          🔵 estilos comunes
│   ├── js/main.js             🔵 JS común
│   ├── img/                   imágenes (logo, plano del local)
│   │
│   ├── mesas/                 🟢 NAZARETH · reservas
│   ├── cursos/                🟣 LAURA · cursos e inscripciones
│   ├── pedidos/               🟠 KEN · catálogo y pedidos
│   ├── chatbot/               🟠 KEN · chatbot
│   └── admin/                 ⚫ panel admin (cada uno su parte)
│
└── docs/                      🔵 [los tres]
    ├── memoria/               apartados de la memoria en .md
    └── capturas/              capturas para la memoria
```

> **Cada carpeta tiene su propio `README.md`** explicando qué archivos
> hay que crear y dándote ejemplos de código. Léelos antes de empezar.

🔵 común · 🟢 Naza · 🟣 Laura · 🟠 Ken · ⚫ compartido por módulos

---

## 📝 Convenciones de código

- **Nombres de archivos en minúsculas con guiones bajos:** `mis_reservas.php`
- **Nombres de funciones en camelCase:** `requerirLogin()`, `setMensaje()`
- **Indentación:** 4 espacios
- **Siempre `require_once` con `__DIR__`:**
  ```php
  require_once __DIR__ . '/../includes/header.php';
  ```
- **Siempre `prepare()` + `execute()` con parámetros**, nunca concatenar variables en SQL.
- **Siempre `e()` al imprimir variables en HTML:** `<?= e($nombre) ?>`
- **Comentarios en español**, los archivos también.

---

## 🌳 Flujo de trabajo con Git

### Ramas que vamos a usar

```
main              ← versión estable, solo se actualiza al final de cada fase
 └── dev          ← rama de integración, aquí mergeamos lo que ya funciona
      ├── feature/mesas       (Naza)
      ├── feature/cursos      (Laura)
      └── feature/chatbot     (Ken)
```

### Setup inicial (lo hace una sola persona, una sola vez)

```bash
# Crear la rama dev a partir de main
git checkout main
git pull
git checkout -b dev
git push -u origin dev
```

### Cómo trabaja cada uno cada día

**1. Antes de empezar**, actualiza tu rama con lo último de `dev`:

```bash
git checkout feature/mesas        # tu rama
git pull origin dev               # trae lo nuevo de dev a tu rama
```

**2. Trabaja normal.** Crea archivos, edita, prueba.

**3. Cuando tengas algo que funcione (un paso pequeño)**, haz commit:

```bash
git add .
git commit -m "feat(mesas): añade validación de solapamiento horario"
git push origin feature/mesas
```

**Mensajes de commit recomendados:**
- `feat(mesas): añade mapa interactivo`
- `fix(cursos): corrige error al inscribirse en curso lleno`
- `docs: actualiza README con instrucciones de XAMPP`
- `style(admin): ajusta CSS de la tabla de pedidos`

**4. Cuando tu funcionalidad esté lista para integrarse**, abre un Pull Request
en GitHub: de `feature/mesas` → `dev`. Pide a otro compañero que la revise.

### Integraciones a `main`

Solo al final de cada fase hacemos merge de `dev` → `main`. Eso lo hacemos
los tres juntos en una pequeña reunión, comprobando que todo funciona.

---

## 🧯 Si algo va mal

| Problema | Solución |
|---|---|
| Conflicto al hacer pull | Pídele a otro que te ayude la primera vez. No fuerces nunca un merge. |
| He commiteado algo que no debía | `git reset HEAD~1` deshace el último commit (sin perder cambios). |
| He pisado código de otro | Antes del próximo commit, asegúrate de hacer `git pull origin dev`. |
| Quiero ver el estado | `git status` te dice qué tienes modificado. |
| Quiero ver qué hicieron los demás | `git log --oneline --all --graph` |

---

## 📅 Calendario

| Fase | Fechas | Contenido |
|---|---|---|
| 1 · Cimientos | 1 – 6 mayo | BBDD, login, layout, repo |
| 2 · Núcleo | 7 – 15 mayo | Cada uno su módulo principal |
| 3 · Pedidos + admin | 16 – 22 mayo | Carrito, panel admin, integración |
| 4 · Memoria + defensa | 23 – 26 mayo | Documentar, ensayar, defender |

---

## 📖 Documentación

La memoria se va escribiendo **a la vez que el código**, no al final.
Cada vez que tomes una decisión técnica, anótala en `docs/memoria/`.
Cada captura de pantalla útil, a `docs/capturas/`.
