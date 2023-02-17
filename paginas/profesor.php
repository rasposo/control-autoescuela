<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/claseProfesor.php';
require_once '../scripts/funciones.php';

require_once 'header.html';
require_once 'head_side.html';

$profesor = new Profesor($_GET['profesor_id']);
$profesor->loadFromDB($pdo);

//comprobamos perfil administrador y director
$admin = "";
$direct = "";
if ( $profesor->getPerfilAdmin() == 1 )    { $admin = 'checked'; };
if ( $profesor->getPerfilDirector() == 1 ) { $direct = 'checked'; };

//obtengo los cursos donde imparte clase
$cursos = searchCursoByProfeId($pdo, $profesor->getId());

//obtengo los permisos
$permisos_profesor = $profesor->getPermisos();

$A2 = ""; $A = ""; $B = ""; $C = ""; $C_E = ""; $D = "";
$fechaA2 = ""; $fechaA = ""; $fechaB = ""; $fechaC = ""; $fechaC_E = ""; $fechaD = "";

foreach ($permisos_profesor as $permiso) {
    if ( $permiso['permiso'] == 'A2' ) { $A2 = "checked"; $fechaA2 = $permiso['fecha']; };
    if ( $permiso['permiso'] == 'A' )  { $A =  "checked"; $fechaA  = $permiso['fecha']; };
    if ( $permiso['permiso'] == 'B' )  { $B =  "checked"; $fechaB  = $permiso['fecha']; };
    if ( $permiso['permiso'] == 'C' )  { $C =  "checked"; $fechaC  = $permiso['fecha']; };
    if ( $permiso['permiso'] == 'C+E') { $C_E = "checked"; $fechaC_E = $permiso['fecha']; };
    if ( $permiso['permiso'] == 'D' )  { $D = "checked"; $fechaD = $permiso['fecha']; };
};

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $profesor->getNombre()." ".$profesor->getApellido1() ?></h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <!-- botones de selección -->
                    <div class="container-fluid">
                        <a href="#" class="btn btn-primary btn-icon-split" id="alumnos_adjudicados_button">
                            <span class="text">Alumnos adjudicados</span>
                        </a>
                        <a href="#" class="btn btn-secondary btn-icon-split" id="datos_profesor_button">
                            <span class="text">Datos profesor</span>
                        </a>
                    </div>
                    <br>
                    
                    <div class="container-fluid" id="datos_profesor" style="display: none">

                        <div class="container-fluid">

                            <h2 class="h4 mb-4 text-gray-600">Datos del profesor</h2>

                            <!-- Formulario -->

                            <form class="user" method="POST">
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3 mb-sm-0"> Nombre:
                                        <input type="text" class="form-control form-control-user" id="nombre" value="<?php echo($profesor->getNombre()) ?>"
                                        placeholder="Nombre">
                                </div>
                                    <div class="col-sm-4">Primer apellido:
                                        <input type="text" class="form-control form-control-user" id="apellido1" value="<?php echo($profesor->getApellido1()) ?>" 
                                            placeholder="Apellido 1">
                                    </div>
                                    <div class="col-sm-4">Segundo apellido:
                                        <input type="text" class="form-control form-control-user" id="apellido2" value="<?php echo($profesor->getApellido2()) ?>"
                                            placeholder="Apellido 2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 mb-3 mb-sm-0">DNI:
                                        <input type="text" class="form-control form-control-user" id="DNI" value="<?php echo($profesor->getDNI()) ?>"
                                            placeholder="DNI">
                                    </div>
                                    <div class="col-sm-2">Fecha caducidad DNI:
                                        <input type="date" class="form-control form-control-user" id="caducidad_dni" value="<?php echo($profesor->getCaducidadDNI()) ?>"
                                            placeholder="Caducidad DNI">
                                    </div>
                                    <div class="col-sm-2">Teléfono:
                                        <input type="text" class="form-control form-control-user" id="telefono" value="<?php echo($profesor->getTelefono()) ?>"
                                            placeholder="Teléfono">
                                    </div>
                                    <div class="col-sm-6">Correo electrónico:
                                        <input type="email" class="form-control form-control-user" id="email" value="<?php echo($profesor->getEmail()) ?>"
                                            placeholder="Correo electrónico">
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

                        <!-- Permisos -->
                        <form class="user" id="permisos_form" method="POST">
                            <legend>Permisos:</legend>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoA2" name="permisos[]" value="A2" <?= $A2 ?>>
                                    <label for="permisoA">A2</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaA2" value=<?= $fechaA2 ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoA" name="permisos[]" value="A" <?= $A ?>>
                                    <label for="permisoA">A</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaA" value=<?= $fechaA ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoB" name="permisos[]" value="B" <?= $B ?>>
                                    <label for="permisoA">B</ACr></label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaB" value=<?= $fechaB ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoC" name="permisos[]" value="C" <?= $C ?>>
                                    <label for="permisoA">C</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaC" value=<?= $fechaC ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoC+E" name="permisos[]" value="C+E" <?= $C_E ?>>
                                    <label for="permisoA">C+E</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaC+E" value=<?= $fechaC_E ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoD" name="permisos[]" value="D" <?= $D ?>>
                                    <label for="permisoA">D</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaD" value=<?= $fechaD ?>>
                                </div>
                            </div>
                        </form>
                        <hr class="sidebar-divider my-0">

                        <!-- Selección de perfiles -->
                        <form class="user" id="perfiles_form" method="POST">
                            <fieldset>
                                <legend>Perfiles:</legend>
                                <div>
                                    <input type="checkbox" name="perfiles[]" value="administrador" <?php echo $admin; ?>>
                                    <label for="perfil_administrador">Administrador</label>
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
                    </div>

                    <!-- Alumnos adjudicados -->
                    <div class="card-body" id="alumnos_adjudicados">
                        <h2 class="h4 mb-4 text-gray-600">Alumnos adjudicados</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="80%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nombre del alumno</th>
                                        <th>Permiso</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo_tabla">
                                    <?php
                                    foreach ($cursos as $curso) { 
                                        echo "<tr><td><a href=\"alumno.php?alumno_id=".$curso['alumno_id']."\">".$curso['nombre']." ".$curso['apellido1']." ".$curso['apellido2']."</a></td>";
                                        echo "<td><a href=\"curso.php?curso_id=".$curso['curso_id']."\">".$curso['tipo']."</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Botones para guardar, cancelar -->
                    <div class="container-fluid" id="botones_profesor" style="display: none">
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

<script>
    $(document).ready( function() {
        //Función para ocultar y mostrar los elementos
        $('#datos_profesor_button').on("click", function() {
            $('#alumnos_adjudicados').hide();
            $('#datos_profesor').show();
            $('#botones_profesor').show();
            $('#datos_profesor_button').attr("class", "btn btn-primary btn-icon-split");
            $('#alumnos_adjudicados_button').attr("class", "btn btn-secondary btn-icon-split")
        });

        $('#alumnos_adjudicados_button').on("click", function() {
            $('#datos_profesor').hide();
            $('#botones_profesor').hide();
            $('#alumnos_adjudicados').show();
            $('#alumnos_adjudicados_button').attr("class", "btn btn-primary btn-icon-split");
            $('#datos_profesor_button').attr("class", "btn btn-secondary btn-icon-split")
        });
    });

</script>