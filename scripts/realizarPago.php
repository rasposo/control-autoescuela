<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'clasePago.php';
require_once 'claseCurso.php';
require_once 'claseAlumno.php';

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    $curso_id = $_POST['curso_id'];
    $concepto = $_POST['concepto'];
    $importe =  $_POST['importe'];

    
    // PARTE 2. Obtenemos fecha
    $fecha = getDate();
    $fecha_actual = $fecha['year']."/".$fecha['mon']."/".$fecha['mday'];
  

    //PARTE 3. Realizamos el pago
    $pago = new Pago();

    $pago->setFecha( $fecha_actual );
    $pago->setCursoId( $curso_id );
    $pago->setImporte( $importe );
    $pago->setConcepto( $concepto );

    $pago->saveIntoDB($pdo);

    //PARTE 4. Recuperamos las instancias para pasarlas en la respuesta

    $pagoRealizado = new Pago( $pdo->lastInsertId() );
    $pagoRealizado->loadFromDB($pdo);

    $curso = new Curso( $curso_id );
    $curso->loadFromDB( $pdo );

    $alumno = new Alumno( $curso->getAlumnoId() );
    $alumno->loadFromDB($pdo);

    $datosAlumno = [];
    $datosAlumno['curso'] = $curso->getNumeroCurso();
    $datosAlumno['nombre'] = $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2();
    $datosAlumno['direccion'] = $alumno->getDireccion();
    $datosAlumno['localidad'] = $alumno->getCodigoPostal()." - ".$alumno->getLocalidad();
    $datosAlumno['provincia'] = $alumno->getProvincia();

    $datosRecibo = [];
    $datosRecibo['numero'] = $pagoRealizado->getNumeroRecibo();
    $datosRecibo['fecha'] = $pagoRealizado->getFecha();
    $datosRecibo['importe'] = $pagoRealizado->getImporte();
    $datosRecibo['concepto'] = $pagoRealizado->getConcepto();
      
    // PARTE 4. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Pago introducido";
    $respuesta->recibo = $datosRecibo;
    $respuesta->alumno = $datosAlumno;
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