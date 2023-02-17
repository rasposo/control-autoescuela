<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseCurso.php';

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

    if ($_POST['teorico'] == "" ) { 
        $teorico = null; 
    } else { 
        $teorico = $_POST['teorico']; 
    }

    if ($_POST['destreza'] == "" ) {
        $destreza= null;
    } else {
        $destreza = $_POST['destreza'];
    }

    if ($_POST['circulacion'] == "" ) {
        $circulacion = null;
    } else {
        $circulacion = $_POST['circulacion'];
    }

    if ($_POST['fecha_finalizacion'] == "" ) {
        $finalizacion = null;
    } else {
        $finalizacion = $_POST['fecha_finalizacion'];
    }
    
    // PARTE 2. Ponemos los datos en la instancia
    $curso = new Curso($curso_id);

    $curso->setAlumnoId               ($_POST['alumno_id']);
    $curso->setProfesorId             ($_POST['profesor_id']);
    $curso->setPermisoId              ($_POST['permiso_id']);
    $curso->setFechaInicio            ($inicio);
    $curso->setFechaExamenTeorico     ($teorico);
    $curso->setFechaExamenDestreza    ($destreza);
    $curso->setFechaExamenCirculacion ($circulacion);
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

?>