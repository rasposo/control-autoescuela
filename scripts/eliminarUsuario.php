<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseProfesor.php';
require_once 'claseAlumno.php';

if ( isset ($_GET['profesor_id']) ) {
    $profesor = new Profesor($_GET['profesor_id']);
    $profesor->loadFromDB($pdo);

    $profesor->deleteFromDB($pdo);
    header ("Location: ../paginas/indice.php");
}

if ( isset ($_GET['alumno_id']) ) {
    $alumno= new Alumno($_GET['alumno_id']);
    $alumno->loadFromDB($pdo);

    $alumno->deleteFromDB($pdo);
    header ("Location: ../paginas/indice.php");
}

?>