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
    //los permisos llegan como string separados por comas y los dos elementos separados por :
    $permisos_string = $_POST['permisos'];
    $permisos = explode(",", $permisos_string);
    $permisos_total = [];
    foreach($permisos as $p) {
        $lista = [];
        $plode = explode(":", $p);
        $lista['permiso'] = $plode[0];
        $lista['fecha'] = $plode[1];
        $permisos_total[] = $lista;
    }


    // PARTE 2. Ponemos los datos en la instancia
    $profesor = new Profesor($profesor_id);
    $profesor->loadFromDB($pdo);

    $profesor->setPermisos($permisos_total);

    // PARTE 3. REALIZAR ACCIÓN
    $profesor->saveIntoDB($pdo);
      
    // PARTE 5. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Permisos guardados";
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
