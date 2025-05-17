<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once '../scripts/claseCurso.php';
require_once '../scripts/claseAlumno.php';
require_once '../scripts/claseProfesor.php';
require_once '../scripts/claseClase.php';
require_once '../scripts/claseCargo.php';
require_once '../scripts/clasePago.php';
require_once '../scripts/funciones.php';

//cargamos instancias
$curso_id = $_GET['curso_id'];
$curso = new Curso($curso_id);
$curso->loadFromDB($pdo);

//filtramos para que si está finalizado no se abra aquí, si no en la página de histórico
if ($curso->getFinalizado() == "1") {
    header("Location: ../paginas/curso-historico.php?curso_id=".$curso_id);
};
$alumno = new Alumno($curso->getAlumnoId());
$alumno->loadFromDB($pdo);
$permiso = loadPermisoById($pdo, $curso->getPermisoId());

//adjudicamos nombres a variables
$nombre_alumno = $alumno->getNombre()." ".$alumno->getApellido1()." ".$alumno->getapellido2();

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

//CARGAMOS INFORMACIÓN PARA LAS LISTAS

//listas de profesores
$todos_profes = loadProfeCurso($pdo, $curso_id);
$lista_profes = [];
if ( $todos_profes !== null ) {
    foreach ($todos_profes as $profe) {
        $item = [];
        $item['nombre'] = $profe['nombre']." ".$profe['apellido1']." ".$profe['apellido2'];
        $item["profesor_id"] = $profe['profesor_id'];
        $lista_profes[] = $item;
    }
} else { $lista_profes = [];}

//clases, cargos y pagos
$clases_ids = loadClasesByCursoId( $pdo, $curso_id );
$cargos_ids = loadCargosByCursoId( $pdo, $curso_id );
$pagos_ids = loadPagoIdByCursoId( $pdo, $curso_id );

//Tasas
$tasas = loadAllTasas($pdo);

//Enseñanzas
$enseñanzas = loadAllEnseñanzas($pdo);

//Examenes
$examenes = loadExamenByCursoId($pdo, $curso_id);

//Fecha
$hoy = date("d/m/Y");

require_once 'header.html';
require_once 'head_side.html';
?>

<!-- Begin Page Content -->
<div class="container-fluid" >

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><span style="color:blue">Sección: <?= $curso->getSeccion()?></span></h1>
    <h1 class="h3 mb-4 text-gray-800">
        <span style="color:brown">Nº <?= $curso->getNumeroCurso(); ?> </span>- <?= $nombre_alumno ?>
        <span> - Curso/Permiso <?= $permiso['tipo'] ?></span>
    </h1>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <br>

    <div class="container-fluid" id="datos-curso">

        <h2 class="h4 mb-4 text-gray-600">Profesores adjudicados</h2>

        <div class="form-group row">
            <div class="col-sm-5 mb-3 mb-sm-0">
                <p><?php
                    foreach ($lista_profes as $prof) {
                        echo('<p>'.$prof['nombre'].'</p>');
                    }
                    ?>
            </div>
            <div class="col-sm-3 mb-2 mb-sm-0">
                <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" 
                    data-target="#adjudicarProfesor" onclick="cambiarProfesor()" >
                        <span class="icon text-white-50">
                        <i class="fas fa-user-alt"></i>
                    </span>
                    <span class="text">Cambiar Profesores</span>
                </a>
            </div>
        </div>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <br>

        <!-- Formulario -->
        <h2 class="h4 mb-4 text-gray-600">Datos del curso</h2>

        <form class="user" method="POST">
            <div id="finalizar">
                <div class="form-group row">
                    <div class="col-sm-2">Fecha  de inicio:
                        <input type="date" class="form-control form-control-user" id="inicio" value="<?= $curso->getFechaInicio() ?>"
                                    placeholder="Inicio">
                    </div>
                    <div class="col-sm-2">Fecha  de finalización:
                        <input type="date" class="form-control form-control-user" id="fecha_finalizacion" value="<?= $curso->getFechaFinalizacion() ?>"
                            placeholder="Finalización">
                    </div>
                    <div class= "col-sm-2">
                            <input type="checkbox" id="finalizado" name="dato_curso[]" value="finalizado" <?= $finalizado ?> style="margin-top: 40px;">
                            <label for="finalizado">Finalizado</label>
                    </div>
                    <div class= "col-sm-2">
                            <input type="checkbox" id="pagado" name="dato_curso[]" value="pagado" <?= $pagado ?> style="margin-top: 40px;">
                            <label for="pagado">Pagado</label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <!-- Boton para guardar -->
                        <button type="button" class="btn btn-primary btn-icon-split" id="guardar-button" onclick="llamarAPIguardarCurso()">
                            <span class="icon text-white-50">
                                <i class="fa fa-save"></i>
                            </span>
                            <span class="text">Guardar</span>
                        </button>
                        <!-- Boton de cancelar -->
                        <a href="curso.php?curso_id=<?=$_GET['curso_id']?>" class="btn btn-secondary btn-icon-split" id="cancelar-button">
                            <span class="text">Cancelar</span>
                        </a>
                    </div> 
                </div>
            </div>
            <input type="hidden" id="alumno_id" value=<?= $alumno->getID() ?>>
            <input type="hidden" id="curso_id" value=<?= $curso->getId() ?>>
            <input type="hidden" id="permiso_id" value=<?= $curso->getPermisoId() ?>>
        </form>
    </div>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <br>

    <div class="container-fluid">
        <!-- Contenido en columnas -->
        <div class="row">

            <!-- tabla de clases -->
            <div class="col-lg-12 mb-1">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Clases</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th><th>Hora</th><th>Tipo</th><th>Profesor</th><th>Importe</th><th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-tabla-clases">
                                <?php
                                    $montante_clases = 0;
                                    $total_pagado = 0;
                                    $deuda = 0;
                                    if ( ! empty($clases_ids) ) {

                                        foreach ($clases_ids as $clase) {
                                            //creamos instancias y cargamos los datos de la clase, profesor y enseñanza
                                            $clas = new Clase( $clase['clase_id'] );
                                            $clas->loadFromDB($pdo);

                                            $ense_id = $clas->getEnseñanzaId();
                                            $ense = loadEnseñanzaById($pdo, $ense_id );
                                            $fecha_clase = date("d/m/Y", strtotime( $clas->getFecha()) );

                                            $clas_id = $clas->getId();

                                            if ( $clas->getProfesorId() == 0 ) {
                                                $profe_clase = "";
                                            } else {
                                                $prof = new Profesor($clas->getProfesorId());
                                                $prof->loadFromDB($pdo);
                                                $profe_clase = $prof->getNombre()." ".$prof->getApellido1();
                                            }

                                            $hora = $clas->getHora();
                                            if ( $hora == 0 ) {
                                                $hora = "";
                                            }

                                            //sacamos precio y añadimo al total
                                            $precio = $ense['precio'];
                                            $montante_clases += $precio;

                                            //montamos la tabla
                                            echo('<tr><td>'.$fecha_clase.'</td><td>'.$hora.'</td><td>'.$ense['tipo'].'</td>
                                            <td>'.$profe_clase.'</td><td>'.$precio.'€</td><td><a href="pag-eliminar/pag-eliminar-clase.php?clase_id='.$clas_id.'">Eliminar</a></td></tr>');
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <!-- Boton de nuevo clase -->
                        <a href="#" class="btn btn-primary btn-icon-split" id="nueva-clase-button" 
                            data-toggle="modal" data-target="#introducirClase">
                            <span class="text">Nueva clase</span>
                        </a>
                    </div>
                </div>
            </div>
                <!-- tabla de examenes -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Examenes</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" style="margin-bottom: 30px;">
                            <thead>
                                <tr>
                                    <th>Tipo</th><th>Fecha</th><th>Profesor</th><th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo_tabla_examenes">
                                <?php
                                    if ( ! empty($examenes) ) {
                                        
                                        foreach ( $examenes as $examen ) {

                                            //cargamos los datos de la convocatoria
                                            $relacion = loadRelacionById($pdo, $examen['relacion_id']);
                                            $pro = loadProfeById($pdo, $relacion['profesor_id']);
                                            $pro_name = $pro['nombre'].' '.$pro['apellido1'];

                                            //estilos para el estado del examen
                                            $estilo = "";
                                            if ( $examen['estado'] == "Apto" ) {
                                                $estilo = 'style="color: green"';
                                            }
                                            if ( $examen['estado'] == "No apto" ) {
                                                $estilo = 'style="color: red"';
                                            }
                    
                                            echo('<tr><td>'.$relacion['tipo_prueba'].'</td>
                                            <td><a href="examen.php?relacion_id='.$relacion['relacion_id'].'">'.date("d/m/Y", strtotime($relacion['fecha_examen'])).'</a></td>
                                            <td>'.$pro_name.'</td>
                                            <td><span '. $estilo.'>'.$examen['estado'].'</span></td></tr>');
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- tabla de cargos -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Cargos</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" style="margin-bottom: 30px;">
                            <thead>
                                <tr>
                                    <th>Fecha</th><th>Tipo</th><th>Importe</th><th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-tabla-cargos">
                                <?php
                                    if ( ! empty($cargos_ids) ) {

                                        foreach ($cargos_ids as $cargo) {
                                            //creamos instancias y cargamos los datos de la clase, y enseñanza
                                            $carg = new Cargo( $cargo['cargo_id'] );
                                            $carg->loadFromDB($pdo);

                                            $tasa_id = $carg->getTasaId();
                                            $tasa = loadTasaById($pdo, $tasa_id );

                                            $fecha_cargo = date("d/m/Y", strtotime( $carg->getFecha()) );

                                            $carg_id = $carg->getId();

                                            //sacamos precio y añadimo al total
                                            $precio = $tasa['precio'];
                                            $montante_clases += $precio;

                                            //montamos la tabla
                                            echo('<tr><td>'.$fecha_cargo.'</td><td>'.$tasa['tipo'].'</td>
                                            <td>'.$precio.'€</td><td><a href="pag-eliminar/pag-eliminar-cargo.php?cargo_id='.$carg_id.'">Eliminar</a></td></tr>');
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <!-- Boton de nuevo cargo -->
                        <a href="#" class="btn btn-primary btn-icon-split" id="nuevo-cargo-button" 
                            data-toggle="modal" data-target="#introducirCargo">
                            <span class="text">Nuevo cargo</span>
                        </a>
                    </div>
                </div>
            </div>

            
                <!-- tabla de pagos -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pagos</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" style="margin-bottom: 30px;">
                            <thead>
                                <tr>
                                    <th>Número</th><th>Fecha</th><th>Concepto</th><th>Importe</th><th>Anular</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo_tabla_pagos">
                                <?php
                                    if ( ! empty($pagos_ids) ) {
                                        
                                        foreach ( $pagos_ids as $pago ) {
                                            
                                            //cargamos pagos 
                                            $pag = new Pago( $pago['pago_id'] );
                                            $pag->loadFromDB($pdo);

                                            //filtramos los pagos anulados
                                            if ( $pag->getAnulado() != "1" ) {

                                                //añadimos importe del pago al total pagado
                                                $total_pagado += $pag->getImporte();
                                            
                                                //montamos la tabla
                                                echo('<tr>
                                                    <td>'.$pag->getNumeroRecibo().'</td>
                                                    <td>'.$pag->getFecha().'</td><td>'.$pag->getConcepto().'</td>
                                                    <td>'.$pag->getImporte().' €</td>
                                                    <td><a href="pag-eliminar/pag-eliminar-pago.php?pago_id='.$pag->getId().'">Anular</a></td>
                                                </tr>');
                                            }
                                        }
                                    };
                                ?>
                            </tbody>
                        </table>
                        <!-- Boton de introducir pago -->
                        <a href="pago.php?curso_id=<?= $_GET['curso_id'] ?>" class="btn btn-primary btn-icon-split" id="nuevo-pago-button">
                            <span class="text">Introducir pago</span>
                        </a>
                    </div>
                </div>

                <!-- tabla de totales -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h4 class="m-0 font-weight-bold text-primary">Total</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Total cargos</th><th>Total pagado</th><th>Debe</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo_tabla_total">
                                <?php
                                    $deuda = $montante_clases - $total_pagado;
                                    echo ('<tbody><tr><td>'.$montante_clases.' €</td><td>'.$total_pagado.' €</td><td>'.$deuda.' €</td></tr></tbody></table>');
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <div class="col-sm-8">
                    <form class="user" method="POST" id="lista-profes">                 
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="#" onclick="adjudicarProfesor()">Adjudicar</a>
                </div>
            </div>
        </div>
</div>

<!-- Introducir clase -->
<div class="modal fade" id="introducirClase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Introducir clase</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="user" method="POST" id="formulario-clase">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?= $nombre_alumno; ?></h5>
                        <h5 class="modal-title" id="exampleModalLabel"><?= $curso->getNumeroCurso()."-".$permiso['tipo']; ?></h5>
                    </div>
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Profesor/es:
                            <br>
                            <?php
                            $numero_profe = 0;
                            echo('<form>');
                                if ( !empty( $lista_profes ) ) {
                                    foreach ($lista_profes as $prof) {
                                        if ( $numero_profe == 0 ) { $checked = "checked"; } else { $checked = ""; };
                                        echo('<input type="radio" name="id-profe-clase" value="'.$prof['profesor_id'].'" '.$checked.'> - '.$prof['nombre'].'</input><br>');
                                        $numero_profe += 1;
                                    }
                                } else {
                                    echo('<input type="radio" name="id-profe-clase" value="1" checked> - Profesor no adjudicado</input><br>');
                                }
                            echo('</form>');
                            ?>
                        </h6>
                    </div>
                    <br>
                    <div class="col-sm-8">
                        
                    <?php
                        $count = 0;
                        foreach( $enseñanzas as $ense ) {
                            if ($ense['permiso_id'] == $permiso['permiso_id']) {
                                if ( $count == 0 ) { $checked = "checked"; } else { $checked = ""; };
                                echo ('<input type="radio" name="tipo-clase" value='.$ense['enseñanza_id'].' '.$checked.'> '.$ense['tipo'].'<br>');
                                $count += 1;
                            }
                        }
                        
                    ?>

                    </div>
                    <br>
                    <div class="col-sm-4">Fecha:
                        <input type="date" class="form-control form-control-user" id="fecha-clase" value="<?= $hoy ?>">
                    </div>
                    <br>
                    <div class="col-sm-11">Franja horaria:<br>
                        <input type="radio" name="franja-hora" value="0" checked> Sin definir <br>
                        <input type="radio" name="franja-hora" value="1"> 1
                        <input type="radio" name="franja-hora" value="2"> 2
                        <input type="radio" name="franja-hora" value="3"> 3
                        <input type="radio" name="franja-hora" value="4"> 4
                        <input type="radio" name="franja-hora" value="5"> 5
                        <input type="radio" name="franja-hora" value="6"> 6
                        <input type="radio" name="franja-hora" value="7"> 7
                        <input type="radio" name="franja-hora" value="8"> 8
                        <input type="radio" name="franja-hora" value="9"> 9
                        <input type="radio" name="franja-hora" value="10"> 10
                        <input type="radio" name="franja-hora" value="11"> 11
                        <input type="radio" name="franja-hora" value="12"> 12
                    </div>                   
                </form>
                <br>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="#" onclick="nuevaClase()">Introducir</a>
                </div>
            </div>
        </div>
</div>

<!-- Introducir cargo -->
<div class="modal fade" id="introducirCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Introducir cargo</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="user" method="POST" id="formulario-cargo">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?= $nombre_alumno; ?></h5>
                        <h5 class="modal-title" id="exampleModalLabel"><?= $curso->getNumeroCurso()."-".$permiso['tipo']; ?></h5>
                    </div>
                    <br>
                    <div class="col-sm-8">

                    <?php
                        foreach( $tasas as $tas ) {
                            echo ('<input type="checkbox" name="cargos[]" value='.$tas['tasa_id'].'> '.$tas['tipo'].'<br>');
                        }
                    ?>

                    </div>
                    <br>
                    <div class="col-sm-4">Fecha:
                        <input type="date" class="form-control form-control-user" id="fecha-cargo" value="<?= $hoy ?>">
                    </div>
                    <br>                
                </form>
                <br>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="#" onclick="nuevoCargo()">Introducir</a>
                </div>
            </div>
        </div>
</div>

<?php require_once 'footer.html'; ?>