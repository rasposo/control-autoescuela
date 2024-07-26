<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseCurso.php';

//recuperamos valores
$curso_id = $_GET['curso_id'];

//creamos la instacia
$curso = new Curso($curso_id);
$curso->loadFromDB($pdo);

//cambiamos el valor de finalizado
$curso->setFinalizado(0);

//guardamos la instacia
$curso->saveIntoDB($pdo);

header("Location: ../paginas/curso.php?curso_id=".$curso_id);

?>
