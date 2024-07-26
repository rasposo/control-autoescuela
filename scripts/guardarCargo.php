<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseCargo.php';

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    $fecha = $_POST['fecha'];
    $curso_id = $_POST['curso_id'];
    $string_cargos = $_POST['cargos'];
    $cargos = explode(",", $string_cargos);

    if ( !empty( $cargos ) ) {

        // PARTE 2. hacemos una entrada por cada cargo
        foreach ( $cargos as $c ) {
            $cargo = new Cargo();

            $cargo->setTasaId  ( $c );
            $cargo->setFecha   ( $fecha );
            $cargo->setCursoId ( $curso_id );

            // PARTE 3. REALIZAR ACCIÓN
            $cargo->saveIntoDB($pdo);
        }
    }
    
      
    // PARTE 4. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Cargos guardados";
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