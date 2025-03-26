<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

try {
    //recuperamos parámetros
    $stringProfes = $_POST['profes'];
    $profes = explode(",", $stringProfes);
    $curso_id = $_POST['curso_id'];

    //borramos todas las entradas antiguas en Curso-Profe
    $stmt = $pdo->prepare('DELETE FROM Curso_profesor WHERE curso_id = :id');
    $stmt ->execute(array( ':id' => $curso_id ));

    //adjudicamos los profesores al curso
    foreach ($profes as $profe) {
        $stmt = $pdo->prepare('INSERT INTO Curso_profesor (curso_id, profesor_id) VALUES (:cui, :poi)');
        $stmt->execute(array( 
            ':cui' => $curso_id, 
            ':poi' => $profe 
            )
        );
    };
    //devolver resultado correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Profesores actualizados";
    $json_respuesta   = json_encode($respuesta);
    echo ($json_respuesta);

//devolver resultado incorrecto
} catch (Exception $e) {
    $error = new stdClass();
    $error->id    = -1;
    $error->texto = $e->getMessage();
    $json_error   = json_encode($error);
    echo ($json_error);
}   

?>