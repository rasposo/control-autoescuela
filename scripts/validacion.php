<?php
require_once "funciones.php";
print_r($_POST);

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to login
    header("Location: ../paginas/login.html");
    return;
}


$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it

session_start();

if ( isset($_POST['email']) && isset($_POST['password']) ) {
    unset($_SESSION["name"]);  // Logout current user
    if ( strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 ) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: ../paginas/login.html");
        return;
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: ../paginas/login.html");
        return;
    } else {
        
        $user = loadUser($pdo, $_POST['email']);
        if ( $user['contraseña'] == $_POST['password'] ) {
            if ( $user['perfil'] == "profesor" ) {
                error_log("Login success ".$_POST['email']); //registro de error sucess
                // Redirect the browser to indice.php
                $_SESSION['name'] = $_POST['email'];
                $_SESSION["success"] = "Logged in.";
                $_SESSION['perfil'] = 'profesor';
                $_SESSION['name'] = $user['nombre']. " ".$user['apellido1'];
                header("Location: ../paginas/indice.php");
                return;
            } else {
                error_log("Login success ".$_POST['email']); //registro de error sucess
                // Redirect the browser to usuario.php
                $_SESSION['name'] = $_POST['email'];
                $_SESSION["success"] = "Logged in.";
                $_SESSION['perfil'] = $user;
                $_SESSION['name'] = $user['nombre']." ".$user['apellido1'];
                header("Location: ../paginas/usuario.php");
                return;
            } 
        } else {
            error_log("Login fail ".$_POST['email'].$user); //registro de error de pass
            $_SESSION['error'] = "Incorrect password ".$user;
            header("Location: ../paginas/login.html");
        return;
        }
    }
}
?>