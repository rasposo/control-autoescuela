<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseProfesor.php';

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    $profesor_id = $_POST['profesor_id'];

    if ($_POST['perfil_director'] == "1") {
        $perfil_director = 1;
    } else {
        $perfil_director = 0;
    }
    
    if ($_POST['perfil_administrador'] == "1") {
        $perfil_administrador = 1;
    } else {
        $perfil_administrador = 0;
    }
    
    // PARTE 2. Ponemos los datos en la instancia
    $profesor = new Profesor($profesor_id);

    $profesor->setNombre($_POST['nombre']);
    $profesor->setApellido1($_POST['apellido1']);
    $profesor->setApellido2($_POST['apellido2']);
    $profesor->setDNI($_POST['DNI']);
    $profesor->setCaducidadDNI($_POST['caducidad_dni']);
    $profesor->setDireccion($_POST['direccion']);
    $profesor->setLocalidad($_POST['localidad']);
    $profesor->setProvincia($_POST['provincia']);
    $profesor->setCodigoPostal($_POST['codigo_postal']);
    $profesor->setFechaNacimiento($_POST['fecha_nacimiento']);
    $profesor->setFechaIngreso($_POST['fecha_ingreso']);
    $profesor->setNumeroSS($_POST['numero_ss']);
    $profesor->setPerfilDirector($_POST['perfil_director']);
    $profesor->setPerfilAdmin($_POST['perfil_administrador']);
    $profesor->setTelefono($_POST['telefono']);
    $profesor->setEmail($_POST['email']);
    $profesor->setContraseña($_POST['contraseña']);

    $permisos = [];
    $permiso = [];
    if ( isset ( $_POST['A2'] )) { $permiso['permiso'] = 'A2'; $permiso['fecha'] = $_POST['A2']; array_push($permisos, $permiso); };
    if ( isset ( $_POST['A'] ))  { $permiso['permiso'] = 'A'; $permiso['fecha'] = $_POST['A']; array_push($permisos, $permiso); };
    if ( isset ( $_POST['B'] )) { $permiso['permiso'] = 'B'; $permiso['fecha'] = $_POST['B']; array_push($permisos, $permiso); };
    if ( isset ( $_POST['C'] )) { $permiso['permiso'] = 'C'; $permiso['fecha'] = $_POST['C']; array_push($permisos, $permiso); };
    if ( isset ( $_POST['C+E'] )) { $permiso['permiso'] = 'C+E'; $permiso['fecha'] = $_POST['C+E']; array_push($permisos, $permiso); };
    if ( isset ( $_POST['D'] )) { $permiso['permiso'] = 'D'; $permiso['fecha'] = $_POST['D']; array_push($permisos, $permiso); };

    $profesor->setPermisos($permisos);


    // PARTE 4. REALIZAR ACCIÓN
    $profesor->saveIntoDB($pdo);
      
    // PARTE 5. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Profesor guardado";
    $json_respuesta   = json_encode($respuesta);
    echo ($json_respuesta);
	  
    // b) Incorrecto
} catch (Exception $e) {
    $error = new stdClass();
    $error->id    = -1;
    $error->texto = $e->getMessage();
    $json_error   = json_encode($error);
    echo ($json_error);
}   

?>