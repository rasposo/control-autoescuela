<?php
require_once 'funciones.php';

class Curso{

	private $curso_id, $seccion, $numero_curso, $alumno_id, $permiso_id, $fecha_inicio, 
            $finalizado, $fecha_finalizacion, $pagado;

	// getters
	public function __construct($value = 0)     {  $this->curso_id = $value;   }
	public function getId()                     { return $this->curso_id; }
    public function getSeccion()                { return $this->seccion; }
    public function getNumeroCurso()            { return $this->numero_curso; }
	public function getAlumnoId()               { return $this->alumno_id; 	}
    public function getPermisoId()              { return $this->permiso_id; }
    public function getFechaInicio()            { return $this->fecha_inicio; }
    public function getFinalizado()             { return $this->finalizado; }
    public function getFechaFinalizacion()      { return $this->fecha_finalizacion; }
	public function getPagado()                 { return $this->pagado; }
	
	// setters
    public function setSeccion($value)                { $this->seccion = $value; }
    public function setNumeroCurso($value)            { $this->numero_curso = $value; }
	public function setAlumnoId($value)               { $this->alumno_id = $value; }
    public function setPermisoId($value)              { $this->permiso_id = $value; }
    public function setFechaInicio($value)            { $this->fecha_inicio = $value; }
	public function setFinalizado($value)             { $this->finalizado = $value; }
    public function setFechaFinalizacion($value)      { $this->fecha_finalizacion = $value; }
    public function setPagado($value)                 { $this->pagado = $value; }
	
	// Cargar desde la base de datos
    public function loadFromDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Curso WHERE curso_id = :id');
        $stmt ->execute(array( ':id' => $this->curso_id));
        $this->loadFromDBRecord($stmt->fetch(PDO::FETCH_ASSOC));
      }
	
	public function loadFromDBRecord ($registro) {
        $this->seccion                  = $registro["seccion"];
        $this->numero_curso             = $registro["numero_curso"];
		$this->alumno_id                = $registro["alumno_id"];
        $this->permiso_id               = $registro["permiso_id"];
        $this->fecha_inicio             = $registro["fecha_inicio"];
        $this->finalizado               = $registro["finalizado"];
		$this->fecha_finalizacion       = $registro["fecha_finalizacion"];
		$this->pagado                   = $registro["pagado"];
	}

    //Comprobar si el curso existe
	public function existsInDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Curso WHERE curso_id = :id');
        $stmt ->execute(array( ':id' => $this->curso_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result != null);        		
	}
	
	//Guardar en la base de datos
    public function saveIntoDB($pdo) {
		// Compruebo si el caso ya existe
		if ($this->existsInDB($pdo)) {
		    // Si existe, actualizo
            $stmt = $pdo->prepare('UPDATE curso SET seccion                  = :sec,
                                                    numero_curso             = :ncu,
                                                    alumno_id                = :aid,
                                                    permiso_id               = :rid,
                                                    fecha_inicio             = :fic,
                                                    finalizado               = :fin,
                                                    fecha_finalizacion       = :ffi,
                                                    pagado                   = :pag
                                    WHERE curso_id = :cid');
            $stmt->execute(array(
                ':sec' => $this->seccion,
                ':ncu' => $this->numero_curso,
                ':cid' => $this->curso_id,
                ':aid' => $this->alumno_id,
                ':rid' => $this->permiso_id,
                ':fic' => $this->fecha_inicio,
                ':fin' => $this->finalizado,
                ':ffi' => $this->fecha_finalizacion,
                ':pag' => $this->pagado
                )
            );
		} else {
			// Si no existe, inserto

            //preparo el número de curso
            //filtramos para solo dar número a los permisos, no al resto de cursos
            if ( $this->permiso_id < 16 ) {
                $fecha = getDate();
                $año = $fecha['year']."/";
                //obtenemos el número de cursos en el año
                $stmt = $pdo->prepare('SELECT count(*) FROM Curso WHERE numero_curso LIKE :id AND seccion = :sec');
                $stmt ->execute(array( ':id' => $año."%",
                                    ':sec' => $this->seccion
                                    ));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                //componemos el número de curso y le damos el valor a la variable + 1
                $this->numero_curso = $año.$result['count(*)'];
            } else {
                $this->numero_curso = "";
            }

            $stmt = $pdo->prepare('INSERT INTO curso (  seccion,
                                                        numero_curso,
                                                        alumno_id,
                                                        permiso_id,
                                                        fecha_inicio,
                                                        finalizado,
                                                        fecha_finalizacion,
                                                        pagado)
                                    VALUES (:sec, :ncu, :aid, :rid, :fic, :fin, :ffi, :pag)'
                                    );
            $stmt->execute(array(
                ':sec' => $this->seccion,
                ':ncu' => $this->numero_curso,
                ':aid' => $this->alumno_id,
                ':rid' => $this->permiso_id,
                ':fic' => $this->fecha_inicio,
                ':fin' => $this->finalizado,
                ':ffi' => $this->fecha_finalizacion,
                ':pag' => $this->pagado
                )
            );

            //Actualizo el id del curso nuevo
            $this->curso_id = $pdo->lastInsertId();
		}	
	}
	
    //Borrar de la base de datos
	public function deleteFromDB($pdo) {
		if ($this->existsInDB($pdo)) {
			// Borrar
            $stmt = $pdo->prepare('DELETE FROM Curso WHERE curso_id = :id' );
            $stmt->execute(array(':id' => $this->curso_id));
		}
	}

   // Obtener todos los cursos de la base de datos
 	static public function getAll($pdo) {
		$lista = Array();
		$stmt = $pdo->query('SELECT * FROM Curso');
        while ($i = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$item = new curso();
			$item->loadFromDBRecord($i);
			$lista[] = $item;
		}
		return $lista;
    }
} // fin de la clase 
?>