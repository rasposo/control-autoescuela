<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/funciones.php';
require_once '../scripts/claseCurso.php';
require_once '../scripts/claseProfesor.php';
require_once '../scripts/claseAlumno.php';

require_once 'header.html';
require_once 'head_side.html';

$cursos = searchCursoInCurse($pdo);

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Cursos</h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <!-- Tabla de resultados-->
                    <div class="card shadow mb-4" id='tabla_profes'>
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cursos en proceso</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Curso</th><th>Alumno</th><th>Profesor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpo_tabla">
                                    <?php
                                    //hacemos el cuadro de cursos
                                    foreach ( $cursos as $curso ) {
                                        $profe = loadProfeById( $pdo, $curso->getProfesorId() );
                                        $alu = loadAlumById( $pdo, $curso->getAlumnoId() );
                                        $stmt = $pdo->prepare('SELECT tipo FROM Permiso WHERE permiso_id = :id' );
                                        $stmt->execute(array( ':id' => $curso->getPermisoId() ));
                                        $permiso = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo "<tr><td><a href=\"curso.php?curso_id=".$curso->getID()."\">".$permiso['tipo'] ."</td>";
                                        echo "<td><a href=\"alumno.php?alumno_id=".$curso->getAlumnoId()."\">".$alu['nombre']." ".$alu['apellido1']." ".$alu['apellido2']."</a></td>";
                                        echo "<td><a href=\"profesor.php?profesor_id=".$curso->getProfesorId()."\">".$profe['nombre']." ".$profe['apellido1']." ".$profe['apellido2']."</a></td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="cursos-historico.php" class="btn btn-primary btn-icon-split">
                                <span class="text">Histórico cursos</span>
                            </a>
                        </div>

<?php require_once 'footer.html';?>