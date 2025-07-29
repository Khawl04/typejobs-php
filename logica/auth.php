<?php
require_once '../datos/conexion.php';
require_once 'usuario.php';

class Auth {

    public static function iniciarSesion($emailUsuario, $contrasena) {
    $emailUsuario = trim($emailUsuario);
    $contrasena = trim($contrasena);

    if (empty($emailUsuario) || empty($contrasena)) {
        return false;
    }

    $claseConexion = new ClaseConexion();
    $conexion = $claseConexion->getConexion();
    
    $query = "SELECT * FROM usuarios WHERE (email = ? OR nomusuario = ?) AND contrasena = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sss", $emailUsuario, $emailUsuario, $contrasena);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $datosUsuario = $resultado->fetch_assoc();
        $usuario = new Usuario($datosUsuario);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['logueado'] = true;
        $_SESSION['usuario_id'] = $usuario->id;
        $_SESSION['usuario_email'] = $usuario->email;
        $_SESSION['usuario_nombre'] = $usuario->nomusuario;
        $_SESSION['usuario_tipo'] = $usuario->tipo;
        $stmt->close();
        $conexion->close();
        return $usuario;
    }
    $stmt->close();
    $conexion->close();
    return false;
}

    public static function registrarUsuario($datos) {
        $nombre = trim($datos['nombre']);
        $apellido = trim($datos['apellido']);
        $nomusuario = trim($datos['nomusuario']);
        $email = trim($datos['email']);
        $telefono = trim($datos['telefono']);
        $contrasena = trim($datos['contrasena']);
        $tipo = trim($datos['tipo']);

        if (empty($nombre) || empty($apellido) || empty($nomusuario) ||empty($email) || empty($contrasena) || empty($tipo)) {
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if (strlen($nomusuario) < 3 || strlen($nomusuario) > 20) {
            return false;
        }
        if (strlen($contrasena) < 6) {
            return false;
        }

        //verificar si el email ya existe
        $claseConexion = new ClaseConexion();
        $conexion = $claseConexion->getConexion();
        $query = "SELECT id_usuario FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $stmt->close();
            $conexion->close();
            return false;
        }

        $stmt->close();

        //verificar si el nombre de usuario ya existe
        $claseConexion = new ClaseConexion();
        $conexion = $claseConexion->getConexion();
        $query = "SELECT id_usuario FROM usuarios WHERE nomusuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $nomusuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $stmt->close();
            $conexion->close();
            return false;
        }

        $stmt->close();

       // Validar longitud del teléfono (máximo 13 caracteres)
        if (!empty($telefono) && strlen($telefono) > 13) {
             $conexion->close();
             return 'telefono_invalido';
        }

       // Verificar si el teléfono ya existe (solo si no está vacío)
        if (!empty($telefono)) {
            $query = "SELECT id_usuario FROM usuarios WHERE telefono = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("s", $telefono);
            $stmt->execute();
            $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $stmt->close();
            $conexion->close();
            return 'telefono_duplicado';
        }

        $stmt->close();
        }   
          
    
        $query = "INSERT INTO usuarios (nombre, apellido, nomusuario, email, telefono, contrasena, tipo, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssssss", $nombre, $apellido, $nomusuario, $email, $telefono, $contrasena, $tipo);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }
    
    public static function estaLogueado() {
        if (session_status() == PHP_SESSION_NONE) {
        session_start();
        }
        return isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
    }

    public static function cerrarSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        return true;
    }

    public static function verificarAcceso() {
        if (!Auth::estaLogueado()) {
            header('Location: index.php');
            exit;
        }
    }

    public static function getUsuarioActual() {
        if (!Auth::estaLogueado()) {
            return null;
        }
        $claseConexion = new ClaseConexion();
        $conexion = $claseConexion->getConexion();
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $_SESSION['usuario_email']);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $datosUsuario = $resultado->fetch_assoc();
            $stmt->close();
            $conexion->close();
            return new Usuario($datosUsuario);
        }
        $stmt->close();

        $conexion->close();
        return null;
    }
}
?>