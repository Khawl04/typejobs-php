<?php
class ClaseConexion{
// Configuración de la base de datos
private $servidor = "localhost";
private $usuario = "root";
private $contrasena = "";
private $baseDatos = "typejobs";
private $conexion;

// Conectar a la base de datos
public function getConexion() {

        $this->conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->baseDatos, 3306);

        if ($this->conexion->connect_error) {
            exit("Error de conexión: " . $this->conexion->connect_error);
        }
    
    return $this->conexion;
}

// Función para cerrar la conexión
public function cerrarConexion() {
        $this->conexion->close();
}
}
?>