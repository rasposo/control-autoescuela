<?php
require_once 'funciones.php';

class Pago {

	private $pago_id, $curso_id, $fecha, $importe, $concepto, $numero_recibo, $anulado, $motivo_anulado;

	// getters
	public function __construct($value = 0) { $this->pago_id = $value; }
	public function getId()           { return $this->pago_id; }
	public function getCursoId()      { return $this->curso_id; }
    public function getFecha()        { return $this->fecha; }
    public function getImporte()      { return $this->importe; }
    public function getConcepto()     { return $this->concepto; }
    public function getNumeroRecibo() { return $this->numero_recibo; }
    public function getAnulado()      { return $this->anulado; }
    public function getMotivoAnulado() { return $this->motivo_anulado; }
	
	// setters
	public function setCursoId($value)      { $this->curso_id = $value; }
    public function setFecha($value)        { $this->fecha = $value; }
    public function setImporte($value)      { $this->importe = $value; }
    public function setConcepto($value)     { $this->concepto = $value; }
    public function setNumeroRecibo($value) { $this->numero_recibo = $value; }
    public function setAnulado($value)      { $this->anulado = $value; }
    public function setMotivoAnulado($value) { $this->motivo_anulado = $value; }
	
	// Cargar desde la base de datos
    public function loadFromDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Pago WHERE pago_id = :id');
        $stmt ->execute(array( ':id' => $this->pago_id ));
        $this->loadFromDBRecord($stmt->fetch(PDO::FETCH_ASSOC));
      }
	
	public function loadFromDBRecord ($registro) {
		$this->curso_id      = $registro["curso_id"];
        $this->fecha         = $registro["fecha"];
        $this->importe       = $registro["importe"];
        $this->concepto      = $registro["concepto"];
        $this->numero_recibo = $registro['numero_recibo'];
        $this->anulado       = $registro["anulado"];
        $this->motivo_anulado = $registro["motivo_anulado"];
    }

    //Comprobar si la clase existe
	public function existsInDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Pago WHERE pago_id = :id');
        $stmt ->execute(array( ':id' => $this->pago_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result != null);      		
	}
	
	//Guardar en la base de datos
    public function saveIntoDB($pdo) {
		// Compruebo si el caso ya existe
		if ($this->existsInDB($pdo)) {
		    // Si existe, actualizo
            $stmt = $pdo->prepare('UPDATE Pago SET  curso_id = :cid,
                                                    fecha = :fec,
                                                    importe = :imp,
                                                    concepto = :con,
                                                    anulado = :anu,
                                                    motivo_anulado = :man
                                    WHERE pago_id = :pid');
            $stmt->execute(array(
                ':pid' => $this->pago_id,
                ':cid' => $this->curso_id,
                ':fec' => $this->fecha,
                ':imp' => $this->importe,
                ':con' => $this->concepto,
                ':anu' => $this->anulado,
                ':man' => $this->motivo_anulado
                )
            );
		} else {
			// Si no existe, inserto

            //preparo el número de recibo
            $año = date('Y')."/";
            //obtenemos el número de pagos en el año
            $stmt = $pdo->prepare('SELECT COUNT(*) AS numero_recibos FROM Pago WHERE numero_recibo LIKE :id');
            $stmt ->execute(array( ':id' => $año."%" ));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            //componemos el número de curso y le damos el valor a la variable
            $numero_actual = sprintf("%05d", $result['numero_recibos'] + 1); 
            $this->numero_recibo = $año.$numero_actual;

            $stmt = $pdo->prepare('INSERT INTO Pago ( curso_id,
                                                        fecha,
                                                        importe,
                                                        concepto,
                                                        numero_recibo,
                                                        anulado,
                                                        motivo_anulado
                                                        )
                                    VALUES ( :cid, :fec, :imp, :con, :num, :anu, :mot )');
            $stmt->execute(array(
                ':cid' => $this->curso_id,
                ':fec' => $this->fecha,
                ':imp' => $this->importe,
                ':con' => $this->concepto,
                ':num' => $this->numero_recibo,
                ':anu' => 0,
                ':mot' => null
                )
            );

            //Actualizo el id del pago nuevo
            $this->pago_id = $pdo->lastInsertId();
		}	
	}
	
    //Borrar de la base de datos REFORMAR
	public function deleteFromDB($pdo, $motivo) {
		if ($this->existsInDB($pdo)) {
			// Anulamos el pago, pero no lo borramos
            $this->anulado = 1;
            $this->motivo_anulado = $motivo;
            $this->saveIntoDB($pdo);
		}
	}
} 

// fin de la clase