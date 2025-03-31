<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}


require_once '../../scripts/funciones.php';
require_once '../../scripts/claseCurso.php';
require_once '../../scripts/claseProfesor.php';
require_once '../../scripts/claseAlumno.php';
require_once '../../scripts/claseClase.php';

if ( isset ($_GET['clase_id']) ) {
    $clase = new Clase($_GET['clase_id']);
    $clase->loadFromDB($pdo);
    $curso = new Curso($clase->getCursoId());
    $curso->loadFromDB($pdo);
    $alumno = new Alumno($curso->getAlumnoId());
    $alumno->loadFromDB($pdo);

    $fecha = $clase->getFecha();
    $hora = $clase->getHora();
    if ( $hora == 0 ) {
        $hora = "";
    }
    $enseñanza = loadEnseñanzaById($pdo, $clase->getEnseñanzaId());
    $tipo = $enseñanza['tipo'];
    $importe = $enseñanza['precio'];
    $nombre = $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2();


} else {
    header("Location: ../paginas/cursos.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Eliminar cargo</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="indice.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Control</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="indice.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Administración</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Apartados
            </div>

            <!-- Nav Item - Alumnos -->
            <li class="nav-item">
                <a class="nav-link" href="alumnos.php">
                    <i class="fas fa-user-graduate  fa-chart-area"></i>
                    <span>Alumnos</span></a>
            </li>

            <!-- Nav Item - Profesores -->
            <li class="nav-item">
                <a class="nav-link" href="profesores.php">
                    <i class="fas fa-user-alt fa-table"></i>
                    <span>Profesores</span></a>
            </li>

            <!-- Nav Item - Cursos -->
            <li class="nav-item">
                <a class="nav-link" href="cursos.php">
                    <i class="fas fa-solid fa-book-open"></i>
                    <span>Cursos</span></a>
            </li>

            <!-- Nav Item - Pagos -->
            <li class="nav-item">
                <a class="nav-link" href="pagos.php">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Pagos</span></a>
            </li>

            <!-- Nav Item - Informes-->
            <li class="nav-item">
                <a class="nav-link" href="informes.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Informes</span></a>
            </li>            

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?= $_SESSION['name'] ?> </span>
                                <img class="img-profile rounded-circle"
                                    src="../../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Ajustes
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Salir
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->


                <!-- Begin Page Content -->
                <div class="container-fluid" >

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Eliminar clase </h1>
                    <br>


                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <div class="container-fluid">
                        <h4 class="h4 mb-4 text-gray-600">¿Seguro que desea eliminar la siguiente clase?</h4>
                        <br>
                        <!-- Contenido en columnas -->
                        <div class="row">

                            <!-- tabla de clases -->
                            <div class="col-lg-8 mb-6">

                                <div class="card shadow mb-6">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Clase</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Alumno</th><th>Fecha</th><th>Hora</th><th>Tipo</th><th>Importe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                echo('<tr><td>'.$nombre.'</td><td>'.$fecha.'</td><td>'.$hora.'</td><td>'.$tipo.'</td><td>'.$importe.'€</td></tr>');
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <h4 class="h4 mb-4 text" style="color: red">Esta acción no se puede deshacer</h4>
                        <br>
                        <!-- Boton de elimimar clase -->
                        <a href="eliminar-clase.php?clase_id=<?= $clase->getId() ?>" class="btn btn-primary btn-icon-split" id="eliminar-clase-button">
                            <span class="text">Eliminar</span>
                        </a>
                        <!-- Boton de cancelar -->
                        <a href="../curso.php?curso_id=<?= $curso->getId() ?>" class="btn btn-primary btn-icon-split" id="cancelar-button">
                            <span class="text">Cancelar</span>
                        </a>
                    </div>
                    <br>
                </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Josefo</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- jQuery ui CSS-->
        <link href="../../vendor/jquery-easing/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery ui JS-->
    <script src="../../vendor/jquery-easing/jquery-ui.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Funciones JS-->
    <script src="../../scripts/jsFunctions.js"></script>

</body>

</html>