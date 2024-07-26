<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    if ( isset ($_POST['permiso_id']) ) {
      $permiso_id = $_POST['permiso_id'];
    };
    $tipo = $_POST['tipo'];

    // PARTE 2. REALIZAR ACCIÓN

    //si nos manda el id del permiso, actualizo si no, inserto
    if ( isset($_POST['permiso_id']) ) {
      $stmt = $pdo->prepare('UPDATE Permiso SET tipo = :tip WHERE permiso_id = :pid');
      $stmt->execute(array( ':tip' => $tipo,
                            ':pid' => $permiso_id
                    ));

    } else {
      $stmt = $pdo->prepare('INSERT INTO Permiso ( tipo ) VALUES ( :tip )' );
      $stmt->execute(array( ':tip' => $tipo ));
    };

    // PARTE 3. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Permiso guardado";
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