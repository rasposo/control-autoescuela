<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/funciones.php';

//Cargamos datos
$datos_autoescuela = loadAutoescuela($pdo, $_GET['autoescuela_id']);

require_once 'header.html';
require_once 'head_side.html';
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Configuración</h1>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <br>

    <div class="container-fluid" id="datos_autoescuela">

        <h2 class="h4 mb-4 text-gray-600">Datos Autoescuela</h2>

        <!-- Formulario datos de la autoescuela-->

        <form class="user" method="POST">
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0"> Nombre:
                    <input type="text" class="form-control form-control-user" id="nombre" value="<?= $datos_autoescuela['nombre']; ?>"
                      placeholder="Nombre">
                </div>
                <div class="col-sm-6">Razón Social:
                    <input type="text" class="form-control form-control-user" id="razon-social" value="<?= $datos_autoescuela['razon_social']; ?>"
                        placeholder="Razón social">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 mb-3 mb-sm-0">Nº de Centro:
                    <input type="text" class="form-control form-control-user" id="numero-centro" value="<?= $datos_autoescuela['n_centro']; ?>"
                        placeholder="Nº de centro">
                </div>
                <div class="col-sm-1 mb-2 mb-sm-0">Sección:
                      <input type="text" class="form-control form-control-user" id="seccion" value="<?= $datos_autoescuela['seccion']; ?>"
                          placeholder="Sección">
                </div>
                <div class="col-sm-1 mb-2 mb-sm-0">D.C.:
                      <input type="text" class="form-control form-control-user" id="D.C." value="<?= $datos_autoescuela['DC']; ?>"
                          placeholder="Sección">
                </div>
                <div class="col-sm-2">Teléfono:
                    <input type="text" class="form-control form-control-user" id="telefono" value="<?= $datos_autoescuela['telefono']; ?>"
                        placeholder="Teléfono">
                </div>
                <div class="col-sm-6">Correo electrónico:
                    <input type="email" class="form-control form-control-user" id="email" value="<?= $datos_autoescuela['email']; ?>"
                        placeholder="Correo electrónico">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">Dirección:
                    <input type="text" class="form-control form-control-user" id="direccion" value="<?= $datos_autoescuela['direccion']; ?>"
                        placeholder="Dirección">
                </div>
                <div class="col-sm-2">Codigo Postal:
                    <input type="text" class="form-control form-control-user" id="codigo-postal" value="<?= $datos_autoescuela['codigo_postal']; ?>"
                        placeholder="Código Postal">
                </div>
                <div class="col-sm-4">Localidad:
                    <input type="text" class="form-control form-control-user" id="localidad" value="<?= $datos_autoescuela['localidad']; ?>"
                        placeholder="Localidad">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">Provincia:
                    <input type="text" class="form-control form-control-user" id="provincia" value="<?= $datos_autoescuela['provincia']; ?>"
                        placeholder="Provincia">
                </div>
                <div class="col-sm-3">CIF:
                    <input type="text" class="form-control form-control-user" id="CIF" value="<?= $datos_autoescuela['CIF']; ?>"
                        placeholder="CIF">
                </div>
                <div class="col-sm-1">IVA:
                    <input type="text" class="form-control form-control-user" id="IVA" value="<?= $datos_autoescuela['IVA']; ?>"
                        placeholder="IVA">
                </div>
            </div>
            <input type="hidden" id="autoescuela_id" value="<?= $datos_autoescuela['autoescuela_id']; ?>" >
        </form>

        <div class="container-fluid">
            <!-- botón para guardar -->
            <a href="#" class="btn btn-primary btn-icon-split" onclick="llamarAPIguardarAutoescuela()">
                <span class="icon text-white-50">
                    <i class="fa fa-save"></i>
                </span>
                <span class="text">Guardar</span>
            </a>
            <!-- botón para cancelar -->
            <a href="configuracion.php" class="btn btn-primary btn-icon-split">
                <span class="text">Cancelar</span>
            </a>
        </div>
        <br>
    </div>

    <hr class="sidebar-divider my-0">
    <br>
</div>

<?php require_once 'footer.html'; ?>