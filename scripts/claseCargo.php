<?php
require_once 'funciones.php';

class Cargo{

	private $cargo_id, $tasa_id, $fecha, $curso_id;

	// getters
	public function __construct($value = 0) { $this->cargo_id = $value; }
	public function getId()      { return $this->cargo_id; }
	public function getTasaId()  { return $this->tasa_id; }
    public function getFecha()   { return $this->fecha; }
    public function getCursoId() { return $this->curso_id; }
	
	// setters
	public function setTasaId($value)  { $this->tasa_id = $value; }
    public function setFecha($value)   { $this->fecha = $value; }
    public function setCursoId($value) { $this->curso_id = $value; }
	
	// Cargar desde la base de datos
    public function loadFromDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Cargo WHERE cargo_id = :id');
        $stmt ->execute(array( ':id' => $this->cargo_id));
        $this->loadFromDBRecord($stmt->fetch(PDO::FETCH_ASSOC));
      }
	
	public function loadFromDBRecord ($registro) {
		$this->tasa_id   = $registro["tasa_id"];
        $this->fecha     = $registro["fecha"];
        $this->curso_id  = $registro["curso_id"];
    }

    //Comprobar si el Cargo existe
	public function existsInDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Cargo WHERE cargo_id = :id');
        $stmt ->execute(array( ':id' => $this->cargo_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result != null);        		
	}
	
	//Guardar en la base de datos
    public function saveIntoDB($pdo) {
		// Compruebo si el caso ya existe
		if ($this->existsInDB($pdo)) {
		    // Si existe, actualizo
            $stmt = $pdo->prepare('UPDATE Cargo SET tasa_id = :tid,
                                                    fecha = :fec,
                                                    curso_id = :cid
                                    WHERE cargo_id = :cid');
            $stmt->execute(array(
                ':tid' => $this->tasa_id,
                ':fec' => $this->fecha,
                ':cid' => $this->curso_id
                )
            );
		} else {
			// Si no existe, inserto
            $stmt = $pdo->prepare('INSERT INTO Cargo ( tasa_id,
                                                        fecha,
                                                        curso_id
                                                        )
                                    VALUES (:tid, :fec, :cid)'
                                    );
            $stmt->execute(array(
                ':tid' => $this->tasa_id,
                ':fec' => $this->fecha,
                ':cid' => $this->curso_id
                )
            );

            //Actualizo el id del curso nuevo
            $this->cargo_id = $pdo->lastInsertId();
		}	
	}
	
    //Borrar de la base de datos
	public function deleteFromDB($pdo) {
		if ($this->existsInDB($pdo)) {
			// Borrar
            $stmt = $pdo->prepare('DELETE FROM Cargo WHERE cargo_id = :id' );
            $stmt->execute(array(':id' => $this->cargo_id));
		}
	}
} 

// fin de la Clase
?>