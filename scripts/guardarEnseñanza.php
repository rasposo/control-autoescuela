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
    $precio = $_POST['precio'];

    // PARTE 2. REALIZAR ACCIÓN

    //si nos manda el id de la enseñanza, actualizo si no, inserto
    if ( isset($_POST['enseñanza_id']) ) {
      $enseñanza_id = $_POST['enseñanza_id'];
      $stmt = $pdo->prepare('UPDATE Enseñanza SET tipo = :tip, 
                                                  precio = :pre
                                              WHERE 
                                                  enseñanza_id = :eid' );
      $stmt->execute(array( ':tip' => $tipo,
                            ':pre' => $precio,
                            ':eid' => $enseñanza_id
                    ));

    } else {
      $stmt = $pdo->prepare('INSERT INTO Enseñanza ( permiso_id,
                                                    tipo,
                                                    precio
                                                  )
                              VALUES (:pid, :tip, :pre)'
                          );
      $stmt->execute(array( ':pid' => $permiso_id,
                            ':tip' => $tipo,
                            ':pre' => $precio
                          ));
    };

    // PARTE 3. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Enseñanza guardada";
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