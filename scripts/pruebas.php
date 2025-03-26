<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}



require_once '../scripts/claseCurso.php';
require_once '../scripts/claseAlumno.php';
require_once '../scripts/claseProfesor.php';
require_once '../scripts/claseClase.php';
require_once '../scripts/funciones.php';

$curso_id = 61;
$curso = new Curso($curso_id);
$curso->loadFromDB($pdo);
$seccion = $curso->getSeccion();
$autoescuela = loadAutoescuelaBySeccion($pdo, $seccion);

var_dump($seccion);
print_r($autoescuela);


?>