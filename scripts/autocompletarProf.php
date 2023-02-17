<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

$stmt = $pdo->prepare('SELECT * FROM Profesor WHERE nombre LIKE :prefix');
$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
$retval = array();
while ( $profe = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $profe['nombre']." ".$profe['apellido1']." ".$profe['apellido2'];
};

echo $retval;

//echo(json_encode($retval, JSON_PRETTY_PRINT));

?>