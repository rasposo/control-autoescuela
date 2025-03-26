<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseCurso.php';


try {
  
  //obtenemos los datos a introducir
    $relacion_id = $_POST['relacion_id'];
    $curso_id = $_POST['curso_id'];
    $profesor_id = $_POST['profesor_id'];

    $curso = new Curso($curso_id);
    $curso->loadFromDB($pdo);
    $seccion = $curso->getSeccion();
    $autoescuela = loadAutoescuelaBySeccion($pdo, $seccion);



    //DEVOLVER RESULTADO

        //a) correcto
        $stmt = $pdo->prepare('INSERT INTO Examen ( relacion_id,
                                                        autoescuela_id,
                                                        curso_id,
                                                        profesor_id,
                                                        vehiculo_id,
                                                        estado )
                                    VALUES (:rid, :aid, :cid, :pid, :vid, :est)'
                                    );
            $stmt->execute(array(
              ':rid' => $relacion_id,
              ':aid' => $autoescuela['autoescuela_id'],
              ':cid' => $curso_id,
              ':pid' => $profesor_id,
              ':vid' => null,
              ':est' => "Pendiente"
                )
            );

        $respuesta = new stdClass();
        $respuesta->id    = 0;
        $respuesta->texto = "Alumno inscrito en examen";
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