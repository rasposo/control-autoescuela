<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseAlumno.php';

$inicio = $_POST['inicio'];
$final = $_POST['final'];

$cursos = [];
$stmt = $pdo->prepare('SELECT * FROM Curso WHERE fecha_finalizacion BETWEEN :ini and :fin' );
$stmt->execute(array( ':ini' => $inicio,
                      ':fin' => $final 
                    ));
while ($i = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  if ($i['finalizado'] == 1) {
    $alumno = new Alumno($i['alumno_id']);
    $alumno->loadFromDB($pdo);
    $i['alumno_id'] = $alumno->getId();
    $i['nombre_alumno'] = $alumno->getNombre();
    $i['apellido1_alumno'] = $alumno->getApellido1();
    $i['apellido2_alumno'] = $alumno->getApellido2();
    $permiso = loadPermisoById($pdo, $i['permiso_id']);
    $i['permiso'] = $permiso['tipo'];
    //añadimos el curso al array por el principio, para que se ordenen desde el más nuevo
    array_unshift($cursos, $i);
  };
}

echo (json_encode($cursos, JSON_PRETTY_PRINT));

?>