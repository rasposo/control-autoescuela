<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

$profe = buscarProfe($pdo, $_REQUEST['term']);
$profes = [];

foreach ( $profe as $p ) {
  $profesor = $p['nombre']. " ".$p['apellido1']." ".$p['apellido2'];
  $profesores [] = $profesor;
}

echo(json_encode($profesores, JSON_PRETTY_PRINT));

?>