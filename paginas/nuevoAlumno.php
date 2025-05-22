<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/claseAlumno.php';
require_once '../scripts/funciones.php';

require_once 'header.html';
require_once 'head_side.html';

$alumno = new Alumno($_GET['alumno_id']);

?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Nuevo alumno</h1>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <br>

                    <div class="container-fluid">

                    <h2 class="h4 mb-4 text-gray-600">Datos del alumno</h2>

                    <!-- Formulario -->

                    <form class="user" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-4 mb-3 mb-sm-0">Nombre
                                <input type="text" class="form-control form-control-user" id="nombre"
                                    placeholder="Nombre" required>
                           </div>
                            <div class="col-sm-4">Primer apellido:
                                <input type="text" class="form-control form-control-user" id="apellido1"
                                    placeholder="Apellido 1" required>
                            </div>
                            <div class="col-sm-4">Segundo apellido:
                                <input type="text" class="form-control form-control-user" id="apellido2"
                                    placeholder="Apellido 2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 mb-3 mb-sm-0" >DNI:
                                <input type="text" class="form-control form-control-user" id="dni"
                                    placeholder="DNI" required>
                           </div>
                            <div class="col-sm-2">Caducidad DNI:
                                <input type="date" class="form-control form-control-user" id="caducidad_dni"
                                    placeholder="Caducidad DNI">
                            </div>
                            <div class="col-sm-2">Teléfono:
                                <input type="text" class="form-control form-control-user" id="telefono"
                                    placeholder="Teléfono" required>
                            </div>
                            <div class="col-sm-4">Correo electrónico:
                                <input type="email" class="form-control form-control-user" id="email"
                                    placeholder="Correo electrónico">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">Dirección:
                                <input type="text" class="form-control form-control-user" id="direccion"
                                    placeholder="Dirección" required>
                           </div>
                            <div class="col-sm-2">Código postal:
                                <input type="text" class="form-control form-control-user" id="codigo_postal"
                                    placeholder="Código Postal" required>
                            </div>
                            <div class="col-sm-4">Localidad:
                                <input type="text" class="form-control form-control-user" id="localidad"
                                    placeholder="Localidad" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">Provincia:
                                <input type="text" class="form-control form-control-user" id="provincia"
                                    placeholder="Provincia" required>
                            </div>
                            <div class="col-sm-2">Nacionalidad:
                                <input type="text" class="form-control form-control-user" id="nacionalidad"
                                    placeholder="Nacionalidad" required>
                            </div>
                            <div class="col-sm-2">Fecha nacimiento:
                                <input type="date" class="form-control form-control-user" id="fecha_nacimiento"
                                    placeholder="Fecha Nacimiento" required>
                            </div>
                            <div class="col-sm-2">Fecha ingreso:
                                <input type="date" class="form-control form-control-user" id="fecha_ingreso"
                                    placeholder="Fecha Ingreso" required>
                            </div>
                        </div>                    
                        <div class="form-group row">
                            <div class="col-sm-6">Estudios:
                                <input type="text" class="form-control form-control-user" id="estudios"
                                    placeholder="Estudios">
                            </div> 
                            <div class="col-sm-2 mb-3 mb-sm-0">Contraseña:
                                <input type="password" class="form-control form-control-user" id="contraseña"
                                    placeholder="Establecer contraseña" required>
                            </div>
                        </div>
                        <input type='hidden' id="alumno_id" value="<?php echo($_GET['alumno_id']) ?>">
                     </form>
                </div>

                <hr class="sidebar-divider my-0">
                        <br>
            </div>

            <!-- Boton para guardar -->
             <div class="container-fluid">
                <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIguardarAlumno()">
                    <span class="icon text-white-50">
                        <i class="fa fa-save"></i>
                    </span>
                    <span class="text">Guardar</span>
                </a>
            <!-- cancelar -->
                <a href="alumnos.php" class="btn btn-primary btn-icon-split">
                    <span class="text">Cancelar</span>
                </a>

<?php require_once 'footer.html'; ?>