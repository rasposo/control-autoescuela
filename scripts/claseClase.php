<?php
require_once 'funciones.php';

class Clase{

	private $clase_id, $enseñanza_id, $fecha, $hora, $curso_id, 
            $profesor_id;

	// getters
	public function __construct($value = 0) { $this->clase_id = $value; }
	public function getId()          { return $this->clase_id; }
	public function getEnseñanzaId() { return $this->enseñanza_id; 	}
    public function getFecha()       { return $this->fecha; }
    public function getHora()        { return $this->hora; }
    public function getCursoId()     { return $this->curso_id; }
    public function getProfesorId()  { return $this->profesor_id; }
	
	// setters
	public function setEnseñanzaId($value) { $this->enseñanza_id = $value; }
    public function setFecha($value)       { $this->fecha = $value; }
    public function setHora($value)        { $this->hora = $value; }
    public function setCursoId($value)     { $this->curso_id = $value; }
    public function setProfesorId($value)  { $this->profesor_id = $value; }
	
	// Cargar desde la base de datos
    public function loadFromDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Clase WHERE clase_id = :id');
        $stmt ->execute(array( ':id' => $this->clase_id));
        $this->loadFromDBRecord($stmt->fetch(PDO::FETCH_ASSOC));
      }
	
	public function loadFromDBRecord ($registro) {
		$this->enseñanza_id = $registro["enseñanza_id"];
        $this->fecha        = $registro["fecha"];
        $this->hora         = $registro["hora"];
        $this->curso_id     = $registro["curso_id"];
        $this->profesor_id  = $registro['profesor_id'];
    }

    //Comprobar si la clase existe
	public function existsInDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Clase WHERE clase_id = :id');
        $stmt ->execute(array( ':id' => $this->clase_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result != null);        		
	}
	
	//Guardar en la base de datos
    public function saveIntoDB($pdo) {
		// Compruebo si el caso ya existe
		if ($this->existsInDB($pdo)) {
		    // Si existe, actualizo
            $stmt = $pdo->prepare('UPDATE Clase SET enseñanza_id = :aid,
                                                    fecha = :pid,
                                                    hora = :rid,
                                                    curso_id = :fic,
                                                    profesor_id = :fet
                                    WHERE clase_id = :cid');
            $stmt->execute(array(
                ':cid' => $this->clase_id,
                ':aid' => $this->enseñanza_id,
                ':pid' => $this->fecha,
                ':rid' => $this->hora,
                ':fic' => $this->curso_id,
                ':fet' => $this->profesor_id
                )
            );
		} else {
			// Si no existe, inserto
            $stmt = $pdo->prepare('INSERT INTO Clase ( enseñanza_id,
                                                        fecha,
                                                        hora,
                                                        curso_id,
                                                        profesor_id
                                                        )
                                    VALUES (:aid, :pid, :rid, :fic, :fet)'
                                    );
            $stmt->execute(array(
                ':aid' => $this->enseñanza_id,
                ':pid' => $this->fecha,
                ':rid' => $this->hora,
                ':fic' => $this->curso_id,
                ':fet' => $this->profesor_id
                )
            );

            //Actualizo el id del curso nuevo
            $this->clase_id = $pdo->lastInsertId();
		}	
	}
	
    //Borrar de la base de datos
	public function deleteFromDB($pdo) {
		if ($this->existsInDB($pdo)) {
			// Borrar
            $stmt = $pdo->prepare('DELETE FROM Clase WHERE clase_id = :id' );
            $stmt->execute(array(':id' => $this->clase_id));
		}
	}
} 

// fin de la clase 
?>