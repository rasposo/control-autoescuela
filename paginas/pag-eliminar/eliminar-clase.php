<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}


require_once '../../scripts/funciones.php';
require_once '../../scripts/claseCurso.php';
require_once '../../scripts/claseClase.php';

if ( isset ($_GET['clase_id']) ) {
    $clase = new Clase($_GET['clase_id']);
    $clase->loadFromDB($pdo);
    $curso_id = $clase->getCursoId();
    $clase->deleteFromDB($pdo);

    header( "Location: ../../paginas/curso.php?curso_id=".$curso_id );

} else {
    header( "Location: ../../paginas/cursos.php" );
}

?>