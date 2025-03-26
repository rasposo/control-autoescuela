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

//cargamos instancias
$curso = new Curso($_GET['curso_id']);
$curso->loadFromDB($pdo);
$alumno = new Alumno($curso->getAlumnoId());
$alumno->loadFromDB($pdo);
$permiso = loadPermisoById($pdo, $curso->getPermisoId());

$nombre_alumno = $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2();

$hoy = date("d/m/Y");

?>


                <!-- Begin Page Content -->
                <div class="container-fluid" >

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><span style="color:brown">Nº <?= $curso->getNumeroCurso(); ?> </span>- <?= $nombre_alumno ?><span> - Permiso <?= $permiso['tipo'] ?></span></h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <h2 class="h4 mb-4 text-gray-600">Introducir pago</h2>

                    <!-- Formulario -->
                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-2">Fecha:
                                <input type="date" class="form-control form-control-user" id="fecha_pago" value="<?= $hoy ?>"
                                    placeholder="<?= $hoy ?>">
                            </div>
                            <div class="col-sm-2">Importe
                                <input type="number" class="form-control form-control-user" id="importe"
                                    placeholder="€" onkeyup="PasarValorImporte();">
                           </div>
                            <div class="col-sm-4 mb-3 mb-sm-0">Nombre
                                <input type="text" class="form-control form-control-user" id="concepto"
                                    placeholder="Concepto" onkeyup="PasarValorConcepto();">
                           </div>
                        </div>
                        <input type="hidden" id="nombre-alumno" value="<?= $nombre_alumno ?>">
                        <input type="hidden" id="nombre-curso" value="Curso <?= $permiso['tipo'] ?>">
                    </form>
                    <br>

                    <!-- Boton para introducir pago -->
                    <div class="container-fluid">
                        <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#introducirPago">
                            <span class="icon text-white-50">
                               <!-- <i class="fa fa-euro"></i> -->
                            </span>
                            <span class="text">Introducir pago</span>
                        </a>
                    <!-- cancelar -->
                        <a href="curso.php?curso_id=<?= $_GET['curso_id']; ?>" class="btn btn-secondary btn-icon-split">
                            <span class="text">Cancelar</span>
                        </a>
                    </div>
                    <br>

                </div>

<!-- Introducir pago-->
<div class="modal fade" id="introducirPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Desea introducir el siguiente pago?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Curso <?= $permiso['tipo'] ?></p>
                    <p>Alumno: <?= $nombre_alumno ?></p>
                    <p>Concepto: <input type="text" class="form-control form-control-user" id="confirmacion-concepto"></p>
                    <p>Importe: <input type="number" class="form-control form-control-user" id="confirmacion-importe"></p>
                    <p><input type="checkbox" name="recibo" id="recibo-pago" checked> Imprimir recibo </p>
                    <input type="hidden" id="curso_id" value="<?= $_GET["curso_id"]?>">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="#" onclick="introducirPago()">Introducir pago</a>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">

function PasarValorImporte()
    {
    document.getElementById("confirmacion-importe").value = document.getElementById("importe").value;
    }

function PasarValorConcepto()
    {
    document.getElementById("confirmacion-concepto").value = document.getElementById("concepto").value;
    }

</script>

<?php require_once 'footer.html';?>

