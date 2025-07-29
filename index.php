<?php
session_start();
require_once 'conexion.php';
require_once 'auth.php';

// Si ya está logueado, redirigir al panel
if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
    header('Location: panel.php');
    exit;
}

$mensaje = '';
$tipoMensaje = '';
$formularioActivo = 'login';

// Cambiar formulario según parámetro GET
if (isset($_GET['form']) && $_GET['form'] === 'registro') {
    $formularioActivo = 'registro';
}

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'login') {
    $email = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if (empty($email) || empty($contrasena)) {
        $mensaje = 'Por favor completa todos los campos';
        $tipoMensaje = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El email no es válido';
        $tipoMensaje = 'error';
    } else {
        $usuario = Auth::iniciarSesion($email, $contrasena);
        if ($usuario) {
            header('Location: panel.php');
            exit;
        } else {
            $mensaje = 'Email o contraseña incorrectos';
            $tipoMensaje = 'error';
        }
    }
}

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'registro') {
    $datos = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'apellido' => trim($_POST['apellido'] ?? ''),
        'nomusuario' => trim($_POST['nomusuario'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'contrasena' => $_POST['contrasena'] ?? '',
        'tipo' => $_POST['tipo'] ?? 'CLIENTE'
    ];
    
    $confirmarcontrasena = $_POST['confirmarcontrasena'] ?? '';

    // Validaciones
    if (empty($datos['nombre']) || empty($datos['apellido']) || empty($datos['nomusuario']) || 
        empty($datos['email']) || empty($datos['contrasena']) || empty($datos['tipo'])) {
        $mensaje = 'Todos los campos obligatorios deben ser completados';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'Email inválido';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else if (strlen($datos['contrasena']) < 6) {
        $mensaje = 'La contraseña debe tener al menos 6 caracteres';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else if ($datos['contrasena'] !== $confirmarcontrasena) {
        $mensaje = 'Las contraseñas no coinciden';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else {
       $resultado = Auth::registrarUsuario($datos);
        if ($resultado === true) {
            $mensaje = 'Registro exitoso. Ya puedes iniciar sesión';
            $tipoMensaje = 'success';
            $formularioActivo = 'login';
        } else if ($resultado === 'telefono_duplicado') {
            $mensaje = 'Este teléfono ya está registrado';
            $tipoMensaje = 'error';
            $formularioActivo = 'registro';
        } else if ($resultado === 'telefono_invalido') {
            $mensaje = 'El teléfono no puede tener más de 13 caracteres';
            $tipoMensaje = 'error';
            $formularioActivo = 'registro';
        } else {
            $mensaje = 'Error al registrar usuario o email ya existe';
            $tipoMensaje = 'error';
            $formularioActivo = 'registro';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Iniciar Sesión</title>
    <link rel="stylesheet" href="styleindex.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>TypeJobs</h1>
            <p>Conectamos talento con oportunidades</p>
        </div>
        
        <div class="form-tabs">
            <a href="index.php?form=login" class="tab-button <?php echo $formularioActivo === 'login' ? 'active' : ''; ?>">Iniciar sesión</a>
            <a href="index.php?form=registro" class="tab-button <?php echo $formularioActivo === 'registro' ? 'active' : ''; ?>">Registrarse</a>
        </div>
        
        <div class="login-form">
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje <?php echo $tipoMensaje; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($formularioActivo === 'login'): ?>
                <form method="POST">
                    <input type="hidden" name="accion" value="login">
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" required>
                    </div>
                    
                    <div class="form-row-options">
                        <div class="remember-me">
                            <label class="checkbox-container">
                                <input type="checkbox" name="recordarme" id="recordarme">
                                <span class="checkmark"></span>Recuérdame</label>
                        </div>
                        
                        <div class="forgot-password">
                            <a href="#" class="forgot-link" onclick="alert('Próximamente')">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                    
                    <button type="submit" class="login-btn">Iniciar sesión</button>
                </form>
            <?php endif; ?>
            
            <?php if ($formularioActivo === 'registro'): ?>
                <form method="POST">
                    <input type="hidden" name="accion" value="registro">
                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nomusuario">Nombre de usuario</label>
                        <input type="text" id="nomusuario" name="nomusuario" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono">
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo">Tipo de cuenta</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Seleccionar</option>
                            <option value="CLIENTE">Cliente</option>
                            <option value="PROVEEDOR">Proveedor</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmarcontrasena">Confirmar contraseña</label>
                        <input type="password" id="confirmarcontrasena" name="confirmarcontrasena" required>
                    </div>
                    
                    <button type="submit" class="login-btn">Crear Cuenta</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>    