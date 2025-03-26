<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseClase.php';

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    $enseñanza_id = $_POST['enseñanza'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $curso_id = $_POST['curso_id'];
    $profesor_id = $_POST['profesor_id'];

    
    // PARTE 2. Ponemos los datos en la instancia
    $clase = new Clase();

    $clase->setEnseñanzaId ( $enseñanza_id) ;
    $clase->setFecha       ( $fecha );
    $clase->setHora        ( $hora );
    $clase->setCursoId     ( $curso_id );
    $clase->setProfesorId  ( $profesor_id );

    // PARTE 4. REALIZAR ACCIÓN
    $clase->saveIntoDB($pdo);
      
    // PARTE 5. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Clase guardada";
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