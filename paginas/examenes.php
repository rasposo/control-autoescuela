<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'header.html';
require_once 'head_side.html';
?>

<!-- Begin Page Content -->
<div class="container-fluid" style="height: 81vh">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Exámenes</h1>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">
  <br>

  <!-- Histórico de examenes -->
  <h2 class="h4 mb-4 text-gray-800">Búsqueda por fecha de convocatoria</h2>
  <form class="user" method="POST">
      <div class="form-group row">
          <div class="col-sm-2">
              <input type="date" class="form-control form-control-user" id="fecha-inicial-examen"
                  placeholder="Desde...">
                  <span>Desde:</span>
          </div>
          <div class="col-sm-2">
          <input type="date" class="form-control form-control-user" id="fecha-final-examen"
                  placeholder="Hasta...">
                  <span>Hasta:</span>
          </div>
          <div class="col-sm-2">
              <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIhistoricoExamenes()">
                  <span class="text">Buscar</span>
              </a>
          </div>
      </div>
  </form>
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
                        <th>Fecha</th><th>Profesor</th><th>Tipo de examen</th><th></th>
                    </tr>
                </thead>
                <tbody id="cuerpo_tabla">
                </tbody>
            </table>
        </div>
    </div>
</div>


  <div class="container-fluid">
      <a href="nuevo_examen.php" class="btn btn-primary btn-icon-split" style="margin-left: 20px">
          <span class="text">Nueva relación de exámen</span>
      </a>
  </div>
  <br>

  <hr class="sidebar-divider my-0">
  <br>
</div>


<?php require_once 'footer.html'; ?>