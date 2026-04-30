<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/funciones.php';

logout();
session_start();   // reabrir para poder dejar el flash
setMensaje('info', 'Has cerrado sesión.');
redirigir('/index.php');
