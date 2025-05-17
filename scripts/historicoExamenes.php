<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseProfesor.php';

$inicio = $_POST['inicio'];
$final = $_POST['final'];

$examenes = [];
$stmt = $pdo->prepare('SELECT * FROM Relacion_examen WHERE fecha_examen BETWEEN :ini and :fin' );
$stmt->execute(array( ':ini' => $inicio,
                      ':fin' => $final 
                    ));
while ($i = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    //añdimos el nombre del profesor
    $profesor = new Profesor($i['profesor_id']);
    $profesor->loadFromDB($pdo);
    $nombre_profesor = $profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getapellido2();
    $i['profesor'] = $nombre_profesor;
    //cambiamos el formato de la fecha
    $i['fecha_examen'] = date("d/m/Y", strtotime($i['fecha_examen']));
    
    //añadimos el examen al array por el principio, para que se ordenen desde el más nuevo
    array_unshift($examenes, $i);
  };

echo (json_encode($examenes, JSON_PRETTY_PRINT));

?>