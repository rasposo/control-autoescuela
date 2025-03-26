<?php
require_once 'funciones.php';

class Alumno{

	private $alumno_id, $nombre, $apellido1, $apellido2, $DNI, 
            $caducidad_DNI, $direccion, $localidad, $provincia,
            $codigo_postal, $fecha_nacimiento, $fecha_ingreso,
            $nacionalidad, $estudios, $telefono, $email, $contraseña;

	// getters
	public function __construct($value = 0)  {  $this->alumno_id = $value;   }
	public function getId()              { return $this->alumno_id; }
	public function getNombre()          { return $this->nombre; 	}
    public function getApellido1()       { return $this->apellido1; }
    public function getapellido2()       { return $this->apellido2; }
    public function getDNI()             { return $this->DNI; }
    public function getCaducidad_DNI()   { return $this->caducidad_DNI; }
    public function getDireccion()       { return $this->direccion; }
	public function getLocalidad()       { return $this->localidad; }
    public function getProvincia()       { return $this->provincia; }
    public function getCodigoPostal()    { return $this->codigo_postal; }
	public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function getFechaIngreso()    { return $this->fecha_ingreso; }
    public function getNacionalidad()    { return $this->nacionalidad; }
    public function getEstudios()        { return $this->estudios; }
    public function getTelefono()        { return $this->telefono; }
    public function getEmail()           { return $this->email; }
    public function getContraseña()      { return $this->contraseña; }	
	
	// setters
	public function setNombre($value)           { $this->nombre = $value; }
    public function setApellido1($value)        { $this->apellido1 = $value; }
    public function setApellido2($value)        { $this->apellido2 = $value; }
    public function setDNI($value)              { $this->DNI = $value; }
    public function setCaducidadDNI($value)     { $this->caducidad_DNI = $value; }
    public function setDireccion($value)        { $this->direccion = $value; }
	public function setLocalidad($value)        { $this->localidad = $value; }
	public function setProvincia($value)        { $this->provincia = $value; }
    public function setCodigoPostal($value)     { $this->codigo_postal = $value; }
    public function setFechaNacimiento($value)  { $this->fecha_nacimiento = $value; }
    public function setFechaIngreso($value)     { $this->fecha_ingreso= $value; }
    public function setNacionalidad($value)     { $this->nacionalidad = $value; }
    public function setEstudios($value)         { $this->estudios = $value; }
    public function setTelefono($value)         { $this->telefono = $value; }
    public function setEmail($value)            { $this->email = $value; }
    public function setContraseña($value)       { $this->contraseña = $value; }
	
	// Cargar desde la base de datos
    public function loadFromDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE alumno_id = :id');
        $stmt ->execute(array( ':id' => $this->alumno_id));
        $this->loadFromDBRecord($stmt->fetch(PDO::FETCH_ASSOC));
      }
	
	public function loadFromDBRecord ($registro) {
		$this->nombre           = $registro["nombre"];
        $this->apellido1        = $registro["apellido1"];
        $this->apellido2        = $registro["apellido2"];
        $this->DNI              = $registro["DNI"];
        $this->caducidad_DNI    = $registro['caducidad_DNI'];
        $this->direccion        = $registro["direccion"];
        $this->localidad        = $registro["localidad"];
        $this->provincia        = $registro["provincia"];
		$this->codigo_postal    = $registro["codigo_postal"];
		$this->fecha_nacimiento = $registro["fecha_nacimiento"];
		$this->fecha_ingreso    = $registro["fecha_ingreso"];
		$this->nacionalidad     = $registro["nacionalidad"];
		$this->estudios         = $registro["estudios"];
        $this->telefono         = $registro["telefono"];
		$this->email            = $registro["email"];	
        $this->contraseña       = $registro["contraseña"];	
	}

    //Comprobar si el alumno existe
	public function existsInDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Alumno WHERE alumno_id = :id');
        $stmt ->execute(array( ':id' => $this->alumno_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result != null);        		
	}
	
	//Guardar en la base de datos
    public function saveIntoDB($pdo) {
		// Compruebo si el caso ya existe
		if ($this->existsInDB($pdo)) {
		    // Si existe, actualizo
            $stmt = $pdo->prepare('UPDATE Alumno SET nombre          = :nom,
                                                    apellido1        = :ap1,
                                                    apellido2        = :ap2,
                                                    DNI              = :dni,
                                                    caducidad_DNI    = :cdn,
                                                    direccion        = :di,
                                                    localidad        = :lo,
                                                    provincia        = :pro,
                                                    codigo_postal    = :ccpp,
                                                    fecha_nacimiento = :fn,
                                                    fecha_ingreso    = :fi,
                                                    nacionalidad     = :na,
                                                    estudios         = :es,
                                                    telefono         = :tlf,
                                                    email            = :em,
                                                    contraseña       = :con
                                    WHERE alumno_id = :id');
            $stmt->execute(array(
                ':id'  => $this->alumno_id,
                ':nom' => $this->nombre,
                ':ap1' => $this->apellido1,
                ':ap2' => $this->apellido2,
                ':dni' => $this->DNI,
                ':cdn' => $this->caducidad_DNI,
                ':di'  => $this->direccion,
                ':lo'  => $this->localidad,
                ':pro' => $this->provincia,
                ':ccpp'=> $this->codigo_postal,
                ':fn'  => $this->fecha_nacimiento,
                ':fi'  => $this->fecha_ingreso,
                ':na'  => $this->nacionalidad,
                ':es'  => $this->estudios,
                ':tlf' => $this->telefono,
                ':em'  => $this->email,
                ':con' => $this->contraseña )
            );
		} else {
			// Si no existe, inserto
            $stmt = $pdo->prepare('INSERT INTO Alumno (nombre,
                                                        apellido1,
                                                        apellido2,
                                                        DNI,
                                                        caducidad_DNI,
                                                        direccion,
                                                        localidad,
                                                        provincia,
                                                        codigo_postal,
                                                        fecha_nacimiento,
                                                        fecha_ingreso,
                                                        nacionalidad,
                                                        estudios,
                                                        telefono,
                                                        email,
                                                        contraseña)
                                    VALUES (:nom, :ap1, :ap2, :dni, :cdn, :di, :lo, :pro, :ccpp, :fn, :fi, :na, :es, :tlf, :em, :con)'
                                    );
            $stmt->execute(array(
                ':nom' => $this->nombre,
                ':ap1' => $this->apellido1,
                ':ap2' => $this->apellido2,
                ':dni' => $this->DNI,
                ':cdn' => $this->caducidad_DNI,
                ':di'  => $this->direccion,
                ':lo'  => $this->localidad,
                ':pro' => $this->provincia,
                ':ccpp'=> $this->codigo_postal,
                ':fn'  => $this->fecha_nacimiento,
                ':fi'  => $this->fecha_ingreso,
                ':na'  => $this->nacionalidad,
                ':es'  => $this->estudios,
                ':tlf' => $this->telefono,
                ':em'  => $this->email,
                ':con' => $this->contraseña )
            );

            //Actualizo el id del alumno nuevo
            $id = loadAlumByDNI($pdo, $this->DNI);
            $this->alumno_id = $id['alumno_id'];
		}	
	}
	
    //Borrar de la base de datos
	public function deleteFromDB($pdo) {
		if ($this->existsInDB($pdo)) {
			// Borrar
            $stmt = $pdo->prepare('DELETE FROM Alumno WHERE alumno_id = :id' );
            $stmt->execute(array(':id' => $this->alumno_id));
		}
	}

   // Obtener todos los alumnos de la base de datos
 	static public function getAll($pdo) {
		$lista = Array();
		$stmt = $pdo->query('SELECT * FROM Alumno');
        while ($i = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$item = new Alumno();
			$item->loadFromDBRecord($i);
			$lista[] = $item;
		}
		return $lista;
    }
} 
// fin de la clase 