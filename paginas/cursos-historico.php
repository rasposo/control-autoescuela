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

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Histórico Cursos</h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>
                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <input type="date" class="form-control form-control-user" id="fecha-inicial"
                                    placeholder="Desde...">
                                    <span>Desde:</span>
                            </div>
                            <div class="col-sm-2">
                            <input type="date" class="form-control form-control-user" id="fecha-final"
                                    placeholder="Hasta...">
                                    <span>Hasta:</span>
                            </div>
                            <div class="col-sm-2">
                                <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIhistoricoCursos()">
                                    <span class="text">Buscar</span>
                                </a>
                            </div>
                            <div class="col-sm-3">
                                <a href="cursos.php" class="btn btn-secondary btn-icon-split" style="margin-left: 25px;">
                                    <span class="text">Volver</span>
                                </a>
                            </div>
                        </div>
                    </form>
                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <!-- Tabla de resultados-->
                    <div class="card shadow mb-4" id='tabla_profes'>
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cursos finalizados</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Alumno</th><th>Curso</th><th>Fecha inicio</th><th>Fecha fin</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpo_tabla">
                                    </tbody>
                                </table>
                            </div>
                        </div>

<?php require_once 'footer.html';?>