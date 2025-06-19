<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/claseAlumno.php';
require_once '../scripts/funciones.php';

//Cargamos instacia
$alumno = new Alumno($_GET['alumno_id']);
$alumno->loadFromDB($pdo);

//obtengo el curso donde está
$cursos = searchCursoByAlumId($pdo, $alumno->getId());

//cargamos información para la lista de enseñanzas que se imparten
$permisos = loadAllPermiso($pdo);

//cargamos secciones de la autoescuela.
$secciones = loadSecciones($pdo);

require_once 'header.html';
require_once 'head_side.html';

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2() ?></h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <div class="container-fluid">

                    <h2 class="h4 mb-4 text-gray-600">Datos del alumno</h2>

                    <!-- Formulario -->

                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-4 mb-3 mb-sm-0">Nombre
                                <input type="text" class="form-control form-control-user" id="nombre" value="<?php echo($alumno->getNombre()) ?>"
                                    placeholder="Nombre">
                           </div>
                            <div class="col-sm-4">Primer apellido:
                                <input type="text" class="form-control form-control-user" id="apellido1" value="<?php echo($alumno->getApellido1()) ?>"
                                    placeholder="Apellido 1">
                            </div>
                            <div class="col-sm-4">Segundo apellido:
                                <input type="text" class="form-control form-control-user" id="apellido2" value="<?php echo($alumno->getApellido2()) ?>"
                                    placeholder="Apellido 2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 mb-3 mb-sm-0">DNI:
                                <input type="text" class="form-control form-control-user" id="dni" value="<?php echo($alumno->getDNI()) ?>"
                                    placeholder="DNI">
                           </div>
                            <div class="col-sm-2">Caducidad DNI:
                                <input type="date" class="form-control form-control-user" id="caducidad_dni" value="<?php echo($alumno->getCaducidad_DNI()) ?>"
                                    placeholder="Caducidad DNI">
                            </div>
                            <div class="col-sm-2">Teléfono:
                                <input type="text" class="form-control form-control-user" id="telefono" value="<?php echo($alumno->getTelefono()) ?>"
                                    placeholder="Teléfono">
                            </div>
                            <div class="col-sm-4">Correo electrónico:
                                <input type="email" class="form-control form-control-user" id="email" value="<?php echo($alumno->getEmail()) ?>"
                                    placeholder="Correo electrónico">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">Dirección:
                                <input type="text" class="form-control form-control-user" id="direccion" value="<?php echo($alumno->getDireccion()) ?>"
                                    placeholder="Dirección">
                           </div>
                            <div class="col-sm-2">Código postal:
                                <input type="text" class="form-control form-control-user" id="codigo_postal" value="<?php echo($alumno->getCodigoPostal()) ?>"
                                    placeholder="Código Postal">
                            </div>
                            <div class="col-sm-4">Localidad:
                                <input type="text" class="form-control form-control-user" id="localidad" value="<?php echo($alumno->getLocalidad()) ?>"
                                    placeholder="Localidad">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">Provincia:
                                <input type="text" class="form-control form-control-user" id="provincia" value="<?php echo($alumno->getProvincia()) ?>"
                                    placeholder="Provincia">
                            </div>
                            <div class="col-sm-2">Nacionalidad:
                                <input type="text" class="form-control form-control-user" id="nacionalidad" value="<?php echo($alumno->getNacionalidad()) ?>"
                                    placeholder="Nacionalidad">
                            </div>
                            <div class="col-sm-2">Fecha nacimiento:
                                <input type="date" class="form-control form-control-user" id="fecha_nacimiento" value="<?php echo($alumno->getFechaNacimiento()) ?>"
                                    placeholder="Fecha Nacimiento">
                            </div>
                            <div class="col-sm-2">Fecha ingreso:
                                <input type="date" class="form-control form-control-user" id="fecha_ingreso" value="<?php echo($alumno->getFechaIngreso()) ?>"
                                    placeholder="Fecha Ingreso">
                            </div>
                        </div>                    
                        <div class="form-group row">
                            <div class="col-sm-6">Estudios:
                                <input type="text" class="form-control form-control-user" id="estudios" value="<?php echo($alumno->getEstudios()) ?>"
                                    placeholder="Estudios">
                            </div> 
                            <div class="col-sm-2 mb-3 mb-sm-0">Contraseña:
                                <input type="password" class="form-control form-control-user" id="contraseña" value="<?php echo($alumno->getContraseña()) ?>"
                                    placeholder="Establecer contraseña">
                            </div>
                        </div>
                        <input type='hidden' id="alumno_id" value="<?= $alumno->getId() ?>">
                    </form>
                    <br>

                    <!-- Boton para guardar -->
                    <div class="container-fluid">
                        <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIguardarAlumno()">
                            <span class="icon text-white-50">
                                <i class="fa fa-save"></i>
                            </span>
                            <span class="text">Guardar</span>
                        </a>
                        <!-- cancelar -->
                        <a href="alumnos.php" class="btn btn-secondary btn-icon-split">
                            <span class="text">Cancelar</span>
                        </a>

                        <!-- Borrar alumno -->
                        <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal" data-target="#borrarUsuario" style="margin-left: 40px">
                            <span class="icon text-white-50">
                                <i class="fa fa-save"></i>
                            </span>
                            <span class="text">Eliminar</span>
                        </a>
                    </div>
                    <br>
                    <hr class="sidebar-divider my-0">
                    <br>

                    <!-- Curso matriculado -->
                        <div class="row">
                            <div class="col-lg-5 mb-3">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Cursos matriculado</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                <th>Seccion</th><th>Curso</th><th>Inicio</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpo_tabla">
                                                <?php
                                                foreach ($cursos as $curso) {
                                                    if ( $curso['finalizado'] == 0 ) {
                                                        echo "<tr>";
                                                        echo "<td>".$curso['seccion']."</td>"; 
                                                        echo "<td><a href=\"curso.php?curso_id=".$curso['curso_id']."\">Nº ".$curso['numero_curso']." - ".$curso['tipo']."</td>";
                                                        echo "<td>".$curso['fecha_inicio']."</td></tr>";
                                                    };
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#nuevoCurso">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-arrow-right"></i>
                                        </span>
                                        <span class="text">Matricular en un nuevo curso</span>
                                    </a>
                                    </div>
                                    <br>
                                </div>
                            </div>

                            <!-- Histórico cursos -->
                            <div class="col-lg-6 mb-3">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Histórico de cursos</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Seccion</th>
                                                    <th>Curso</th>
                                                    <th>Inicio</th>
                                                    <th>Finalización</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpo_tabla_finalizado">
                                            <?php
                                                foreach ($cursos as $curso) {
                                                    if ( $curso['finalizado'] == 1 ) {
                                                        echo "<tr>";
                                                        echo "<td>".$curso['seccion']."</td>"; 
                                                        echo "<td><a href=\"curso-historico.php?curso_id=".$curso['curso_id']."\">Nº ".$curso['numero_curso']." - ".$curso['tipo']."</td>";
                                                        echo "<td>".$curso['fecha_inicio']."</td><td>".$curso['fecha_finalizacion']."</td></tr>";
                                                    };
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                </div>


<?php require_once 'footer.html'; ?>

<!-- borrarUsuario-->
<div class="modal fade" id="borrarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Seguro que quieres eliminar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Esta acción no se puede deshacer. Selecciona "Eliminar" si deseas hacerlo.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir sin eliminar</button>
                <a class="btn btn-primary" href="../scripts/eliminarUsuario.php?alumno_id=<?php echo($_GET['alumno_id']) ?>">Eliminar</a>
            </div>
        </div>
    </div>
</div>


<!-- Nuevo curso -->
<div class="modal fade" id="nuevoCurso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo curso</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="user" method="POST">
                    <p style="padding-left: 10px;"><?= $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2()?></p>
                    <div class="col-sm-6">Sección autoescuela: <br>
                        <?php
                        $count = 0;
                        foreach ( $secciones as $seccion ) {
                            if ( $seccion !== "" ) {
                                if ( $count == 0 ) { $checked = "checked"; } else { $checked = ""; };
                                echo('<input type="radio" name="seccion" value="'.$seccion.'" '.$checked.'> - '.$seccion.'</input><br>');
                                $count += 1;
                            };
                        }
                        ?>
                    </div>
                    <div class="col-sm-4">Tipo de Curso: <br>
                        <?php
                        $count = 0;
                        echo('<form>');
                            foreach ( $permisos as $permiso ) {
                                if ( $count == 0 ) { $checked = "checked"; } else { $checked = ""; };
                                echo('<input type="radio" name="permiso-curso" value="'.$permiso['permiso_id'].'" '.$checked.'> - '.$permiso['tipo'].'</input><br>');
                                $count += 1;
                            }
                        echo('</form>');

                        ?>
                    </div>                   
                </form>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="button" onclick="nuevoCurso()">Guardar</button>
                </div>
            </div>
        </div>
</div>