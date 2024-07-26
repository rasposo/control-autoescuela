<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseCurso.php';

try {
  $busqueda = [];

  //Buscamos en la base de datos, primero con nombre y después con apellido
  if ( isset ($_POST['nombre'])) {
    $search = buscarAlu($pdo, $_POST['nombre']);
  };

  if ( isset ($_POST['alumno_id'])) {
    $search = [];
    $result = loadAlumById ($pdo, $_POST['alumno_id']);
    $search[] = $result;
  };

  
  //Buscamos ahora los cursos en los que está matriculado y lo añadimos al alumno
  foreach( $search as $alum ) {
    $curso = searchCursoByAlumId($pdo, $alum['alumno_id'] );
    $alum['cursos'] = $curso;
    $busqueda[] = $alum;
  }

  //devolver resultado correcto
  $respuesta = new stdClass();
  $respuesta->id  = 0;
  $respuesta->texto = "Lista alumnos";
  $respuesta->search = $busqueda;
  $json_respuesta   = json_encode($respuesta);
  echo ($json_respuesta);


  } catch (Exception $e) {
    $error = new stdClass();
    $error->id    = -1;
    $error->texto = $e->getMessage();
    $json_error   = json_encode($error);
    echo ($json_error);
  }


?>