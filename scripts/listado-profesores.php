<?php

session_start();
require_once 'funciones.php';

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
  }
  ?>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<!-- jQuery ui CSS-->
<link href="vendor/jquery-easing/jquery-ui.min.css" rel="stylesheet" type="text/css">
<!-- jQuery ui JS-->
<script src="vendor/jquery-easing/jquery-ui.min.js"></script>

<?php

$stmt = $pdo->prepare('SELECT nombre, apellido1 FROM Profesor
    WHERE nombre LIKE :prefix');
$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));

$retval = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = htmlentities($row['nombre']." ".$row['apellido1']);
}

echo(json_encode($retval, JSON_PRETTY_PRINT));

?>