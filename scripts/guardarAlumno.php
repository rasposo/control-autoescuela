<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseAlumno.php';

// PARTE 1. Comprobanos que no existe ya el DNI y el correo electrónico en la base de datos en caso de alumno nuevo.
if ( $_POST['alumno_id'] == 0 && loadAlumByDNI($pdo, $_POST['dni'])) {
    $respuesta = new stdClass();
    $respuesta->id    = -1;
    $respuesta->texto = "El DNI ya existe";
    $json_respuesta   = json_encode($respuesta);
    echo ($json_respuesta);

} elseif ( $_POST['alumno_id'] == 0 && loadAlumByEmail($pdo, $_POST['email'])) {
    $respuesta = new stdClass();
    $respuesta->id    = -1;
    $respuesta->texto = "El email ya existe";
    $json_respuesta   = json_encode($respuesta);
    echo ($json_respuesta);
} else {

    try {
        // PARTE 2. RECUPERAR PARÁMETROS
        $alumno_id = $_POST['alumno_id'];

        
        // PARTE 3. Ponemos los datos en la instancia
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
        $respuesta->texto = 'Alumno guardado';
        

        //cargamos el nuevo alumno
        $new_alumno = loadAlumByDNI($pdo, $_POST['dni']);
        $respuesta->alumno_id = $new_alumno['alumno_id'];;

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
}