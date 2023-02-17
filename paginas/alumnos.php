<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/funciones.php';

require_once 'header.html';
require_once 'head_side.html';
?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Alumnos</h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <div class="container-fluid">
                        <form action="#" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-gradient-light border-1 small" placeholder="Buscar..."
                                    aria-label="Search" aria-describedby="basic-addon2" id="prof-alumn-find" >
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="llamarAPIbuscarAlumno()" id="boton">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                    </form>
                    <a href="nuevoAlumno.php?alumno_id=0" class="btn btn-primary btn-icon-split" style="margin-left: 20px">
                            <span class="text">Nuevo alumno</span>
                        </a>
                    </div>
                    <br>

                    <hr class="sidebar-divider my-0">
                    <br>
                </div>

                <!-- Tabla de resultados-->
                <div class="card shadow mb-4" id='tabla_profes'>
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Resultado búsqueda</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpo_tabla">
                                    </tbody>
                                </table>
                            </div>
                        </div>

<?php require_once 'footer.html';?>