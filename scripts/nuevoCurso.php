<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseCurso.php';

$curso = new Curso();
try {
  //configuramos el curso con lo básico
  $curso->setAlumnoId($_POST['alumno_id']);
  $permiso = $_POST['curso'];

  $permiso_id = loadPermisoByTipo($pdo, $permiso);
  $curso->setPermisoId($permiso_id['permiso_id']);

  $curso->setFinalizado(0);
  $curso->setPagado(0);
  $curso->setProfesorId(2);

  $hoy = date("Y/m/d");
  $curso->setFechaInicio($hoy);

  //guardamos los parámetros
  $curso->saveIntoDB($pdo);

  //cargamos el curso de nuevo
  $curso->loadFromDB($pdo);



  //DEVOLVER RESULTADO
	// a) Correcto
  $respuesta = new stdClass();
  $respuesta->id    = 0;
  //$respuesta->post = $_POST['nuevoCurso'];
  $respuesta->texto = "Profesor guardado";
  $respuesta->curso_id = $curso->getId();
  $json_respuesta   = json_encode($respuesta);
  echo ($json_respuesta);
  
  // b) Incorrecto
} catch (Exception $e) {
  $error = new stdClass();
  $error->id    = -1;
  $error->texto = $e->getMessage();
  $json_error   = json_encode($error);
  echo ($json_error);
}   

?>