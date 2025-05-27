<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

if ($_SESSION['perfil'] !== 'profesor') {
    die('ACCESS DENIED');
}


require_once '../scripts/claseProfesor.php';

require_once 'header.html';
require_once 'head_side.html';
?>

<!-- Begin Page Content -->
<div class="container-fluid" style="background-image: url('../img/logo_difu_sin.png'); background-repeat: no-repeat; background-position: center;">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Panel de administración</h1>

    <div class="row justify-content-around py-5">
        <!-- Alumnos Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-bottom-dark shadow-lg h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="alumnos.php">Alumnos</a></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profesores Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-bottom-secondary shadow-lg h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="profesores.php">Profesores</a></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagos Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-bottom-success shadow-lg h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="pagos.php">Pagos</a></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-around pt-4 pb-5">
        <!-- Cursos Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-bottom-info shadow-lg h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="cursos.php">Cursos</a></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-solid fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Examenes Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-bottom-warning shadow-lg h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="examenes.php">Examenes</a></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fa-tasks fa-2x text-gray-300"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-bottom-danger shadow-lg h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="configuracion.php">Configuración</a></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            

<?php require_once 'footer.html'; ?>

