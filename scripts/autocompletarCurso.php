<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

$stmt = $pdo->prepare('SELECT * FROM Permiso' );
$stmt->execute();
$retval = array();
while ( $curso = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $curso['tipo'];
};

echo(json_encode($retval, JSON_PRETTY_PRINT));

?>