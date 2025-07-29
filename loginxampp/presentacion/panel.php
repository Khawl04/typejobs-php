<?php

require_once '../logica/auth.php';

//Verificar que el usuario este logueado
Auth::verificarAcceso();

//Obtener datos del usuario actual
$usuario = Auth::getUsuarioActual();

if (!$usuario) {
    header('Location: index.php');
    exit;
}

//Procesar accion de cerrar sesion
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
</head>
<body>
    <!-- ENCABEZADO CON NAVEGACION -->
    <div class="header">
        <div class="logo-section">
            <img src="../img/typejobs-icono-solo.jpg" alt="TypeJobs Logo" class="logo">
            <h2>TypeJobs</h2>
        </div>
        
        <div class="search-section">
            <input type="text" class="search-input" placeholder="Buscar servicios...">
        </div>
        
        <div class="user-info">
            <span>Hola, <?php echo htmlspecialchars($usuario->getNomUsuario()); ?></span>
            <a href="panel.php?accion=logout" class="logout-btn" onclick="return confirm('¿Estas seguro que quieres salir?')">
                Cerrar Sesion
            </a>
        </div>
    </div>

    <div class="container">
        <!-- ENCABEZADO DEL PANEL -->
        <div class="panel-card">
            <div class="panel-header">
                <h1>Mi Panel de Usuario</h1>
                <p>Informacion personal y configuracion</p>
            </div>
            
            <!-- SECCION DE DATOS PERSONALES -->
            <div class="user-details">
                <h2>Mis Datos Personales</h2>
                <div class="user-grid">
                    <div class="user-item">
                        <strong>ID de Usuario:</strong>
                        <span><?php echo htmlspecialchars($usuario->id); ?></span>
                    </div>
                    
                    <div class="user-item">
                        <strong>Nombre Completo:</strong>
                        <span><?php echo htmlspecialchars($usuario->nombre . ' ' . $usuario->apellido); ?></span>
                    </div>
                    
                    <div class="user-item">
                        <strong>Nombre de Usuario:</strong>
                        <span><?php echo htmlspecialchars($usuario->nomusuario); ?></span>
                    </div>
                    
                    <div class="user-item">
                        <strong>Email:</strong>
                        <span><?php echo htmlspecialchars($usuario->email); ?></span>
                    </div>
                    
                    <div class="user-item">
                        <strong>Telefono:</strong>
                        <span><?php echo $usuario->telefono ? htmlspecialchars($usuario->telefono) : 'No especificado'; ?></span>
                    </div>
                    
                    <div class="user-item">
                        <strong>Tipo de Usuario:</strong>
                        <span><?php echo htmlspecialchars($usuario->tipo); ?></span>
                    </div>
                    
                    <div class="user-item">
                        <strong>Fecha de Registro:</strong>
                        <span><?php echo date('d/m/Y', strtotime($usuario->fechaRegistro)); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- SECCION DE ACCIONES DISPONIBLES -->
            <div class="acciones-section">
                <h2>Acciones Disponibles</h2>
                <div class="acciones-grid">
                    <a href="#" class="accion-item" onclick="alert('Proximamente - Editar perfil')">
                        Editar mi Perfil
                    </a>
                    
                    <?php if ($usuario->esProveedor()): ?>
                        <a href="#" class="accion-item" onclick="alert('Proximamente - Mis servicios')">
                            Gestionar mis Servicios
                        </a>
                        
                        <a href="#" class="accion-item" onclick="alert('Proximamente - Estadisticas')">
                            Ver mis Estadisticas
                        </a>
                    <?php else: ?>
                        <a href="#" class="accion-item" onclick="alert('Proximamente - Buscar servicios')">
                            Buscar Servicios
                        </a>
                        
                        <a href="#" class="accion-item" onclick="alert('Proximamente - Mis contrataciones')">
                            Mis Contrataciones
                        </a>
                    <?php endif; ?>
                    
                    <a href="#" class="accion-item" onclick="alert('Proximamente - Configuracion')">
                        Configuracion de Cuenta
                    </a>
                    
                    <a href="#" class="accion-item" onclick="alert('Proximamente - Soporte')">
                        Contactar Soporte
                    </a>
                </div>
            </div>      
</body>
</html>