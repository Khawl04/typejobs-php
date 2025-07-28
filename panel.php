<?php
require_once 'auth.php';

// Verificar que el usuario esté logueado
Auth::verificarAcceso();

// Obtener datos del usuario actual
$usuario = Auth::getUsuarioActual();

if (!$usuario) {
    header('Location: index.php');
    exit;
}

// Procesar cerrar sesión
if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    Auth::cerrarSesion();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Panel de Usuario</title>
    <link rel="stylesheet" href="stylepanel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="img/typejobs-icono-solo.jpg" alt="TypeJobs Logo" class="logo">
                <h2>TypeJobs</h2>
            </div>
        
            <div class="search-section">
                <input type="text" class="search-input" placeholder="Buscar servicios...">
                <div class="search-icon"></div>
            </div>
        
            <div class="user-info">
                <span>Hola, <?php echo ($usuario->getNomUsuario()); ?></span>
                <a href="panel.php?accion=logout" class="logout-btn" onclick="return confirm('¿Estás seguro que quieres salir?')">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="panel-card">
            <div class="panel-header">
                <h1>Mi Panel</h1>
                <p>Datos personales</p>
            </div>
            
            <div class="user-details">
                <h2>Mis Datos</h2>
                <div class="user-grid">
                    <div class="user-item">
                        <strong>ID:</strong>
                        <span><?php echo htmlspecialchars($usuario->id); ?></span>
                    </div>
                    <div class="user-item">
                        <strong>Nombre:</strong>
                        <span><?php echo htmlspecialchars($usuario->nombre . ' ' . $usuario->apellido); ?></span>
                    </div>
                    <div class="user-item">
                        <strong>Usuario:</strong>
                        <span><?php echo htmlspecialchars($usuario->nomusuario); ?></span>
                    </div>
                    <div class="user-item">
                        <strong>Email:</strong>
                        <span><?php echo htmlspecialchars($usuario->email); ?></span>
                    </div>
                    <div class="user-item">
                        <strong>Teléfono:</strong>
                        <span><?php echo $usuario->telefono ? htmlspecialchars($usuario->telefono) : 'No especificado'; ?></span>
                    </div>
                    <div class="user-item">
                        <strong>Tipo:</strong>
                        <span><?php echo htmlspecialchars($usuario->tipo); ?></span>
                    </div>
                    <div class="user-item">
                        <strong>Fecha de Registro:</strong>
                        <span><?php echo date('d/m/Y', strtotime($usuario->fechaRegistro)); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="acciones-section">
                <h2>Acciones</h2>
                <div class="acciones-grid">
                    <a href="#" class="accion-item" onclick="alert('Próximamente')">Editar perfil</a>
                    
                    <?php if ($usuario->esProveedor()): ?>
                        <a href="#" class="accion-item" onclick="alert('Próximamente')">Mis servicios</a>
                    <?php else: ?>
                        <a href="#" class="accion-item" onclick="alert('Próximamente')">Buscar servicios</a>
                    <?php endif; ?>
                    
                    <a href="#" class="accion-item" onclick="alert('Próximamente')">Configuración</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>