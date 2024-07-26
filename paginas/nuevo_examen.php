<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/claseAlumno.php';
require_once '../scripts/claseProfesor.php';
require_once '../scripts/claseCurso.php';
require_once '../scripts/funciones.php';

require_once 'header.html';
require_once 'head_side.html';


$profesores = loadAllProfe($pdo);
$lista_profes = [];
foreach ( $profesores as $profesor ) {
    $lista_profes[] = $profesor['nombre']." ".$profesor['apellido1']." ".$profesor['apellido2'];
};

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Nueva relación de examen</h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <div class="container-fluid">

                    <!-- Formulario -->

                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-2">Fecha  de presentación:
                                <input type="date" class="form-control form-control-user" id="fecha-presentacion"
                                    placeholder="Fecha de presentación">
                            </div>
                            <div class="col-sm-2">Fecha de examen:
                                <input type="date" class="form-control form-control-user" id="fecha-examen"
                                    placeholder="Fecha de examen">
                            </div>
                        </div>                    
                        <div class="form-group row">
                            <div class="col-sm-4">Profesor:
                                <input type="text" class="form-control form-control-user" id="profesor"
                                    placeholder="Profesor">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">Tipo de prueba:
                                <input type="text" class="form-control form-control-user" id="tipo-prueba"
                                    placeholder="Tipo de prueba">
                            </div>
                        </div>
                     </form>
                </div>

                <hr class="sidebar-divider my-0">
                        <br>
            </div>

            <!-- Boton para guardar -->
             <div class="container-fluid">
                <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPInuevaRelacionExamen()">
                    <span class="icon text-white-50">
                        <i class="fa fa-save"></i>
                    </span>
                    <span class="text">Guardar</span>
                </a>
            <!-- cancelar -->
                <a href="examenes.php" class="btn btn-primary btn-icon-split">
                    <span class="text">Cancelar</span>
                </a>



<?php require_once 'footer.html'; ?>

<script>
$("#profesor").autocomplete({
    minLength: 0,
    source: "../scripts/autocompletarProf.php"
});

const tipo_examen = ['Circulación', 'Circuito cerrado', 'Teórico'];
$("#tipo-prueba").autocomplete({
    minLength: 0,
    source: tipo_examen
});
</script>