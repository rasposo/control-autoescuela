<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}


require_once '../../scripts/funciones.php';
require_once '../../scripts/claseCurso.php';
require_once '../../scripts/clasePago.php';

if ( isset ($_GET['pago_id']) ) {
    $motivo = $_POST['motivo'];
    $pago = new Pago($_GET['pago_id']);
    $pago->loadFromDB($pdo);
    $curso_id = $pago->getCursoId();
    $pago->deleteFromDB($pdo, $motivo);

    header( "Location: ../../paginas/curso.php?curso_id=".$curso_id );

} else {
    header( "Location: ../../paginas/cursos.php" );
}

?>