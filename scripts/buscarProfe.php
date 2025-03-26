<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
try {
  //Buscamos en la base de datos, primero con nombre y después con apellido
  $search = [];
  if ( isset ($_POST['nombre'])) {
    $search = buscarProfe($pdo, $_POST['nombre']);
  } else {
    $search = loadAllProfe($pdo);
  }
      //devolver resultado correcto
      $respuesta = new stdClass();
      $respuesta->id  = 0;
      $respuesta->texto = "Lista profesores";
      $respuesta->search = $search;
      $json_respuesta   = json_encode($respuesta);
      echo ($json_respuesta);

} catch (Exception $e) {
  $error = new stdClass();
  $error->id    = -1;
  $error->texto = $e->getMessage();
  $json_error   = json_encode($error);
  echo ($json_error);
}

?>