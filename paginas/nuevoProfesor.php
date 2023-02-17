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

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Nuevo profesor</h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <div class="container-fluid" id="datos_profesor">

                    <h2 class="h4 mb-4 text-gray-600">Introducir datos del profesor</h2>

                    <!-- Formulario -->

                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-4 mb-3 mb-sm-0"> Nombre:
                                <input type="text" class="form-control form-control-user" id="nombre"
                                   placeholder="Nombre">
                           </div>
                            <div class="col-sm-4">Primer apellido:
                                <input type="text" class="form-control form-control-user" id="apellido1"
                                    placeholder="Apellido 1">
                            </div>
                            <div class="col-sm-4">Segundo apellido:
                                <input type="text" class="form-control form-control-user" id="apellido2"
                                    placeholder="Apellido 2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 mb-3 mb-sm-0">DNI:
                                <input type="text" class="form-control form-control-user" id="DNI"
                                    placeholder="DNI">
                           </div>
                            <div class="col-sm-2">Fecha caducidad DNI:
                                <input type="date" class="form-control form-control-user" id="caducidad_dni"
                                    placeholder="Caducidad DNI">
                            </div>
                            <div class="col-sm-2">Teléfono:
                                <input type="text" class="form-control form-control-user" id="telefono"
                                    placeholder="Teléfono">
                            </div>
                            <div class="col-sm-6">Correo electrónico:
                                <input type="email" class="form-control form-control-user" id="email"
                                    placeholder="Correo electrónico">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">Dirección:
                                <input type="text" class="form-control form-control-user" id="direccion"
                                    placeholder="Dirección">
                           </div>
                            <div class="col-sm-2">Codigo Postal:
                                <input type="text" class="form-control form-control-user" id="codigo_postal"
                                    placeholder="Código Postal">
                            </div>
                            <div class="col-sm-4">Localidad:
                                <input type="text" class="form-control form-control-user" id="localidad"
                                    placeholder="Localidad">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">Provincia:
                                <input type="text" class="form-control form-control-user" id="provincia"
                                    placeholder="Provincia">
                        </div>
                            <div class="col-sm-2">Fecha de nacimiento:
                                <input type="date" class="form-control form-control-user" id="fecha_nacimiento"
                                    placeholder="Fecha Nacimiento">
                            </div>
                            <div class="col-sm-2">Fecha de ingreso:
                                <input type="date" class="form-control form-control-user" id="fecha_ingreso"
                                    placeholder="Antigüedad">
                            </div>
                            <div class="col-sm-3">Número de la SS:
                                <input type="text" class="form-control form-control-user" id="numero_ss"
                                    placeholder="Nº Seguridad Social">
                            </div>
                            <div class="col-sm-2">Contraseña
                                <input type="password" class="form-control form-control-user" id="contraseña"
                                    placeholder="Contraseña">
                            </div>
                            <input type='hidden' id="profesor_id" value="<?php echo($_GET['profesor_id']) ?>">
                        </form>
                        </div>
                        
                        <hr class="sidebar-divider my-0">
                        <br>

                        <!-- Permisos -->
                        <form class="user" id="permisos_form" method="POST">
                            <legend>Permisos:</legend>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoA2" name="permisos[]" value="A2">
                                    <label for="permisoA">A2</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaA2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoA" name="permisos[]" value="A">
                                    <label for="permisoA">A</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaA">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoB" name="permisos[]" value="B">
                                    <label for="permisoA">B</ACr></label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaB">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoC" name="permisos[]" value="C">
                                    <label for="permisoA">C</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaC">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoC+E" name="permisos[]" value="C+E">
                                    <label for="permisoA">C+E</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaC+E">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class= "col-sm-1">
                                    <input type="checkbox" id="permisoD" name="permisos[]" value="D">
                                    <label for="permisoA">D</label>
                                </div>
                                <div class="col-sm3">
                                    <input type="date" class="form-control form-control-user" id="fechaD">
                                </div>
                            </div>
                        </form>
                        <hr class="sidebar-divider my-0">

                    <!-- Selección de perfiles -->
                    <div class="container-fluid" id="perfiles_profesor">


                        <fieldset>
                            <legend>Perfiles:</legend>

                            <div>
                                <input type="checkbox" id="perfil_profesor" name="perfil_profesor" checked>
                                <label for="perfil_profesor">Profesor</label>
                            </div>
                            <div>
                                <input type="checkbox" id="perfil_administrador" name="perfil_administrador">
                                <label for="perfil_administrador">Administrador</label>
                            </div>
                            <div>
                                <input type="checkbox" id="perfil_director" name="perfil_director">
                                <label for="perfil_director">Director</label>
                            </div>
                        </fieldset>
                        <hr class="sidebar-divider my-0">
                        <br>
                    </div>

                    <!-- Boton para guardar -->
                    <div class="container-fluid">
                        <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIguardarProfesor()">
                            <span class="icon text-white-50">
                                <i class="fa fa-save"></i>
                            </span>
                            <span class="text">Guardar</span>
                        </a>
                    <!-- cancelar -->
                        <a href="profesores.php" class="btn btn-primary btn-icon-split">
                            <span class="text">Cancelar</span>
                        </a>
                    </div>
                </div>

<?php require_once 'footer.html'; ?>