<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseAlumno.php';

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    $alumno_id = $_POST['alumno_id'];

    
    // PARTE 2. Ponemos los datos en la instancia
    $alumno = new Alumno($alumno_id);

    $alumno->setNombre          ($_POST['nombre']);
    $alumno->setApellido1       ($_POST['apellido1']);
    $alumno->setApellido2       ($_POST['apellido2']);
    $alumno->setDNI             ($_POST['dni']);
    $alumno->setCaducidadDNI    ($_POST['caducidad_dni']);
    $alumno->setDireccion       ($_POST['direccion']);
    $alumno->setLocalidad       ($_POST['localidad']);
    $alumno->setProvincia       ($_POST['provincia']);
    $alumno->setCodigoPostal    ($_POST['codigo_postal']);
    $alumno->setFechaNacimiento ($_POST['fecha_nacimiento']);
    $alumno->setFechaIngreso    ($_POST['fecha_ingreso']);
    $alumno->setNacionalidad    ($_POST['nacionalidad']);
    $alumno->setEstudios        ($_POST['estudios']);
    $alumno->setTelefono        ($_POST['telefono']);
    $alumno->setEmail           ($_POST['email']);
    $alumno->setContraseña      ($_POST['contraseña']);

    // PARTE 4. REALIZAR ACCIÓN
    $alumno->saveIntoDB($pdo);
      
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