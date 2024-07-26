<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

try {

    $fecha_presentacion = $_POST['fechaPresentacion'];
    $fecha_examen =       $_POST['fechaExamen'];
    $profesor =           $_POST['profesor'];
    $tipo_prueba =        $_POST['tipoPrueba'];

    //obtenemos la id del profesor
    $profesor_id = "";
    $array_profesor = explode(" ", $profesor);
    $all_profesor = loadAllProfe($pdo);
    foreach ( $all_profesor as $p ) {
        if ( $p['nombre'] == $array_profesor[0] && 
             $p['apellido1'] == $array_profesor[1] && 
             $p['apellido2'] == $p['apellido2']) {
                $profesor_id = $p['profesor_id'];
        }
    }

    $listado_pruebas = ['Circulación', 'Circuito cerrado', 'Teórico'];

    //DEVOLVER RESULTADO

    //si no se encuentra el profesor o el tipo de prueba es incorecto, devolvemos el error
    if ( $profesor_id == "" ) {
        $error = new stdClass();
        $error->id    = -1;
        $error->texto = "Profesor no encontrado";
        $json_error   = json_encode($error);
        echo ($json_error);
    }  elseif ( ! in_array( $tipo_prueba, $listado_pruebas )) {
        $error = new stdClass();
        $error->id    = -1;
        $error->texto = "Tipo de pueba incorrecta";
        $json_error   = json_encode($error);
        echo ($json_error);
    } else {

        //a) correcto
        $stmt = $pdo->prepare('INSERT INTO Relacion_examen ( profesor_id,
                                                        fecha_presentacion,
                                                        fecha_examen,
                                                        tipo_prueba                                                        )
                                    VALUES (:pid, :fec, :fee, :tip)'
                                    );
            $stmt->execute(array(
                ':pid' => $profesor_id,
                ':fec' => $fecha_presentacion,
                ':fee' => $fecha_examen,
                ':tip' => $tipo_prueba
                )
            );
            $relacion_id = $pdo->lastInsertId();

        $respuesta = new stdClass();
        $respuesta->id    = 0;
        $respuesta->texto = "Relación de examen, creada";
        $respuesta->relacion_id = $relacion_id;
        $json_respuesta   = json_encode($respuesta);
        echo ($json_respuesta);

    }

    
    // b) Incorrecto
  } catch (Exception $e) {
    $error = new stdClass();
    $error->id    = -1;
    $error->texto = $e->getMessage();
    $json_error   = json_encode($error);
    echo ($json_error);
  }   
?>