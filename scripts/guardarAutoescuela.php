<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';

try {
    // PARTE 1. RECUPERAR PARÁMETROS
    $autoescuela_id =$_POST['autoescuela_id'];
    $nombre =        $_POST['nombre'];
    $razon_social =  $_POST['razon-social'];
    $numero_centro = $_POST['numero-centro'];
    $seccion =       $_POST['seccion'];
    $dc =            $_POST['DC'];
    $telefono =      $_POST['telefono'];
    $email =         $_POST['email'];
    $direccion =     $_POST['direccion'];
    $codigo_postal = $_POST['codigo-postal'];
    $localidad =     $_POST['localidad'];
    $provincia =     $_POST['provincia'];
    $CIF =           $_POST['CIF'];
    $IVA =           $_POST['IVA'];


    // PARTE 2. REALIZAR ACCIÓN

    //Si no existe, $id es 1. Entonces, introducimos nueva autoescuela
    if ($autoescuela_id == "1" ) {
        $stmt = $pdo->prepare('INSERT INTO Autoescuela (nombre,
                                                        razon_social,
                                                        n_centro,
                                                        seccion,
                                                        DC,
                                                        telefono,
                                                        email,
                                                        direccion,
                                                        codigo_postal,
                                                        localidad,
                                                        provincia,
                                                        CIF,
                                                        IVA
                                                    )
                                VALUES (:nom, :raz, :nce, :sec, :dc, :tel, :ema, :dir, :cod, :loc, :pro, :cif, :iva)'
                                );
        $stmt->execute(array(   ':nom' => $nombre,
                                ':raz' => $razon_social,
                                ':nce' => $numero_centro,
                                ':sec' => $seccion,
                                ':dc'  => $dc,
                                ':tel' => $telefono,
                                ':ema' => $email,
                                ':dir' => $direccion,
                                ':cod' => $codigo_postal,
                                ':loc' => $localidad,
                                ':pro' => $provincia,
                                ':cif' => $CIF,
                                ':iva' => $IVA                        
                                ));
    } else {
        //si existe, actualizo
        $stmt = $pdo->prepare('UPDATE Autoescuela SET  nombre =     :nom,
                                                    razon_social =  :raz,
                                                    n_centro =      :nce,
                                                    seccion =       :sec,
                                                    DC =            :dc,
                                                    telefono =      :tel,
                                                    email =         :ema,
                                                    direccion =     :dir,
                                                    codigo_postal = :cod,
                                                    localidad =     :loc,
                                                    provincia =     :pro,
                                                    CIF =           :cif,
                                                    IVA =           :iva
                            WHERE autoescuela_id = :aid' );
    $stmt->execute(array(   ':aid' => $autoescuela_id,
                            ':nom' => $nombre,
                            ':raz' => $razon_social,
                            ':nce' => $numero_centro,
                            ':sec' => $seccion,
                            ':dc'  => $dc,
                            ':tel' => $telefono,
                            ':ema' => $email,
                            ':dir' => $direccion,
                            ':cod' => $codigo_postal,
                            ':loc' => $localidad,
                            ':pro' => $provincia,
                            ':cif' => $CIF,
                            ':iva' => $IVA                        
                        ));
    }
    

    // PARTE 3. DEVOLVER RESULTADO
	// a) Correcto
    $respuesta = new stdClass();
    $respuesta->id    = 0;
    $respuesta->texto = "Autoescuela guardada";
    $json_respuesta   = json_encode($respuesta);
    echo ($json_respuesta);
	  
    // b) Incorrecto
} catch (Exception $e) {
    $error = new stdClass();
    $error->id    = -1;
    $error->texto = $e->getMessage();
    $json_error   = json_encode($error);
    echo ($json_error);
}   
?>