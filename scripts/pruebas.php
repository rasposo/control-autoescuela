<?php
require_once '../scripts/claseCurso.php';
require_once '../scripts/claseAlumno.php';
require_once '../scripts/claseProfesor.php';
require_once '../scripts/funciones.php';

$permiso = loadPermisoById($pdo, 1);
print_r($permiso)

?>