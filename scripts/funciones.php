<?php
$pdo = new PDO('mysql:host=localhost;port=8889;dbname=proyecto', 'josefo', 'rasposo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//seleccion del usuario en el login
function loadUser($pdo, $email) {
  $user = loadProfeByEmail($pdo, $email);
  if ($user != null) {
    $user['perfil'] = "profesor";
    return $user;
  } else {
    $user = loadAlumByEmail($pdo, $email);
    $user['perfil'] = "alumno";
    return $user;
  }
}

//funciones de carga de profesores
function loadProfeById($pdo, $id) {
  $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE profesor_id = :id');
  $stmt ->execute(array( ':id' => $id ));
  $profe = $stmt->fetch(PDO::FETCH_ASSOC);
  return $profe;
}

function loadProfeByName($pdo, $name) {
    $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE nombre = :na');
    $stmt ->execute(array( ':na' => $name));
    $profe = $stmt->fetch(PDO::FETCH_ASSOC);
    return $profe;
  }

function loadProfeByEmail($pdo, $email) {
    $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE email = :em');
    $stmt ->execute(array( ':em' => $email));
    $profe = $stmt->fetch(PDO::FETCH_ASSOC);
    return $profe;
  }

  function loadProfeByDNI($pdo, $dni) {
    $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE dni = :dni');
    $stmt ->execute(array( ':dni' => $dni));
    $profe = $stmt->fetch(PDO::FETCH_ASSOC);
    return $profe;
  }
  
  function loadAllProfe($pdo) {
	  $profes = [];
	  $stmt = $pdo->query('SELECT * FROM Profesor');
    while ($profe = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $profes[] = $profe;
	  };
    return $profes;
  }
  

//funciones de búsqueda de profesores
function searchProfeByName($pdo, $name) {
  $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE nombre LIKE :na');
  $stmt ->execute(array( ':na' => $name.'%'));
  $profe = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $profe;
}

function searchProfeByApellido($pdo, $name) {
  $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE apellido1 LIKE :ap');
  $stmt ->execute(array( ':ap' => $name.'%'));
  $profe = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $profe;
}

//Buscamos en la base de datos, primero con nombre y después con apellido
function buscarProfe($pdo, $name) {
  $search = searchProfeByName($pdo, $name);
  if ( $search == null ) {
    $search = searchProfeByApellido($pdo, $name);
  }
  return $search;
}

//funciones de carga de alumnos
function loadAlumById($pdo, $id) {
  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE alumno_id = :id');
  $stmt ->execute(array( ':id' => $id));
  $alum = $stmt->fetch(PDO::FETCH_ASSOC);
  return $alum;
}

function loadAlumByEmail($pdo, $email) {
    $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE email = :em');
    $stmt ->execute(array( ':em' => $email));
    $alum = $stmt->fetch(PDO::FETCH_ASSOC);
    return $alum;
  }

function loadAlumByDNI($pdo, $dni) {
    $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE DNI = :dni');
    $stmt ->execute(array( ':dni' => $dni));
    $alum = $stmt->fetch(PDO::FETCH_ASSOC);
    return $alum;
  }

//funciones de búsqueda de alumnos
function searchAluByName($pdo, $name) {
  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE nombre LIKE :na');
  $stmt ->execute(array( ':na' => $name.'%'));
  $alum = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $alum;
}

function searchAluByApellido($pdo, $name) {
  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE apellido1 LIKE :ap');
  $stmt ->execute(array( ':ap' => $name.'%'));
  $alum = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $alum;
}

function searchAluByTelefono($pdo, $name) {
  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE telefono LIKE :tlf');
  $stmt ->execute(array( ':tlf' => $name.'%'));
  $alum = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $alum;
}

function searchAluByEmail($pdo, $name) {
  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE email LIKE :em');
  $stmt ->execute(array( ':em' => $name.'%'));
  $alum = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $alum;
}

function searchAluByDNI($pdo, $name) {
  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE DNI LIKE = :dni');
  $stmt ->execute(array( ':dni' => $name.'%'));
  $alum = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $alum;
}

function buscarAlu($pdo, $name) {
  $search = searchAluByName($pdo, $name);
  if ( $search != null ) { 
    return $search; 
  };
  $search = searchAluByApellido($pdo, $name);
  if ( $search != null ) { 
    return $search; 
  };
  $search = searchAluByEmail($pdo, $name);
  if ( $search != null ) { 
    return $search; 
  };
  $search = searchAluByTelefono($pdo, $name);
  if ( $search != null ) { 
    return $search; 
  };
  $search = searchAluByDNI($pdo, $name);
  if ( $search != null ) { 
    return $search;
  };
}

//funciones de búsqueda de Cursos
function searchCursoByAlumId($pdo, $alumno_id) {
  $stmt = $pdo->prepare('SELECT Alumno.alumno_id, Profesor.profesor_id, Curso.curso_id, Profesor.nombre, Profesor.apellido1, 
                          Profesor.apellido2, Permiso.tipo  
                          FROM Curso JOIN Profesor JOIN Alumno JOIN Permiso 
                          ON Curso.profesor_id = Profesor.profesor_id AND Curso.alumno_id = Alumno.alumno_id 
                          AND Curso.permiso_id = Permiso.permiso_id
                          WHERE Alumno.alumno_id = :aid');
  $stmt ->execute(array(':aid' => $alumno_id));
  $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $curso;
}

function searchCursoByProfeId($pdo, $profe_id) {
  $stmt = $pdo->prepare('SELECT Alumno.alumno_id, Profesor.profesor_id, Curso.curso_id, Alumno.nombre, Alumno.apellido1, 
                          Alumno.apellido2, Permiso.tipo  
                          FROM Curso JOIN Profesor JOIN Alumno JOIN Permiso 
                          ON Curso.profesor_id = Profesor.profesor_id AND Curso.alumno_id = Alumno.alumno_id 
                          AND Curso.permiso_id = Permiso.permiso_id
                          WHERE Profesor.profesor_id = :pid');
  $stmt ->execute(array(':pid' => $profe_id));
  $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $curso;
}

function searchCursoInCurse($pdo) {
  $cursos = [];
  $stmt = $pdo->prepare('SELECT curso_id FROM Curso WHERE finalizado = 0');
  $stmt->execute();
  while ($i = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $item = new Curso($i['curso_id']);
    $item->loadFromDB($pdo);
    $cursos[] = $item;
  }
  return $cursos;
}

//funciones de carga de permisos
function loadPermisosByProfeId($pdo, $profe_id) {
  $stmt = $pdo->prepare('SELECT * FROM Prof_permiso WHERE profesor_id = :id');
  $stmt ->execute(array( ':id' => $profe_id ));
  $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $permisos;
}

function loadPermisoById($pdo, $permiso_id) {  
  $stmt = $pdo->prepare('SELECT * FROM Permiso WHERE permiso_id = :id');
  $stmt ->execute(array( ':id' => $permiso_id ));
  $permiso = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $permiso;
}

function loadPermisoByTipo($pdo, $tipo) {  
  $stmt = $pdo->prepare('SELECT permiso_id FROM Permiso WHERE tipo = :ti');
  $stmt ->execute(array( ':ti' => $tipo ));
  $permiso_id = $stmt->fetch(PDO::FETCH_ASSOC);
  return $permiso_id;
}