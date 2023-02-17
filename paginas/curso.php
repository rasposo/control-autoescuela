<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/claseCurso.php';
require_once '../scripts/claseAlumno.php';
require_once '../scripts/claseProfesor.php';
require_once '../scripts/funciones.php';

require_once 'header.html';
require_once 'head_side.html';

$curso = new Curso($_GET['curso_id']);
$curso->loadFromDB($pdo);
$alumno = new Alumno($curso->getAlumnoId());
$alumno->loadFromDB($pdo);
$permiso = loadPermisoById($pdo, $curso->getPermisoId());

$profesor = $curso->getProfesorId();
if ( $profesor != null) {
    $profesor = new Profesor($curso->getProfesorId());
    $profesor->loadFromDB($pdo);
    $profesor_name = $profesor->getNombre()." ".$profesor->getApellido1();
} else {
    $profesor_name = "";
}

if ( $curso->getPagado() == 1 ) {
    $pagado = "checked";
} else {
    $pagado = "";
}

if ( $curso->getFinalizado() == 1 ) {
    $finalizado= "checked";
} else {
    $finalizado = "";
}


$todos_profes = loadAllProfe($pdo);
$lista = [];
if ( $todos_profes !== null ) {
    foreach ($todos_profes as $profe) {
        $item = $profe['nombre']." ".$profe['apellido1']." ".$profe['apellido2'];
        $lista[] = $item;
        $lista_profes = (json_encode($lista, JSON_PRETTY_PRINT));
    }
} else { $lista_profes = [];}

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Curso <?= $permiso[0]['tipo'] ?>
                    <br><br>
                    <?= $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2() ?></h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <div class="container-fluid">

                    <h2 class="h4 mb-4 text-gray-600">Profesor adjudicado</h2>

                        <div class="form-group row">
                            <div class="col-sm-4 mb-3 mb-sm-0">
                                <p><?= $profesor_name; ?></p>
                            </div>
                            <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" 
                                data-target="#adjudicarProfesor" onclick="cambiarProfesor()" >
                                    <span class="icon text-white-50">
                                    <i class="fas fa-user-alt"></i>
                                </span>
                                <span class="text">Cambiar Profesor</span>
                            </a>
                        </div>

                        <!-- Divider -->
                        <hr class="sidebar-divider my-0">
                        <br>

                        <!-- Formulario -->

                        <h2 class="h4 mb-4 text-gray-600">Datos del curso</h2>

                        <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-2">Fecha  de inicio:
                                <input type="date" class="form-control form-control-user" id="inicio" value="<?= $curso->getFechaInicio() ?>"
                                            placeholder="Inicio">
                            </div>
                            <div class="col-sm-2">Fecha  de finalización:
                                <input type="date" class="form-control form-control-user" id="fecha_finalizacion" value="<?= $curso->getFechaFinalizacion() ?>"
                                    placeholder="Finalización">
                            </div>
                        </div>
                        <div id="finalizar">
                            <div class="form-group row">
                                <div class= "col-sm-2">
                                    <input type="checkbox" id="finalizado" name="dato_curso[]" value="finalizado" <?= $finalizado ?>>
                                    <label for="finalizado">Finalizado</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-2">
                                    <input type="checkbox" id="pagado" name="dato_curso[]" value="pagado" <?= $pagado ?>>
                                    <label for="pagado">Pagado</label>
                                </div>
                            </div>
                        </div>
                        <hr class="sidebar-divider my-0">
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-2">Fecha Teórico:
                                <input type="date" class="form-control form-control-user" id="teorico" value="<?= $curso->getFechaExamenTeorico() ?>"
                                    placeholder="F. Teórico">
                           </div>
                           <div class="col-sm-2">Fecha Destreza:
                                <input type="date" class="form-control form-control-user" id="destreza" value="<?= $curso->getFechaExamenDestreza() ?>"
                                    placeholder="F. Destreza">
                           </div>
                           <div class="col-sm-2">Fecha Circulación:
                                <input type="date" class="form-control form-control-user" id="circulacion" value="<?= $curso->getFechaExamenCirculacion()?>"
                                    placeholder="Circulacion">
                           </div>
                        </div>

                        <input type="hidden" id="alumno_id" value=<?= $alumno->getID() ?>>
                        <input type="hidden" id="curso_id" value=<?= $curso->getId() ?>>
                        <input type="hidden" id="profesor_id" value=<?= $curso->getProfesorId() ?>>
                        <input type="hidden" id="permiso_id" value=<?= $curso->getPermisoId() ?>>
                    </form>
                </div>
                <hr class="sidebar-divider my-0">
                <br>
            </div>

            <!-- Boton para guardar -->
             <div class="container-fluid">
                <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIguardarCurso()">
                    <span class="icon text-white-50">
                        <i class="fa fa-save"></i>
                    </span>
                    <span class="text">Guardar</span>
                </a>
            <!-- cancelar -->
                <a href="alumnos.php" class="btn btn-primary btn-icon-split">
                    <span class="text">Cancelar</span>
                </a>
            </div> 
            <br>

<!-- Adjudicar profesor -->
<div class="modal fade" id="adjudicarProfesor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adjudicar Profesor</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="user" method="POST">
                    <div class="col-sm-8" id="profesores">Seleccionar profesor:
                    </div>                   
                </form>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
</div>

<?php require_once 'footer.html'; ?>