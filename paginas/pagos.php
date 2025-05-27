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
                    <h1 class="h2 mb-4 text-gray-800">Pagos</h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <!-- Realizar pago -->
                    <h2 class="h4 mb-4 text-gray-800">Introducir pago</h2>

                    <div class="container-fluid">
                        <form action="#" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <span style="margin-right: 8px;">Buscar alumno</span>
                                <input type="text" class="form-control bg-gradient-light border-1 small" placeholder="Buscar..."
                                    aria-label="Search" aria-describedby="basic-addon2" id="prof-alumn-find" >
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="llamarAPIbuscarAlumnoPago()" id="boton">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>

                    <!-- Tabla de resultados-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Resultado búsqueda</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th><th>Introducir pago en el curso</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpo_tabla">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <!-- Histórico de pagos -->
                    <h2 class="h4 mb-4 text-gray-800">Histórico de pagos</h2>
                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <input type="date" class="form-control form-control-user" id="fecha-inicial-pago"
                                    placeholder="Desde...">
                                    <span>Desde:</span>
                            </div>
                            <div class="col-sm-2">
                            <input type="date" class="form-control form-control-user" id="fecha-final-pago"
                                    placeholder="Hasta...">
                                    <span>Hasta:</span>
                            </div>
                            <div class="col-sm-2">
                                <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIhistoricoPagos()">
                                    <span class="text">Buscar</span>
                                </a>
                            </div>
                        </div>
                    </form>
                    <br>

                    <!-- Tabla de resultados-->
                    <div class="card shadow mb-4" id='tabla_profes'>
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pagos realizados en el periodo</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Número</th><th>Alumno</th><th>Curso</th><th>Fecha</th><th>Concepto</th><th>Importe</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpo_tabla-pagos">
                                    </tbody>
                                </table>

                                <!-- carta con el importe total -->
                                <div class="col-xl-3 col-md-6 mb-4">
                                  <div class="card border-left-success shadow h-100 py-2">
                                      <div class="card-body">
                                          <div class="row no-gutters align-items-center">
                                              <div class="col mr-2">
                                                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                      Total pagado</div>
                                                  <div class="h5 mb-0 font-weight-bold text-gray-800" id="montante"></div>
                                              </div>
                                              <div class="col-auto">
                                                  <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <!-- Pagos anulados -->
                    <h2 class="h4 mb-4 text-gray-800">Pagos anulados</h2>
                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <input type="date" class="form-control form-control-user" id="fecha-inicial-pago-anulado"
                                    placeholder="Desde...">
                                    <span>Desde:</span>
                            </div>
                            <div class="col-sm-2">
                            <input type="date" class="form-control form-control-user" id="fecha-final-pago-anulado"
                                    placeholder="Hasta...">
                                    <span>Hasta:</span>
                            </div>
                            <div class="col-sm-2">
                                <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIhistoricoPagosAnulados()">
                                    <span class="text">Buscar</span>
                                </a>
                            </div>
                        </div>
                    </form>
                    <br>

                    <!-- Tabla de resultados-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pagos anulados en el periodo</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tabla-pagos-anulados" width="80%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Número</th><th>Alumno</th><th>Curso</th><th>Fecha</th><th>Motivo anulación</th><th>Importe</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpo_tabla-pagos-anulados">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

<?php require_once 'footer.html';?>

<script>
    $("#prof-alumn-find").autocomplete({
        source: "../scripts/autocompletarAlumnos.php",
        minLength: 2
    });
</script>