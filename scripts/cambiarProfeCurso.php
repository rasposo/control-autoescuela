<?php
require_once '../scripts/funciones.php';

$curso = $_GET['curso_id'];
$profesor = $_GET['profesor_id'];

$stmt = $pdo->prepare('UPDATE Curso SET profesor_id = :pro WHERE curso_id = :cur');
$stmt->execute(array(
    ':pro' => $profesor,
    ':cur' => $curso
));

header ("Location: ../paginas/curso.php?curso_id=".$curso);
?>