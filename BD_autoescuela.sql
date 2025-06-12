

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema autoescuela
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `autoescuela` DEFAULT CHARACTER SET utf8mb3 ;
USE `autoescuela` ;

-- -----------------------------------------------------
-- Table `autoescuela`.`Alumno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Alumno` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Alumno` (
  `alumno_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL DEFAULT NULL,
  `apellido1` VARCHAR(128) NOT NULL DEFAULT NULL,
  `apellido2` VARCHAR(128) NULL DEFAULT NULL,
  `DNI` VARCHAR(32) NOT NULL,
  `caducidad_DNI` DATE NULL DEFAULT NULL,
  `direccion` VARCHAR(255) NULL DEFAULT NULL,
  `localidad` VARCHAR(128) NULL DEFAULT NULL,
  `provincia` VARCHAR(45) NULL DEFAULT NULL,
  `codigo_postal` INT NULL DEFAULT NULL,
  `fecha_nacimiento` DATE NULL DEFAULT NULL,
  `fecha_ingreso` DATE NULL DEFAULT NULL,
  `nacionalidad` VARCHAR(45) NULL DEFAULT NULL,
  `estudios` VARCHAR(255) NULL DEFAULT NULL,
  `telefono` INT NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `contraseña` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`alumno_id`),
  UNIQUE INDEX `DNI_UNIQUE` (`DNI` ASC) VISIBLE,
  UNIQUE INDEX `alumno_id_UNIQUE` (`alumno_id` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Autoescuela`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Autoescuela` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Autoescuela` (
  `autoescuela_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NULL DEFAULT NULL,
  `razon_social` VARCHAR(128) NULL DEFAULT NULL,
  `n_centro` VARCHAR(128) NULL DEFAULT NULL,
  `seccion` VARCHAR(32) NULL DEFAULT NULL,
  `telefono` INT NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `direccion` VARCHAR(255) NULL DEFAULT NULL,
  `codigo_postal` INT NULL DEFAULT NULL,
  `localidad` VARCHAR(128) NULL DEFAULT NULL,
  `provincia` VARCHAR(128) NULL DEFAULT NULL,
  `CIF` INT NULL DEFAULT NULL,
  `IVA` INT NULL DEFAULT NULL,
  `DC` INT NULL DEFAULT NULL,
  PRIMARY KEY (`autoescuela_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Permiso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Permiso` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Permiso` (
  `permiso_id` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`permiso_id`),
  UNIQUE INDEX `permiso_id_UNIQUE` (`permiso_id` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Profesor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Profesor` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Profesor` (
  `profesor_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(128) NULL DEFAULT NULL,
  `apellido1` VARCHAR(128) NULL DEFAULT NULL,
  `apellido2` VARCHAR(128) NULL DEFAULT NULL,
  `DNI` VARCHAR(45) NULL DEFAULT NULL,
  `caducidad_DNI` DATE NULL DEFAULT NULL,
  `direccion` VARCHAR(255) NULL DEFAULT NULL,
  `localidad` VARCHAR(128) NULL DEFAULT NULL,
  `provincia` VARCHAR(50) NULL DEFAULT NULL,
  `codigo_postal` INT NULL DEFAULT NULL,
  `fecha_nacimiento` DATE NULL DEFAULT NULL,
  `fecha_ingreso` DATE NULL DEFAULT NULL,
  `numero_ss` BIGINT NULL DEFAULT NULL,
  `perfil_director` TINYINT NULL DEFAULT NULL,
  `perfil_administrad` TINYINT NULL DEFAULT NULL,
  `telefono` INT NULL DEFAULT NULL,
  `email` VARCHAR(128) NULL DEFAULT NULL,
  `contraseña` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`profesor_id`),
  UNIQUE INDEX `profesor_id_UNIQUE` (`profesor_id` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `DNI_UNIQUE` (`DNI` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Prof_permiso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Prof_permiso` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Prof_permiso` (
  `prof-permiso_id` INT NOT NULL AUTO_INCREMENT,
  `profesor_id` INT NULL DEFAULT NULL,
  `permiso_id` INT NULL DEFAULT NULL,
  `fecha` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`prof-permiso_id`),
  INDEX `id_permiso_idx` (`permiso_id` ASC) VISIBLE,
  INDEX `profesor-id_idx` (`profesor_id` ASC) VISIBLE,
  CONSTRAINT `permiso_id`
    FOREIGN KEY (`permiso_id`)
    REFERENCES `autoescuela`.`Permiso` (`permiso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `profesor-id`
    FOREIGN KEY (`profesor_id`)
    REFERENCES `autoescuela`.`Profesor` (`profesor_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Curso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Curso` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Curso` (
  `curso_id` INT NOT NULL AUTO_INCREMENT,
  `alumno_id` INT NULL DEFAULT NULL,
  `prof-permiso_id` INT NULL DEFAULT NULL,
  `fecha_inicio` DATE NULL DEFAULT NULL,
  `finalizado` TINYINT NULL DEFAULT NULL,
  `fecha_finalizacion` DATE NULL DEFAULT NULL,
  `pagado` TINYINT NULL DEFAULT NULL,
  `seccion` VARCHAR(32) NULL DEFAULT NULL,
  `numero_curso` VARCHAR(50) CHARACTER SET 'utf8mb3' NULL DEFAULT NULL,
  `permiso_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`curso_id`),
  UNIQUE INDEX `curso_id_UNIQUE` (`curso_id` ASC) VISIBLE,
  INDEX `alumno_id_idx` (`alumno_id` ASC) VISIBLE,
  INDEX `permiso_id_idx` (`prof-permiso_id` ASC) VISIBLE,
  INDEX `fk_permiso_id` (`permiso_id` ASC) VISIBLE,
  CONSTRAINT `alumno_id`
    FOREIGN KEY (`alumno_id`)
    REFERENCES `autoescuela`.`Alumno` (`alumno_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_permiso_id`
    FOREIGN KEY (`permiso_id`)
    REFERENCES `autoescuela`.`Permiso` (`permiso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `prof-permiso_id`
    FOREIGN KEY (`prof-permiso_id`)
    REFERENCES `autoescuela`.`Prof_permiso` (`prof-permiso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Tasa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Tasa` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Tasa` (
  `tasa_id` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(128) NULL DEFAULT NULL,
  `precio` FLOAT NULL DEFAULT NULL,
  PRIMARY KEY (`tasa_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Cargo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Cargo` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Cargo` (
  `cargo_id` INT NOT NULL AUTO_INCREMENT,
  `tasa_id` INT NOT NULL,
  `curso_id` INT NOT NULL,
  `fecha` DATE NOT NULL,
  PRIMARY KEY (`cargo_id`),
  INDEX `fk_Cargo_curso_id` (`curso_id` ASC) VISIBLE,
  INDEX `tasa_id` (`tasa_id` ASC) VISIBLE,
  CONSTRAINT `fk_Cargo_curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `autoescuela`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `tasa_id`
    FOREIGN KEY (`tasa_id`)
    REFERENCES `autoescuela`.`Tasa` (`tasa_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Enseñanza`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Enseñanza` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Enseñanza` (
  `enseñanza_id` INT NOT NULL AUTO_INCREMENT,
  `permiso_id` INT NULL DEFAULT NULL,
  `tipo` VARCHAR(128) NULL DEFAULT NULL,
  `precio` INT NULL DEFAULT NULL,
  PRIMARY KEY (`enseñanza_id`),
  INDEX `permiso_id_fk` (`permiso_id` ASC) VISIBLE,
  CONSTRAINT `permiso_id_fk`
    FOREIGN KEY (`permiso_id`)
    REFERENCES `autoescuela`.`Permiso` (`permiso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Clase`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Clase` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Clase` (
  `clase_id` INT NOT NULL AUTO_INCREMENT,
  `enseñanza_id` INT NULL DEFAULT NULL,
  `fecha` DATE NULL DEFAULT NULL,
  `hora` INT NULL DEFAULT NULL,
  `curso_id` INT NOT NULL,
  `profesor_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`clase_id`),
  UNIQUE INDEX `clase_id_UNIQUE` (`clase_id` ASC) VISIBLE,
  INDEX `enseñanza_id_idx` (`enseñanza_id` ASC) VISIBLE,
  INDEX `curso_id` (`curso_id` ASC) VISIBLE,
  INDEX `clase_profesor` (`profesor_id` ASC) VISIBLE,
  CONSTRAINT `clase_enseñanza_id`
    FOREIGN KEY (`enseñanza_id`)
    REFERENCES `autoescuela`.`Enseñanza` (`enseñanza_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `clase_profesor`
    FOREIGN KEY (`profesor_id`)
    REFERENCES `autoescuela`.`Profesor` (`profesor_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `autoescuela`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Curso_profesor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Curso_profesor` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Curso_profesor` (
  `curso_id` INT NOT NULL,
  `profesor_id` INT NOT NULL,
  CONSTRAINT `curso-prof_curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `autoescuela`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `curso-prof_profesor_id`
    FOREIGN KEY (`profesor_id`)
    REFERENCES `autoescuela`.`Profesor` (`profesor_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Vehiculo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Vehiculo` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Vehiculo` (
  `vehiculo_id` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(50) NULL DEFAULT NULL,
  `marca` VARCHAR(50) NULL DEFAULT NULL,
  `matricula` VARCHAR(10) NULL DEFAULT NULL,
  PRIMARY KEY (`vehiculo_id`),
  UNIQUE INDEX `matricula` (`matricula` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Relacion_examen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Relacion_examen` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Relacion_examen` (
  `relacion_id` INT NOT NULL AUTO_INCREMENT,
  `profesor_id` INT NOT NULL,
  `fecha_presentacion` DATE NULL DEFAULT NULL,
  `fecha_examen` DATE NULL DEFAULT NULL,
  `tipo_prueba` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`relacion_id`),
  INDEX `fk_relacion_examen_profesor_id` (`profesor_id` ASC) VISIBLE,
  CONSTRAINT `fk_relacion_examen_profesor_id`
    FOREIGN KEY (`profesor_id`)
    REFERENCES `autoescuela`.`Profesor` (`profesor_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Examen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Examen` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Examen` (
  `examen_id` INT NOT NULL AUTO_INCREMENT,
  `curso_id` INT NULL DEFAULT NULL,
  `profesor_id` INT NULL DEFAULT NULL,
  `vehiculo_id` INT NULL DEFAULT NULL,
  `estado` VARCHAR(45) NULL DEFAULT NULL,
  `relacion_id` INT NOT NULL,
  `autoescuela_id` INT NOT NULL,
  PRIMARY KEY (`examen_id`),
  INDEX `FK_RELACION_ID` (`relacion_id` ASC) VISIBLE,
  INDEX `fk_autoescuela_id_examen` (`autoescuela_id` ASC) VISIBLE,
  INDEX `examen_profesor_id` (`profesor_id` ASC) VISIBLE,
  INDEX `examen_vehiculo_id` (`vehiculo_id` ASC) VISIBLE,
  INDEX `examen-curso_id` (`curso_id` ASC) VISIBLE,
  CONSTRAINT `examen-curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `autoescuela`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `examen_curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `autoescuela`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `examen_profesor_id`
    FOREIGN KEY (`profesor_id`)
    REFERENCES `autoescuela`.`Profesor` (`profesor_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `examen_vehiculo_id`
    FOREIGN KEY (`vehiculo_id`)
    REFERENCES `autoescuela`.`Vehiculo` (`vehiculo_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_autoescuela_id_examen`
    FOREIGN KEY (`autoescuela_id`)
    REFERENCES `autoescuela`.`Autoescuela` (`autoescuela_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_RELACION_ID`
    FOREIGN KEY (`relacion_id`)
    REFERENCES `autoescuela`.`Relacion_examen` (`relacion_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `autoescuela`.`Pago`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `autoescuela`.`Pago` ;

CREATE TABLE IF NOT EXISTS `autoescuela`.`Pago` (
  `pago_id` INT NOT NULL AUTO_INCREMENT,
  `curso_id` INT NOT NULL,
  `fecha` DATE NULL DEFAULT NULL,
  `importe` FLOAT NULL DEFAULT NULL,
  `concepto` VARCHAR(128) NULL DEFAULT NULL,
  `numero_recibo` VARCHAR(40) NULL DEFAULT NULL,
  `anulado` INT NULL DEFAULT NULL,
  `motivo_anulado` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`pago_id`),
  INDEX `fk_Pago_curso_id` (`curso_id` ASC) VISIBLE,
  CONSTRAINT `fk_Pago_curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `autoescuela`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = utf8mb3;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
