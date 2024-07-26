<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}


require_once '../../scripts/funciones.php';
require_once '../../scripts/claseCurso.php';
require_once '../../scripts/claseCargo.php';

if ( isset ($_GET['cargo_id']) ) {
    $cargo = new Cargo($_GET['cargo_id']);
    $cargo->loadFromDB($pdo);
    $curso_id = $cargo->getCursoId();
    $cargo->deleteFromDB($pdo);

    header( "Location: ../../paginas/curso.php?curso_id=".$curso_id );

} else {
    header( "Location: ../../paginas/cursos.php" );
}

?>