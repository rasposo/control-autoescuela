<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

try {

    //Recogemos los datos
    $id = $_POST['id'];

    //introducimos la tasa
    $stmt = $pdo->prepare('DELETE FROM Tasa WHERE tasa_id = :id');
    $stmt->execute(array(
                        ':id' => $id,
                    ));


  //DEVOLVER RESULTADO
	// a) Correcto
  $respuesta = new stdClass();
  $respuesta->id    = 0;
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