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

$relacion_id = $_GET['relacion_id'];
$relacion = loadRelacionById($pdo, $relacion_id);

//cargamos profesor
$profesor_id = $relacion['profesor_id'];
$profesor = new Profesor($profesor_id);
$profesor->loadFromDB($pdo);
$nombre_profesor = $profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getapellido2();

//corregimos fechas con el formato DD-MM-AA
$date = explode("-", $relacion['fecha_examen']);
$fecha_examen = $date[2]."-".$date[1]."-".$date[0];

$date2 = explode("-", $relacion['fecha_presentacion']);
$fecha_presentacion = $date2[2]."-".$date2[1]."-".$date2[0];

//cargamos los alumnos que se presentan a la convocatoria
$lista_alumnos = loadExamenByRelacionId($pdo, $relacion_id);

//cargamos vehículos
$vehiculos = loadAllVehiculos($pdo);

require_once 'header.html';
require_once 'head_side.html';

?>

<!-- Begin Page Content -->
<div class="container-fluid" style="height: 81vh;">



  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Exámen</h1>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">
  <br>

  <div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
        <thead>
            <tr>
            <th>Profesor</th><th>Fecha de examen</th><th>Fecha de presentación</th><th>Tipo de prueba</th>
            </tr>
        </thead>
        <tbody>
          <tr>
            <td><?= $nombre_profesor ?></td>
            <td><?= $fecha_examen ?></td>
            <td><?= $fecha_presentacion ?></td>
            <td><?= $relacion['tipo_prueba'] ?></td>
          </tr>
        </tbody>
    </table>
</div>


<form action="#">
<input type="hidden" id="relacion_id" value="<?= $relacion_id ?>">
<input type="hidden" id="profesor_id" value="<?= $profesor_id ?>">
<input type="hidden" id="fecha_presentacion" value="<?= $fecha_presentacion ?>">
<input type="hidden" id="tipo_prueba" value="<?= $relacion['tipo_prueba'] ?>">
</form>



  <!-- Tabla de presentados a examen-->
  <div class="card shadow mb-4" id='tabla_profes'>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Relacion de alumnos</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                <thead>
                    <tr>
                    <th>Nº</th>
                    <th>DNI</th>
                    <th>Apellidos y nombre</th>
                    <th>Permiso</th>
                    <th>Seccion</th>
                    <th>D.C.</th>
                    <?php
                    if ( $relacion['tipo_prueba'] == 'Circulación') {
                        echo('<th>Vehículo</th>');
                    }
                    ?>
                    <th>Estado</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody id="cuerpo_tabla_ex">
                  <?php
                  $numero = 1;
                  foreach ( $lista_alumnos as $examen ) {

                    $curso = new Curso($examen['curso_id']);
                    $curso->loadFromDB($pdo);
                    $permiso = loadTipoPermisoById($pdo, $curso->getPermisoId());
                    $alumno = new Alumno($curso->getAlumnoId());
                    $alumno->loadFromDB($pdo);
                    $nombre_alumno = $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2();
                    $DNI = $alumno->getDNI();

                    $autoescuela = loadAutoescuela($pdo, $examen['autoescuela_id']);
                    $seccion = $autoescuela['seccion'];
                    $DC = $autoescuela['DC'];

                    //damos el valor a vehículo si no es teórico
                    if ( $relacion['tipo_prueba'] == 'Circulación') {
                        if ( isset ($examen['vehiculo_id']) ) {
                            $vehiculo =  loadVehiculoById($pdo, $examen['vehiculo_id'])['matricula'];
                        } else {
                            $vehiculo = '<a href="#" class="abrirModal" onclick="identificarExamen('.$examen['examen_id'].')" data-toggle="modal" 
                            data-target="#introducirVehiculo">Introducir</a>';
                        };
                    };

                    echo "<tr>";
                    echo "<td>".$numero."</td>";
                    echo "<td>".$DNI."</td>";
                    echo '<td><a href="alumno.php?alumno_id='.$alumno->getId().'">'.$nombre_alumno.'</a></td>';
                    echo "<td>".$permiso."</td>";
                    echo "<td>".$seccion."</td>";
                    echo "<td>".$DC."</td>";
                    if ( $relacion['tipo_prueba'] == 'Circulación') {
                    echo "<td>".$vehiculo."</td>";
                    };
                    echo '<td><a href="#" class="abrirModal" onclick="identificarExamen('.$examen['examen_id'].')" data-toggle="modal" 
                    data-target="#cambiarEstado">'.$examen['estado'].'</td>';
                    echo'<td><a href="#" class="abrirModal" onclick="identificarExamen('.$examen['examen_id'].')" data-toggle="modal" 
                    data-target="#eliminarExamen">Eliminar</a></td>';
                    echo "</tr>";

                    $numero += 1;
                  }
                  ?>
                </tbody>
            </table>
        </div>
        <!-- botón para nuevo inscrito -->
        <a href="#" class="btn btn-primary btn-icon-split"  data-toggle="modal" data-target="#nuevo-inscrito">
            <span class="icon text-white-50">
                <i class="fas fa-user-graduate"></i>
            </span>
            <span class="text">Nuevo inscrito</span>
        </a>
    </div>
  </div>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">
  <br>

</div>

<!-- modal cambiar Estado-->
<div class="modal fade" id="cambiarEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cambiar estado del examen</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idExamenEstado" value="">
                <input type="hidden" id="relacion_id_estado" value="<?= $relacion_id ?>">
                <div class="col-sm-11">
                    <input type="radio" name="estado" value="Pendiente" checked> Pendiente <br>
                    <input type="radio" name="estado" value="Apto"> Apto <br>
                    <input type="radio" name="estado" value="No apto"> No apto <br>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir sin modificar</button>
                <a class="btn btn-primary" href="#" onclick="llamarAPIestadoExamen()">Cambiar estado</a>
            </div>
        </div>
    </div>
</div>

<!-- modal introducir vehiculo-->
<div class="modal fade" id="introducirVehiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Seleccionar vehículo para examen</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idExamenVehiculo" id="idExamenVehiculo" value="">
                <input type="hidden" id="relacion_id_vehiculo" value="<?= $relacion_id ?>">
                <div class="col-sm-11">
                    <?php
                    foreach ( $vehiculos as $vehiculo ) {
                        echo ('<input type="radio" name="vehiculo" value="'.$vehiculo['vehiculo_id'].'"
                                > '.$vehiculo['matricula'].' - '.$vehiculo['marca'].'<br>');
                    }
                  ?>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir sin modificar</button>
                <a class="btn btn-primary" href="#" onclick="llamarAPIintroducirVehiculo()">Introducir</a>
            </div>
        </div>
    </div>
</div>

<!-- modal eliminar Presentacion examen-->
<div class="modal fade" id="eliminarExamen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Seguro que quieres eliminar este alumno de la presentación?</h5>
            </div>
            <div class="modal-body">Esta acción no se puede deshacer. Selecciona "Eliminar" si deseas hacerlo.</div>
                <input type="hidden" name="idExamen" id="idExamen" value="">
                <input type="hidden" id="relacion_id-borrar" value="<?= $relacion_id ?>">
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir sin eliminar</button>
                <a class="btn btn-primary" href="#" onclick="eliminarExamen()">Eliminar</a>
            </div>
        </div>
    </div>
</div>


<!-- Modal nuevo inscrito -->
<div class="modal fade" id="nuevo-inscrito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inscribir alumno</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="user" method="POST">Nombre del alumno:
                        <div class="input-group">
                            <input type="text" class="form-control bg-gradient-light border-1 small" placeholder="Buscar..."
                                    aria-label="Search" aria-describedby="basic-addon2" id="nombre-alumno-inscribir">
                            <div class="input-group-append">
                              <button class="btn btn-primary" type="button" onclick="llamarAPIbuscarAlumnoInscribir()">
                                      <i class="fas fa-search fa-sm"></i>
                              </button>
                            </div>
                        </div>                        
                    </form>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabla-alumno-inscribir" width="80%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Alumno</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-tabla-inscribir">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="examen.php?relacion_id=<?= $relacion_id ?>" >Cancelar</a>
                </div>
            </div>
        </div>
</div>


<?php require_once 'footer.html'; ?>

<script>

  //funciones de autocompletado
  $('#nombre-alumno-inscribir').autocomplete({
      source: "../scripts/autocompletarAlumnos.php",
      minLength: 2,
      appendTo: "#nombre-alumno-inscribir"
      });

  //función para pasar el id del examen a eliminar, al modal.
  function identificarExamen($id) {
    document.getElementById('idExamen').value = $id;
    document.getElementById('idExamenVehiculo').value = $id;
    document.getElementById('idExamenEstado').value = $id;
    }

</script>