<?php
require_once 'funciones.php';

class Profesor{

	private $profesor_id, $nombre, $apellido1, $apellido2, $DNI, 
            $caducidad_DNI, $direccion, $localidad, $provincia,
            $codigo_postal, $fecha_nacimiento, $fecha_ingreso,
            $numero_ss, $perfil_director,$perfil_administrador, 
            $telefono, $email, $contraseña, $permisos;

	// getters
	public function __construct($value = 0)  {  $this->profesor_id = $value;   }
	public function getId()              { return $this->profesor_id; }
	public function getNombre()          { return $this->nombre; 	}
    public function getApellido1()       { return $this->apellido1; }
    public function getapellido2()       { return $this->apellido2; }
    public function getDNI()             { return $this->DNI; }
    public function getCaducidadDNI()    { return $this->caducidad_DNI; }
    public function getDireccion()       { return $this->direccion; }
	public function getLocalidad()       { return $this->localidad; }
    public function getProvincia()       { return $this->provincia; }
    public function getCodigoPostal()    { return $this->codigo_postal; }
	public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function getFechaIngreso()    { return $this->fecha_ingreso; }
    public function getNumeroSS()        { return $this->numero_ss; }
    public function getPerfilDirector()  { return $this->perfil_director; }
    public function getPerfilAdmin()     { return $this->perfil_administrador; }
    public function getTelefono()        { return $this->telefono; }
    public function getEmail()           { return $this->email; }
    public function getContraseña()      { return $this->contraseña; }
    public function getPermisos()        { return $this->permisos; }
	
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
    public function setNumeroSS($value)         { $this->numero_ss = $value; }
    public function setPerfilDirector($value)   { $this->perfil_director = $value; }
    public function setPerfilAdmin($value)      { $this->perfil_administrador = $value; }
    public function setTelefono($value)         { $this->telefono = $value; }
    public function setEmail($value)            { $this->email = $value; }
    public function setContraseña($value)       { $this->contraseña = $value; }
    public function setPermisos($value)         { $this->permisos = $value; }
	
	// Cargar desde la base de datos
    public function loadFromDB($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM Profesor WHERE profesor_id = :pid");
        $stmt->execute(array(':pid' => $this->profesor_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT permiso, fecha FROM Prof_permiso WHERE profesor_id = :pid");
        $stmt->execute(array(':pid' => $this->profesor_id));
        $col = $stmt->fetchall(PDO::FETCH_ASSOC);
        $row['permisos'] = $col;

        $this->loadFromDBRecord($row);
      }
	
	public function loadFromDBRecord($registro) {
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
		$this->numero_ss        = $registro["numero_ss"];
		$this->perfil_director  = $registro["perfil_director"];
        $this->perfil_administrador  = $registro["perfil_administrad"];
        $this->telefono         = $registro["telefono"];
		$this->email            = $registro["email"];	
        $this->contraseña       = $registro["contraseña"];
        $this->permisos         = $registro["permisos"];
	}

    //Comprobar si el profesor existe
	public function existsInDB($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Profesor WHERE profesor_id = :id');
        $stmt ->execute(array( ':id' => $this->profesor_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ( $result != null);
	}
	
	//Guardar en la base de datos
    public function saveIntoDB($pdo) {
		// Compruebo si el caso ya existe
		if ($this->existsInDB($pdo)) {
		    // Si existe, actualizo
            $stmt = $pdo->prepare('UPDATE Profesor SET nombre          = :nom,
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
                                                    numero_ss        = :nss,
                                                    perfil_director  = :pdi,
                                                    perfil_administrad = :pad,
                                                    telefono         = :tlf,
                                                    email            = :em,
                                                    contraseña       = :con
                                    WHERE profesor_id = :id');
            $stmt->execute(array(
                ':id'  => $this->profesor_id,
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
                ':nss' => $this->numero_ss,
                ':pdi' => $this->perfil_director,
                ':pad' => $this->perfil_administrador,
                ':tlf' => $this->telefono,
                ':em'  => $this->email,
                ':con' => $this->contraseña )
            );

            //borrarmos entradas antiguas de permisos
            $stmt = $pdo->prepare('DELETE FROM Prof_permiso WHERE profesor_id = :pid' );
            $stmt->execute(array( ':pid'=> $this->profesor_id));
            //introducimos las nuevas
            $new_permisos = $this->permisos;
            foreach( $new_permisos as $permiso) {
                $stmt = $pdo->prepare('INSERT INTO Prof_permiso (profesor_id, permiso, fecha) VALUES (:pid, :per, :fech)');
                $stmt->execute(array( ':pid' => $this->profesor_id, ':per' => $permiso['permiso'], ':fech' => $permiso['fecha']));
            };

		} else {
			// Si no existe, inserto
            $stmt = $pdo->prepare('INSERT INTO Profesor (nombre,
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
                                                        numero_ss,
                                                        perfil_director,
                                                        perfil_administrad,
                                                        telefono,
                                                        email,
                                                        contraseña)
                                    VALUES (:nom, :ap1, :ap2, :dni, :cdn, :di, :lo, :pro, :ccpp, :fn, :fi, :nss, :pdi, :pad, :tlf, :em, :con)'
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
                ':nss' => $this->numero_ss,
                ':pdi' => $this->perfil_director,
                ':pad' => $this->perfil_administrador,
                ':tlf' => $this->telefono,
                ':em'  => $this->email,
                ':con' => $this->contraseña )
            );


            //Actualizo el id del profesor nuevo
            $id = loadProfeByDNI($pdo, $this->DNI);
            $this->profesor_id = $id['profesor_id'];
		    
            //Con la id del profesor, inserto los permisos
            $new_permisos = $this->permisos;
            foreach($new_permisos as $permiso) {
                $stmt = $pdo->prepare('INSERT INTO Prof_permiso (profesor_id, permiso, fecha) VALUES (:pid, :per, :fech)');
                $stmt->execute(array( ':pid' => $this->profesor_id, ':per' => $permiso['permiso'], ':fech' => $permiso['fecha']));
            }
        }
	}
	
    //Borrar de la base de datos
	public function deleteFromDB($pdo) {
		if ($this->existsInDB($pdo)) {
			// Borrar
            $stmt = $pdo->prepare('DELETE FROM Profesor WHERE profesor_id = :id' );
            $stmt->execute(array(':id' => $this->profesor_id));

            $stmt = $pdo->prepare('DELETE FROM Prof_permiso WHERE profesor_id = :id' );
            $stmt->execute(array(':id' => $this->profesor_id));
		}
	}

} 
// fin de la clase
?>