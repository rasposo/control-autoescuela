<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseAlumno.php';
require_once 'claseCurso.php';
require_once 'claseClase.php';

$inicio = $_POST['inicio'];
$final = $_POST['final'];

$pagos = [];
$stmt = $pdo->prepare('SELECT * FROM Pago WHERE fecha BETWEEN :ini and :fin' );
$stmt->execute(array( ':ini' => $inicio,
                      ':fin' => $final 
                    ));
while ( $i = $stmt->fetch(PDO::FETCH_ASSOC) ) {

    //cremamos las instancias y añadimos la información al array $i
    $curso = new Curso( $i['curso_id'] );
    $curso->loadFromDB( $pdo );
    $alumno = new Alumno( $curso->getAlumnoId() );
    $alumno->loadFromDB( $pdo );
    $permiso = loadPermisoById( $pdo, $curso->getPermisoId() );
    $permiso_tipo = $permiso['tipo'];

    $i['curso_id']         = $curso->getId();
    $i['numero_curso']     = $curso->getNumeroCurso();
    $i['permiso']          = $permiso_tipo;
    $i['alumno_id']        = $alumno->getId();
    $i['nombre_alumno']    = $alumno->getNombre();
    $i['apellido1_alumno'] = $alumno->getApellido1();
    $i['apellido2_alumno'] = $alumno->getApellido2();

    $pagos[] = $i;
}

echo (json_encode($pagos, JSON_PRETTY_PRINT));

?>