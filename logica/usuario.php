<?php
class Usuario {
    public $id;
    public $nombre;
    public $apellido;
    public $nomusuario; 
    public $email;
    public $telefono;
    public $tipo;
    public $fechaRegistro;
    
    public function __construct($datos) {
        $this->id = $datos['id_usuario'];
        $this->nombre = $datos['nombre'];
        $this->apellido = $datos['apellido'];
        $this->nomusuario = $datos['nomusuario'];
        $this->email = $datos['email'];
        $this->telefono = $datos['telefono'];
        $this->tipo = $datos['tipo'];
        $this->fechaRegistro = $datos['fecha_registro'];
    }

    public function getNomUsuario() {
        return $this->nomusuario;
    }
    
    public function esCliente() {
        return $this->tipo === 'CLIENTE';
    }
    
    public function esProveedor() {
        return $this->tipo === 'PROVEEDOR';
    }
}
?>