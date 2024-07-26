<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

$alum = buscarAlu($pdo, $_REQUEST['term']);
$alumnos = [];

foreach ( $alum as $a ) {
    $alumno = $a['nombre']." ".$a['apellido1']." ".$a['apellido2'];
    $alumnos[] = $alumno;
}

echo(json_encode($alumnos, JSON_PRETTY_PRINT));


?>