<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/claseProfesor.php';
require_once '../scripts/claseCurso.php';
require_once '../scripts/claseAlumno.php';
require_once '../scripts/funciones.php';

require_once 'header.html';
require_once 'head_side.html';

$profesor = new Profesor($_GET['profesor_id']);
$profesor->loadFromDB($pdo);
$nombre_profesor = $profesor->getNombre()." ".$profesor->getApellido1()." ".$profesor->getapellido2();

//comprobamos perfil administrador y director
$admin = "";
$direct = "";
if ( $profesor->getPerfilAdmin() == 1 )    { $admin = 'checked'; };
if ( $profesor->getPerfilDirector() == 1 ) { $direct = 'checked'; };

//obtengo los cursos donde imparte clase
$total_cursos = searchCursoByProfeId($pdo, $profesor->getId());

//obtengo los permisos del profesor
$per_profe = $profesor->getPermisos();
//añado el nombre del permiso
$permisos_profesor = [];
foreach ($per_profe as $p ){
    $nombre = loadPermisoById($pdo, $p['permiso_id']);
    $nombre = $nombre['tipo'];
    $p['permiso'] = $nombre;
    $permisos_profesor[] = $p;
}

//Todos los permisos
$permisos = loadAllPermisos($pdo);

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $nombre_profesor ?></h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>
                    
                    <div class="container-fluid" id="datos_profesor">

                        <div class="container-fluid">

                            <h2 class="h4 mb-4 text-gray-600">Datos del profesor</h2>

                            <!-- Formulario -->

                            <form class="user" method="POST">
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3 mb-sm-0"> Nombre:
                                        <input type="text" class="form-control form-control-user" id="nombre" value="<?php echo($profesor->getNombre()) ?>"
                                        placeholder="Nombre" required>
                                </div>
                                    <div class="col-sm-4">Primer apellido:
                                        <input type="text" class="form-control form-control-user" id="apellido1" value="<?php echo($profesor->getApellido1()) ?>" 
                                            placeholder="Apellido 1" required>
                                    </div>
                                    <div class="col-sm-4">Segundo apellido:
                                        <input type="text" class="form-control form-control-user" id="apellido2" value="<?php echo($profesor->getApellido2()) ?>"
                                            placeholder="Apellido 2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 mb-3 mb-sm-0">DNI:
                                        <input type="text" class="form-control form-control-user" id="DNI" value="<?php echo($profesor->getDNI()) ?>"
                                            placeholder="DNI" required>
                                    </div>
                                    <div class="col-sm-2">Fecha caducidad DNI:
                                        <input type="date" class="form-control form-control-user" id="caducidad_dni" value="<?php echo($profesor->getCaducidadDNI()) ?>"
                                            placeholder="Caducidad DNI">
                                    </div>
                                    <div class="col-sm-2">Teléfono:
                                        <input type="text" class="form-control form-control-user" id="telefono" value="<?php echo($profesor->getTelefono()) ?>"
                                            placeholder="Teléfono" required>
                                    </div>
                                    <div class="col-sm-6">Correo electrónico:
                                        <input type="email" class="form-control form-control-user" id="email" value="<?php echo($profesor->getEmail()) ?>"
                                            placeholder="Correo electrónico" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">Dirección:
                                        <input type="text" class="form-control form-control-user" id="direccion" value="<?php echo($profesor->getDireccion()) ?>"
                                            placeholder="Dirección">
                                </div>
                                    <div class="col-sm-2">Codigo Postal:
                                        <input type="text" class="form-control form-control-user" id="codigo_postal" value="<?php echo($profesor->getCodigoPostal()) ?>"
                                            placeholder="Código Postal">
                                    </div>
                                    <div class="col-sm-4">Localidad:
                                        <input type="text" class="form-control form-control-user" id="localidad" value="<?php echo($profesor->getLocalidad()) ?>"
                                            placeholder="Localidad">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">Provincia:
                                        <input type="text" class="form-control form-control-user" id="provincia" value="<?php echo($profesor->getProvincia()) ?>"
                                            placeholder="Provincia">
                                    </div>
                                    <div class="col-sm-2">Fecha nacimiento:
                                        <input type="date" class="form-control form-control-user" id="fecha_nacimiento" value="<?php echo($profesor->getFechaNacimiento()) ?>"
                                            placeholder="Fecha Nacimiento">
                                    </div>
                                    <div class="col-sm-2">Fecha de ingreso:
                                        <input type="date" class="form-control form-control-user" id="fecha_ingreso" value="<?php echo($profesor->getFechaIngreso()) ?>"
                                            placeholder="Antigüedad">
                                    </div>
                                    <div class="col-sm-3">Número de la SS:
                                        <input type="text" class="form-control form-control-user" id="numero_ss" value="<?php echo($profesor->getNumeroSS()) ?>"
                                            placeholder="Nº Seguridad Social">
                                    </div>
                                    <div class="col-sm-2">Contraseña
                                        <input type="password" class="form-control form-control-user" id="contraseña" value="<?php echo($profesor->getContraseña()) ?>"
                                            placeholder="Contraseña">
                                    </div>
                                    <input type='hidden' id="profesor_id" value="<?php echo($_GET['profesor_id']) ?>">
                            </form>
                        </div>
                            
                        <hr class="sidebar-divider my-0">
                        <br>

                        <div class="row">

                            <!-- Permisos -->
                            <div class="col-lg-5 mb-3">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Permisos/cursos que puede impartir</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Permiso/Curso</th>
                                                    <th>Fecha</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach( $permisos_profesor as $permiso ) {
                                                        echo('<tr>');
                                                        echo('<td>'.$permiso['permiso'].'</td><td>'.$permiso['fecha'].'</td>');
                                                        echo('</tr>');
                                                    };
                                                ?>
                                            </tbody>
                                        </table>
                                        <!-- Boton para cambiar permisos -->
                                        <a href="#" class="btn btn-primary btn-icon-split" id="modificar-permisos-button" 
                                            data-toggle="modal" data-target="#modificarPermisos">
                                            <span class="text">Modificar permisos</span>
                                        </a>
                                    </div>
                                </div>
                            </div>


                            <!-- Alumnos adjudicados -->
                            <div class="col-lg-5 mb-3">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Alumnos adjudicados</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" width="80%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Alumno</th>
                                                    <th>Curso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($total_cursos as $curso_id) {

                                                        $curso = new Curso($curso_id);
                                                        $curso->loadFromDB($pdo);

                                                        //filtro los que están finalizados
                                                        if ( $curso->getFinalizado() !== "1" ) {

                                                            $permiso = loadPermisoById($pdo, $curso->getPermisoId());

                                                            $alumno_id = $curso->getAlumnoId();
                                                            $alumno = new Alumno($alumno_id);
                                                            $alumno->loadFromDB($pdo);


                                                            echo "<tr><td><a href=\"alumno.php?alumno_id=".$alumno_id."\">".$alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2()."</a></td>";
                                                            echo "<td><a href=\"curso.php?curso_id=".$curso_id."\">".$curso->getNumeroCurso()." - ".$permiso['tipo']."</td></tr>";
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="sidebar-divider my-0">

                        <!-- Selección de perfiles -->
                        <form class="user" id="perfiles_form" method="POST">
                            <fieldset>
                                <legend>Perfiles:</legend>
                                <div>
                                    <input type="checkbox" name="perfiles[]" value="administrador" <?php echo $admin; ?>>
                                    <label for="perfil_administrador">Profesor</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="perfiles[]" value="director" <?php echo $direct; ?>>
                                    <label for="perfil_director">Director</label>
                                </div>
                            </fieldset>
                            <hr class="sidebar-divider my-0">
                            <br>
                        </form>
                    </div>

                    <!-- Botones para guardar, cancelar -->
                    <div class="container-fluid" id="botones_profesor">
                        <!-- Boton para guardar -->
                        <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIguardarProfesor()">
                            <span class="icon text-white-50">
                                <i class="fa fa-save"></i>
                            </span>
                            <span class="text">Guardar</span>
                            </a>
                        <!-- Cancelar -->
                        <a href="profesores.php" class="btn btn-primary btn-icon-split">
                            <span class="text">Cancelar</span>
                        </a>

                        <!-- Borrar profesor -->
                        <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal" data-target="#borrarUsuario" style="margin-left: 40px">
                            <span class="icon text-white-50">
                                <i class="fa fa-save"></i>
                            </span>
                            <span class="text">Eliminar</span>
                        </a>
                    </div>
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
                    <a class="btn btn-primary" href="../scripts/eliminarUsuario.php?profesor_id=<?php echo($_GET['profesor_id']) ?>">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

<!-- Modificar permisos -->
<div class="modal fade" id="modificarPermisos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Modificar permisos</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="user" method="POST" id="permisos_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?= $nombre_profesor ?></h5>
                    </div>
                    <br>
                    <div class="col-sm-10" >
                        <table class="table table-bordered" width="80%">
                            <thead>
                                <tr><th>Permisos</th><th>Fecha</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach( $permisos as $per ) {
                                        $checked = "";
                                        $date = "";
                                        //comprobamos si el profesor tiene el permiso
                                        foreach ( $permisos_profesor as $permiso )
                                            if ( $per['permiso_id'] == $permiso['permiso_id'] ) {
                                                $checked = "checked";
                                                $date = $permiso['fecha'];
                                            };
                                        echo ('<tr><td><input type="checkbox" name="permisos[]" value='.$per['permiso_id'].' '.$checked.'> '.$per['tipo'].'</td>');
                                        echo ('<td><input type="date" id="fecha'.$per['permiso_id'].'" value="'.$date.'"></td></tr>');
                                    } 
                                ?>
                            </tbody>
                        </table>
                    </div>                  
                </form>
                <br>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="#" onclick="llamarAPIguardarPermisosProfesor()">Guardar</a>
                </div>
            </div>
        </div>
</div>