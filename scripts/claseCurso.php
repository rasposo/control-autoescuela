<?php
require_once 'funciones.php';

class Curso{

	private $curso_id, $alumno_id, $profesor_id, $permiso_id, $fecha_inicio, 
            $fecha_examen_teorico, $fecha_examen_destreza, $fecha_examen_circulacion, $finalizado,
            $fecha_finalizacion, $pagado;

	// getters
	public function __construct($value = 0)     {  $this->curso_id = $value;   }
	public function getId()                     { return $this->curso_id; }
	public function getAlumnoId()              { return $this->alumno_id; 	}
    public function getProfesorId()            { return $this->profesor_id; }
    public function getPermisoId()              { return $this->permiso_id; }
    public function getFechaInicio()            { return $this->fecha_inicio; }
    public function getFechaExamenTeorico()     { return $this->fecha_examen_teorico; }
    public function getFechaExamenDestreza()    { return $this->fecha_examen_destreza; }
	public function getFechaExamenCirculacion() { return $this->fecha_examen_circulacion; }
    public function getFinalizado()             { return $this->finalizado; }
    public function getFechaFinalizacion()      { return $this->fecha_finalizacion; }
	public function getPagado()                 { return $this->pagado; }
	
	// setters
	public function setAlumnoId($value)               { $this->alumno_id = $value; }
    public function setProfesorId($value)            { $this->profesor_id = $value; }
    public function setPermisoId($value)              { $this->permiso_id = $value; }
    public function setFechaInicio($value)            { $this->fecha_inicio = $value; }
    public function setFechaExamenTeorico($value)     { $this->fecha_examen_teorico = $value; }
    public function setFechaExamenDestreza($value)    { $this->fecha_examen_destreza = $value; }
	public function setFechaExamenCirculacion($value) { $this->fecha_examen_circulacion = $value; }
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
		$this->alumno_id                = $registro["alumno_id"];
        $this->profesor_id              = $registro["profesor_id"];
        $this->permiso_id               = $registro["permiso_id"];
        $this->fecha_inicio             = $registro["fecha_inicio"];
        $this->fecha_examen_teorico     = $registro['fecha_examen_teorico'];
        $this->fecha_examen_destreza    = $registro["fecha_examen_destreza"];
        $this->fecha_examen_circulacion = $registro["fecha_examen_circulacion"];
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
            $stmt = $pdo->prepare('UPDATE curso SET alumno_id                = :aid,
                                                    profesor_id              = :pid,
                                                    permiso_id               = :rid,
                                                    fecha_inicio             = :fic,
                                                    fecha_examen_teorico     = :fet,
                                                    fecha_examen_destreza    = :fed,
                                                    fecha_examen_circulacion = :fec,
                                                    finalizado               = :fin,
                                                    fecha_finalizacion       = :ffi,
                                                    pagado                   = :pag
                                    WHERE curso_id = :cid');
            $stmt->execute(array(
                ':cid' => $this->curso_id,
                ':aid' => $this->alumno_id,
                ':pid' => $this->profesor_id,
                ':rid' => $this->permiso_id,
                ':fic' => $this->fecha_inicio,
                ':fet' => $this->fecha_examen_teorico,
                ':fed' => $this->fecha_examen_destreza,
                ':fec' => $this->fecha_examen_circulacion,
                ':fin' => $this->finalizado,
                ':ffi' => $this->fecha_finalizacion,
                ':pag' => $this->pagado
                )
            );
		} else {
			// Si no existe, inserto
            $stmt = $pdo->prepare('INSERT INTO curso (alumno_id,
                                                        profesor_id,
                                                        permiso_id,
                                                        fecha_inicio,
                                                        fecha_examen_teorico,
                                                        fecha_examen_destreza,
                                                        fecha_examen_circulacion,
                                                        finalizado,
                                                        fecha_finalizacion,
                                                        pagado)
                                    VALUES (:aid, :pid, :rid, :fic, :fet, :fed, :fec, :fin, :ffi, :pag)'
                                    );
            $stmt->execute(array(
                ':aid' => $this->alumno_id,
                ':pid' => $this->profesor_id,
                ':rid' => $this->permiso_id,
                ':fic' => $this->fecha_inicio,
                ':fet' => $this->fecha_examen_teorico,
                ':fed' => $this->fecha_examen_destreza,
                ':fec' => $this->fecha_examen_circulacion,
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