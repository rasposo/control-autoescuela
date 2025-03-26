<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseCurso.php';

$fecha = getDate();
$fecha_actual = $fecha['year']."/".$fecha['mon']."/".$fecha['mday'];

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    $curso_id = $_POST['curso_id'];

    if ($_POST['pagado'] == "1") {
        $pagado = 1;
    } else {
        $pagado = 0;
    }
    
    if ($_POST['finalizado'] == "1") {
        $finalizado = 1;
    } else {
        $finalizado = 0;
    }

    if ($_POST['inicio'] == "" ) {
        $inicio = null;
    } else {
        $inicio = $_POST['inicio'];
    }

    if ($_POST['fecha_finalizacion'] == "" ) {
        $finalizacion = $fecha_actual;
    } else {
        $finalizacion = $_POST['fecha_finalizacion'];
    }
    
    // PARTE 2. Ponemos los datos en la instancia
    $curso = new Curso($curso_id);
    $curso->loadFromDB($pdo);

    $curso->setAlumnoId               ($_POST['alumno_id']);
    $curso->setPermisoId              ($_POST['permiso_id']);
    $curso->setFechaInicio            ($inicio);
    $curso->setFinalizado             ($finalizado);
    $curso->setFechaFinalizacion      ($finalizacion);
    $curso->setPagado                 ($pagado);
    
    // PARTE 4. REALIZAR ACCIÓN
    $curso->saveIntoDB($pdo);
      
    // PARTE 5. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Curso guardado";
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