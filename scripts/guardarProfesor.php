<?php
session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'funciones.php';
require_once 'claseProfesor.php';

// PARTE 1. Comprobanos que no existe ya el DNI y el correo electrónico en la base de datos en caso de profesor nuevo.
if ( $_POST['profesor_id'] == 0 && loadProfeByDNI($pdo, $_POST['DNI'])) {
    $respuesta = new stdClass();
    $respuesta->id    = -1;
    $respuesta->texto = "El DNI ya existe";
    $json_respuesta   = json_encode($respuesta);
    echo ($json_respuesta);

} elseif ( $_POST['profesor_id'] == 0 && loadProfeByEmail($pdo, $_POST['email'])) {
    $respuesta = new stdClass();
    $respuesta->id    = -1;
    $respuesta->texto = "El email ya existe";
    $json_respuesta   = json_encode($respuesta);
    echo ($json_respuesta);
} else {
    try {
        // PARTE 2. RECUPERAR PARÁMETROS
        $profesor_id = $_POST['profesor_id'];

        
        // PARTE 3. Ponemos los datos en la instancia
        $profesor = new Profesor($profesor_id);

        $profesor->setNombre($_POST['nombre']);
        $profesor->setApellido1($_POST['apellido1']);
        $profesor->setApellido2($_POST['apellido2']);
        $profesor->setDNI($_POST['DNI']);
        $profesor->setCaducidadDNI($_POST['caducidad_dni']);
        $profesor->setDireccion($_POST['direccion']);
        $profesor->setLocalidad($_POST['localidad']);
        $profesor->setProvincia($_POST['provincia']);
        $profesor->setCodigoPostal($_POST['codigo_postal']);
        $profesor->setFechaNacimiento($_POST['fecha_nacimiento']);
        $profesor->setFechaIngreso($_POST['fecha_ingreso']);
        $profesor->setNumeroSS($_POST['numero_ss']);
        $profesor->setPerfilDirector($_POST['perfil_director']);
        $profesor->setPerfilAdmin($_POST['perfil_administrador']);
        $profesor->setTelefono($_POST['telefono']);
        $profesor->setEmail($_POST['email']);
        $profesor->setContraseña($_POST['contraseña']);

        // PARTE 4. REALIZAR ACCIÓN
        $profesor->saveIntoDB($pdo);

        $id = loadProfeByDNI($pdo, $_POST['DNI']);
        $id = $id['profesor_id'];
        
        // PARTE 5. DEVOLVER RESULTADO
        // a) Correcto
        $respuesta = new stdClass();
        $respuesta->id    = 0;
        $respuesta->profesor_id = $id;
        $respuesta->texto = "Profesor guardado";
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
}  

?>