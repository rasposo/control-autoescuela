<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

//Buscamos en la base de datos, primero con nombre y después con apellido
$search = [];
if ( isset ($_POST['nombre'])) {
  $search = buscarProfe($pdo, $_POST['nombre']);
} else {
  $search = loadAllProfe($pdo);
}
echo(json_encode($search, JSON_PRETTY_PRINT));

?>