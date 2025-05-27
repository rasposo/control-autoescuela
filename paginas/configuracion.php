<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/funciones.php';

//Cargamos datos
$autoescuelas = loadAllDatosAutoescuela($pdo);
$permisos = loadAllPermisos($pdo);
$enseñanzas = loadAllEnseñanzas($pdo);
$vehiculos = loadAllVehiculos($pdo);
$tasas = loadAllTasas($pdo);

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
        <div class="row">
            <?php
                foreach ( $autoescuelas as $autoescuela ) {
                    echo('<div class="col-lg-6 mb-4">');
                    echo('<div class="card shadow mb-6">');
                    //encabezado de la tarjeta
                    echo('<div class="card-header py-3">');
                    echo('<h4 class="m-1 font-weight-bold text-primary">'.$autoescuela['nombre'].' - Sección: '.$autoescuela['seccion'].'</h4>');
                    echo('</div>');

                    //cuerpo de la tarjeta
                    echo('<div class="card-body">');
                    echo('<p>Razón social: '.$autoescuela['razon_social'].'<br>');
                    echo('Número de centro: '.$autoescuela['n_centro'].'<br>');
                    echo('Seccion: '.$autoescuela['seccion'].'<br>');
                    echo('D.C.: '.$autoescuela['DC'].'<br>');
                    echo('Teléfono: '.$autoescuela['telefono'].'<br>');
                    echo('email: '.$autoescuela['email'].'<br>');
                    echo('Dirección: '.$autoescuela['direccion'].'<br>');
                    echo('Código postal: '.$autoescuela['codigo_postal'].'<br>');
                    echo('Localidad: '.$autoescuela['localidad'].'<br>');
                    echo('Provincia: '.$autoescuela['provincia'].'<br>');
                    echo('CIF: '.$autoescuela['CIF'].'<br>');
                    echo('IVA: '.$autoescuela['IVA'].'</p>');
                    echo('<br>');

                    echo('<a href="actualizarAutoescuela.php?autoescuela_id='.$autoescuela['autoescuela_id'].'" class="btn btn-primary btn-icon-split">');
                    echo('<span class="text">Modificar</span></a>');                  
                    echo('</div>');
                    echo('</div>');
                    echo('</div>');                        
                };
            ?>
        </div>
        <a href="actualizarAutoescuela.php?autoescuela_id=0" class="btn btn-primary btn-icon-split">
            <span class="text">Nueva sección</span>
        </a>
    </div>
    <br>

    <hr class="sidebar-divider my-0">
    <br>

    <!-- Vehículos -->
    <div class="container-fluid">
        <h2 class="h4 mb-4 text-gray-600">Vehículos</h2>
        <div class="row">    
            <div class="col-lg-8 mb-6">
                <div class="card shadow mb-8">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th>Tipo:</th><th>Marca</th><th>Matrícula</th><th></th></tr>
                            <?php
                                //recopilamos los vehículos
                                foreach ( $vehiculos as $vehiculo ) {
                                    echo('<tr><td>'.$vehiculo['tipo'].'</td>
                                    <td>'.$vehiculo['marca'].'</td>
                                    <td>'.$vehiculo['matricula'].'</td>
                                    <td><a href="#" class="abrirModal" onclick="identificarVehiculo('.$vehiculo['vehiculo_id'].')" data-toggle="modal" 
                                    data-target="#eliminarVehiculo">Eliminar</a></td></th>');
                                };
                                ?>
                        </table>

                        <input type="button" id="añadir-vehiculo" value="Añadir" 
                            class="btn btn-primary btn-icon-split" onclick="añadirVehiculo()">
                        <div  style="display: none" id="nuevo-vehiculo"><br>
                            Tipo <input type="text" id="tipo-vehiculo" class="form-control col-md-6" >
                            Marca <input type="text" id="marca" class="form-control col-md-6">
                            Matrícula <input type="text" id="matricula" class="form-control col-md-4">
                            <br>
                            <input type="button" id="nuevo-vehículo" value="añadir" 
                            class="btn btn-secondary btn-icon-split" onclick="nuevoVehiculo()">
                        </div>
                    </div>                     
                </div>
            </div>
        </div>                       
    </div>
    <br>

    <hr class="sidebar-divider my-0">
    <br>

    <!-- Tasas -->
    <div class="container-fluid">
        <h2 class="h4 mb-4 text-gray-600">Tasas</h2>
        <div class="row">    
            <div class="col-lg-8 mb-6">
                <div class="card shadow mb-8">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th>Tipo:</th><th>Precio</th></tr>
                            <?php
                                //recopilamos las tasas
                                foreach ( $tasas as $tasa ) {
                                    echo('<tr><td>'.$tasa['tipo'].'</td>
                                    <td>'.$tasa['precio'].'</td>
                                    <td><a href="#" class="abrirModal" onclick="identificarTasa('.$tasa['tasa_id'].')" data-toggle="modal" 
                                    data-target="#eliminarTasa">Eliminar</a></td></th>');
                                };
                                ?>
                        </table>

                        <input type="button" id="añadir_tasa" value="Añadir" 
                            class="btn btn-primary btn-icon-split" onclick="añadirTasa()">
                        <div  style="display: none" id="nueva_tasa"><br>
                            Tipo <input type="text" id="tipo_tasa" class="form-control col-md-6" >
                            Precio <input type="text" id="precio" class="form-control col-md-6">
                            <br>
                            <input type="button" id="nueva_tasa" value="añadir" 
                            class="btn btn-secondary btn-icon-split" onclick="nuevaTasa()">
                        </div>
                    </div>                     
                </div>
            </div>
        </div>                       
    </div>
    <br>

    <hr class="sidebar-divider my-0">
    <br>  

    <!-- Configuración de permisos -->
    <div class="container-fluid" id="enseñanzas">
        <h2 class="h4 mb-4 text-gray-600">Configuración de permisos</h2>
        <div class="row">    
                <?php
                    foreach ( $permisos as $permiso ) {

                        $permiso_id = $permiso['permiso_id'];
                        if ( $permiso_id < 16 ) {

                            echo('<div class="col-lg-6 mb-4">');
                            echo('<div class="card shadow mb-6">');
                            //encabezado de la tarjeta
                            echo('<div class="card-header py-3">');
                            echo('<h4 class="m-1 font-weight-bold text-primary">'.$permiso['tipo'].'</h4>');
                            echo('</div>');

                            //cuerpo de la tarjeta
                            echo('<div class="card-body">');
                                //tabla de enseñanzas
                                echo('<table class="table table-bordered">');
                                echo('<tr><th>Clase-enseñanza:</th><th>Precio</th><th>Modificar</th></tr>');
                                    //recopilamos las clases que se dan con el permiso
                                    foreach ( $enseñanzas as $enseñanza ) {
                                    if ( $enseñanza['permiso_id'] == $permiso_id ) {
                                        echo('<tr><td id="nombre-enseñanza-'.$enseñanza['enseñanza_id'].'">'.$enseñanza['tipo'].'</td>
                                        <td id="precio-enseñanza-'.$enseñanza['enseñanza_id'].'">'.$enseñanza['precio'].' €</td>
                                        <td id="modificar-enseñanza-'.$enseñanza['enseñanza_id'].'"><a href="#" onclick="modificarEnseñanza('.$enseñanza['enseñanza_id'].')">Modificar</a></td></th>');
                                        };
                                    };
                                echo('</table>');

                            echo('<input type="button" id="añadir'.$permiso_id.'" value="Añadir" 
                            class="btn btn-primary btn-icon-split" onclick="añadir('.$permiso_id.')">');
                            echo('<div  style="display: none" id="div-ense-'.$permiso_id.'"><br>
                            Nombre <input type="text" id="nueva-ense-tipo-'.$permiso_id.'" class="form-control" >
                            Precio   <input type="text" id="nueva-ense-precio-'.$permiso_id.'" class="form-control col-md-6">
                            <br>
                            <input type="button" id="nuevaEnse'.$permiso_id.'" value="Introducir" 
                            class="btn btn-secondary btn-icon-split" onclick="nuevaEnseñanza('.$permiso_id.')"></div>');                     
                            echo('</div>');
                            echo('</div>');
                            echo('</div>');
                        };                      
                    };
                ?>     
        </div>
    </div>

    <hr class="sidebar-divider my-0">
    <br>

    <!-- Otros cursos -->
    <div class="container-fluid" id="otras-enseñanzas">
        <h2 class="h4 mb-4 text-gray-600">Otros cursos</h2>
        <div class="row">    
                <?php
                    foreach ( $permisos as $permiso ) {

                        $permiso_id = $permiso['permiso_id'];
                        if ( $permiso_id >= 16 ) {

                            echo('<div class="col-lg-6 mb-4">');
                            echo('<div class="card shadow mb-6">');
                            //encabezado de la tarjeta
                            echo('<div class="card-header py-3">');
                            echo('<h4 class="m-1 font-weight-bold text-primary">'.$permiso['tipo'].'</h4>');
                            echo('</div>');

                            //cuerpo de la tarjeta
                            echo('<div class="card-body">');
                                //tabla de enseñanzas
                                echo('<table class="table table-bordered">');
                                echo('<tr><th>Clase-enseñanza:</th><th>Precio</th><th>Modificar</th></tr>');
                                    //recopilamos las clases que se dan con el permiso
                                    foreach ( $enseñanzas as $enseñanza ) {
                                    if ( $enseñanza['permiso_id'] == $permiso_id ) {
                                        echo('<tr><td id="nombre-enseñanza-'.$enseñanza['enseñanza_id'].'">'.$enseñanza['tipo'].'</td>
                                        <td id="precio-enseñanza-'.$enseñanza['enseñanza_id'].'">'.$enseñanza['precio'].' €</td>
                                        <td id="modificar-enseñanza-'.$enseñanza['enseñanza_id'].'"><a href="#" onclick="modificarEnseñanza('.$enseñanza['enseñanza_id'].')">Modificar</a></td></th>');
                                        };
                                    };
                                echo('</table>');

                            echo('<input type="button" id="añadir'.$permiso_id.'" value="Añadir" 
                            class="btn btn-primary btn-icon-split" onclick="añadir('.$permiso_id.')">');
                            echo('<div  style="display: none" id="div-ense-'.$permiso_id.'"><br>
                            Nombre <input type="text" id="nueva-ense-tipo-'.$permiso_id.'" class="form-control" >
                            Precio   <input type="text" id="nueva-ense-precio-'.$permiso_id.'" class="form-control col-md-6">
                            <br>
                            <input type="button" id="nuevaEnse'.$permiso_id.'" value="Introducir" 
                            class="btn btn-secondary btn-icon-split" onclick="nuevaEnseñanza('.$permiso_id.')"></div>');                     
                            echo('</div>');
                            echo('</div>');
                            echo('</div>');
                        };                      
                    };
                ?>     
        </div>
            <!-- botón introducir nuevo permiso/curso -->
            <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" 
                                    data-target="#nuevo-permiso">
                <span class="icon text-white-50">
                    <i class="fa fa-save"></i>
                </span>
                <span class="text">Nuevo Curso</span>
            </a>
    </div>
    <br>

    
<!-- Modal nuevo permiso-->
<div class="modal fade" id="nuevo-permiso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo permiso/curso</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="user" method="POST" id="formulario-nuevo-permiso">
                        <div class="form-group row">
                            <div class="col-sm-6">Nombre permiso/curso:
                                <input type="text" class="form-control form-control-user" id="tipo-nuevo-permiso">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="#" onclick="nuevoPermiso()">Introducir</a>
                </div>
            </div>
        </div>
</div>

<!-- modal eliminar Vehículo-->
<div class="modal fade" id="eliminarVehiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Seguro que quieres eliminar este vehículo?</h5>
            </div>
            <div class="modal-body">Esta acción no se puede deshacer. Selecciona "Eliminar" si deseas hacerlo.</div>
                <input type="hidden" name="idVehiculo" id="idVehiculo" value="">
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir sin eliminar</button>
                <a class="btn btn-primary" href="#" onclick="eliminarVehiculo()">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<!-- modal eliminar Tasa-->
<div class="modal fade" id="eliminarTasa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Seguro que quieres eliminar esta tasa?</h5>
            </div>
            <div class="modal-body">Esta acción no se puede deshacer. Selecciona "Eliminar" si deseas hacerlo.</div>
                <input type="hidden" name="idTasa" id="idTasa" value="">
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir sin eliminar</button>
                <a class="btn btn-primary" href="#" onclick="eliminarTasa()">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
    function añadir(permiso_id) {
        $('#div-ense-'+permiso_id).toggle();
        let boton = document.getElementById("añadir"+permiso_id).value;
        if ( boton == "Añadir" ) {
            document.getElementById("añadir"+permiso_id).value = "Cancelar";
        } else {
            document.getElementById("añadir"+permiso_id).value = "Añadir";
        };
    }

    function añadirVehiculo() {
        $('#nuevo-vehiculo').toggle();
        let boton = document.getElementById("añadir-vehiculo").value;
        if ( boton == "Añadir" ) {
            document.getElementById("añadir-vehiculo").value = "Cancelar";
        } else {
            document.getElementById("añadir-vehiculo").value = "Añadir";
        };
    }

    function añadirTasa() {
        $('#nueva_tasa').toggle();
        let boton = document.getElementById("añadir_tasa").value;
        if ( boton == "Añadir" ) {
            document.getElementById("añadir_tasa").value = "Cancelar";
        } else {
            document.getElementById("añadir_tasa").value = "Añadir";
        };
    }

    //función para pasar datos del vehículo a eliminar, al modal.
    function identificarVehiculo($vehiculo) {
        document.getElementById('idVehiculo').value = $vehiculo;
    }

    //función para pasar datos de la tasa  a eliminar, al modal.
    function identificarTasa($tasa) {
        document.getElementById('idTasa').value = $tasa;
    }
</script>

<?php require_once 'footer.html'; ?>