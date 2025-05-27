<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=autoescuela', 'root', '');
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

  function loadProfeCurso($pdo, $curso_id) {
    $profes = [];
	  $stmt = $pdo->prepare('SELECT profesor_id FROM Curso_profesor WHERE curso_id = :cui');
    $stmt->execute(array(':cui' => $curso_id));
    while ($profe = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $profe_completo = loadProfeById($pdo, $profe['profesor_id']);
      $profes[] = $profe_completo;
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

function searchProfeByCompleteName($pdo, $name) {
  $profe = explode(" ", $name);
  $nombre = $profe[0];

  if ( isset($profe[1]) ) {
    $apellido1 = $profe[1];
  } else {
    $apellido1 = "";
  };
  if ( isset($profe[2]) ) {
    $apellido2 = $profe[2];
  } else {
    $apellido2 = "";
  };

  $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE nombre LIKE :nam AND apellido1 LIKE :ap1 AND apellido2 LIKE :ap2');
  $stmt ->execute(array( ':nam' => $nombre.'%',
                         ':ap1' => $apellido1.'%',
                         ':ap2' => $apellido2.'%'
                        ));
  $profesor = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $profesor;
}

//Buscamos en la base de datos, primero con nombre y después con apellido
function buscarProfe($pdo, $name) {
  $search = searchProfeByName($pdo, $name);
  if ( $search != null ) {
    return $search;
  };
  $search = searchProfeByApellido($pdo, $name);
  if ( $search != null ) {
    return $search;
  };
  $search = searchProfeByCompleteName($pdo, $name);
  if ( $search != null ) {
    return $search;
  };
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
  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE DNI LIKE :dni');
  $stmt ->execute(array( ':dni' => $name.'%'));
  $alum = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $alum;
}

function searchAluByCompleteName($pdo, $name) {
  $alum = explode(" ", $name);
  $nombre = $alum[0];

  if ( isset($alum[1]) ) {
    $apellido1 = $alum[1];
  } else {
    $apellido1 = "";
  };

  if ( isset($alum[2]) ) {
    $apellido2 = $alum[2];
  } else {
    $apellido2 = "";
  };

  $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE nombre = :nam AND apellido1 = :ap1 AND apellido2 = :ap2');
  $stmt ->execute(array( ':nam' => $nombre,
                         ':ap1' => $apellido1,
                         ':ap2' => $apellido2
                        ));
  $alumno = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $alumno;
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
  $search = searchAluByCompleteName($pdo, $name);
  if ( $search != null ) { 
    return $search;
  };
}

//funciones de búsqueda de Cursos
function searchCursoByAlumId($pdo, $alumno_id) {
  $stmt = $pdo->prepare('SELECT Alumno.alumno_id, Curso.curso_id, Curso.seccion, Curso.numero_curso, Curso.fecha_inicio, Curso.fecha_finalizacion, Curso.finalizado, 
                          Permiso.tipo
                          FROM Curso JOIN Alumno JOIN Permiso 
                          ON Curso.alumno_id = Alumno.alumno_id 
                          AND Curso.permiso_id = Permiso.permiso_id
                          WHERE Alumno.alumno_id = :aid');
  $stmt ->execute(array(':aid' => $alumno_id));
  $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $curso;
}

function searchCursoIdByAlumId($pdo, $alumno_id) {
  $stmt = $pdo->prepare('SELECT curso_id FROM Curso WHERE alumno_id = :aid');
  $stmt ->execute(array(':aid' => $alumno_id));
  $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $curso;
}

function searchCursoByProfeId($pdo, $profe_id) {
  $curso_id = [];
  $stmt = $pdo->prepare('SELECT curso_id  FROM Curso_profesor WHERE profesor_id = :poi' );
  $stmt ->execute(array(':poi' => $profe_id));
  while ( $curso = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $curso_id[] = $curso['curso_id'] ;
  }
  return $curso_id;
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
  $permiso = $stmt->fetch(PDO::FETCH_ASSOC);
  return $permiso;
}

function loadPermisoByTipo($pdo, $tipo) {  
  $stmt = $pdo->prepare('SELECT permiso_id FROM Permiso WHERE tipo = :ti');
  $stmt ->execute(array( ':ti' => $tipo ));
  $permiso_id = $stmt->fetch(PDO::FETCH_ASSOC);
  return $permiso_id;
}

function loadAllPermiso($pdo) {
  $stmt = $pdo->prepare('SELECT * FROM Permiso' );
  $stmt ->execute();
  $permiso = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $permiso;
}

//funciones de carga de clases y cargos y pagos por Id del curso

function loadClasesByCursoId($pdo, $curso_id) {
  $stmt = $pdo->prepare('SELECT clase_id FROM Clase WHERE curso_id = :id');
  $stmt ->execute(array( 'id' => $curso_id));
  $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $clases;
}

function loadPagoIdByCursoId($pdo, $curso_id) {
  $stmt = $pdo->prepare('SELECT pago_id FROM Pago WHERE curso_id = :id');
  $stmt ->execute(array( 'id' => $curso_id));
  $pago = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $pago;
}

function loadCargosByCursoId($pdo, $curso_id) {
  $stmt = $pdo->prepare('SELECT cargo_id FROM Cargo WHERE curso_id = :id');
  $stmt ->execute(array( 'id' => $curso_id));
  $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $cargos;
}


//funciones de tasas y enseñazas
function loadAllTasas($pdo) {
  $stmt = $pdo->prepare('SELECT * FROM Tasa' );
  $stmt ->execute();
  $tasa = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $tasa;
}

function loadTasaById($pdo, $tasa_id) {
  $stmt = $pdo->prepare('SELECT tipo, precio FROM Tasa WHERE tasa_id = :id');
  $stmt ->execute(array( 'id' => $tasa_id));
  $tasa = $stmt->fetch(PDO::FETCH_ASSOC);
  return $tasa;
}

function loadAllEnseñanzas($pdo) {
  $stmt = $pdo->prepare('SELECT * FROM Enseñanza' );
  $stmt ->execute();
  $enseñanza = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $enseñanza;
}

function loadEnseñanzaById($pdo, $enseñanza_id) {
  $stmt = $pdo->prepare('SELECT tipo, precio FROM Enseñanza WHERE enseñanza_id = :id');
  $stmt ->execute(array( 'id' => $enseñanza_id));
  $tipo = $stmt->fetch(PDO::FETCH_ASSOC);
  return $tipo;
}

function loadTipoPermisoById($pdo, $id) {
  $stmt = $pdo->prepare('SELECT tipo FROM Permiso WHERE permiso_id = :pid');
  $stmt ->execute(array( ':pid' => $id ));
  $tipo = $stmt->fetch(PDO::FETCH_ASSOC);
  $nombre = $tipo['tipo'];
  return $nombre;
}

function loadAllPermisos($pdo) {
  $permisos_completo = [];
  $stmt = $pdo->prepare('SELECT * FROM Permiso' );
  $stmt ->execute();
  while ( $permisos = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $permisos_completo[] = $permisos;
  }
  return $permisos_completo;
}

//funciones autoescuela
function loadAllDatosAutoescuela($pdo) {
  $autoescuelas = [];
  $stmt = $pdo->prepare('SELECT * FROM Autoescuela' );
  $stmt ->execute();
  while ( $datos = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $autoescuelas[] = $datos;
  };
  return $autoescuelas;
}

function loadAutoescuela( $pdo, $autoescuela_id ) {
  $stmt = $pdo->prepare('SELECT * FROM Autoescuela WHERE autoescuela_id = :id');
  $stmt->execute(array( ':id' => $autoescuela_id ));
  $datos = $stmt->fetch(PDO::FETCH_ASSOC);
  return $datos;
}

function loadAutoescuelaBySeccion( $pdo, $seccion ) {
  $stmt = $pdo->prepare('SELECT * FROM Autoescuela WHERE seccion = :sec');
  $stmt->execute(array( ':sec' => $seccion ));
  $datos = $stmt->fetch(PDO::FETCH_ASSOC);
  return $datos;
}

function loadSecciones($pdo) {
  $secciones = [];
  $stmt = $pdo->prepare('SELECT seccion FROM Autoescuela' );
  $stmt ->execute();
  while ( $datos = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $secciones[] = $datos['seccion'];
  };
  return $secciones;
}


//funciones vehículos
function loadAllVehiculos($pdo) {
  $vehiculos = [];
  $stmt = $pdo->prepare('SELECT * FROM Vehiculo' );
  $stmt ->execute();
  while ( $datos = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $vehiculos[] = $datos;
  };
  return $vehiculos;
}

function loadVehiculoById($pdo, $id) {
  $stmt = $pdo->prepare('SELECT * FROM Vehiculo WHERE vehiculo_id = :id');
  $stmt ->execute(array( ':id' => $id ));
  $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);
  return $vehiculo;
}




//funciones de examenes
function loadRelacionById($pdo, $relacion_id) {
  $stmt = $pdo->prepare('SELECT * FROM Relacion_examen WHERE relacion_id = :id');
  $stmt ->execute(array( 'id' => $relacion_id));
  $relacion = $stmt->fetch(PDO::FETCH_ASSOC);
  return $relacion;
};

function loadExamenByRelacionId($pdo, $relacion_id) {
  $examinados = [];
  $stmt = $pdo->prepare('SELECT * FROM Examen WHERE relacion_id = :id');
  $stmt ->execute(array( 'id' => $relacion_id));
  while ( $examen = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $examinados[] = $examen;
  }
  return $examinados;
};

function loadExamenByCursoId($pdo, $curso_id) {
  $examenes = [];
  $stmt = $pdo->prepare('SELECT * FROM Examen WHERE curso_id = :id');
  $stmt ->execute(array( 'id' => $curso_id));
  while ( $examen = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $examenes[] = $examen;
  }
  return $examenes;
};

?>