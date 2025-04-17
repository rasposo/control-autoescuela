-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema proyecto
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema proyecto
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `proyecto` DEFAULT CHARACTER SET utf8 ;
USE `proyecto` ;

-- -----------------------------------------------------
-- Table `proyecto`.`Alumno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Alumno` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Alumno` (
  `alumno_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NULL,
  `apellido1` VARCHAR(128) NULL,
  `apellido2` VARCHAR(128) NULL,
  `DNI` VARCHAR(32) NOT NULL,
  `caducidad_DNI` DATE NULL,
  `direccion` VARCHAR(255) NULL,
  `localidad` VARCHAR(128) NULL,
  `provincia` VARCHAR(45) NULL,
  `codigo_postal` INT NULL,
  `fecha_nacimiento` DATE NULL,
  `fecha_ingreso` DATE NULL,
  `nacionalidad` VARCHAR(45) NULL,
  `estudios` VARCHAR(255) NULL,
  `email` VARCHAR(255) NULL,
  `contraseña` VARCHAR(45) NULL,
  PRIMARY KEY (`alumno_id`),
  UNIQUE INDEX `DNI_UNIQUE` (`DNI` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `alumno_id_UNIQUE` (`alumno_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Profesor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Profesor` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Profesor` (
  `profesor_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(128) NULL,
  `apellido1` VARCHAR(128) NULL,
  `apellido2` VARCHAR(128) NULL,
  `DNI` VARCHAR(45) NULL,
  `caducidad_DNI` DATE NULL,
  `direccion` VARCHAR(255) NULL,
  `localidad` VARCHAR(128) NULL,
  `provincia` VARCHAR(45) NULL,
  `codigo_postal` INT NULL,
  `fecha_nacimiento` DATE NULL,
  `fecha_ingreso` DATE NULL,
  `numero_ss` INT NULL,
  `perfil_director` TINYINT NULL,
  `perfil_administrad` TINYINT NULL,
  `teléfono` INT NULL,
  `email` VARCHAR(128) NULL,
  `contraseña` VARCHAR(45) NULL,
  PRIMARY KEY (`profesor_id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `DNI_UNIQUE` (`DNI` ASC),
  UNIQUE INDEX `profesor_id_UNIQUE` (`profesor_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Permiso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Permiso` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Permiso` (
  `permiso_id` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NULL,
  PRIMARY KEY (`permiso_id`),
  UNIQUE INDEX `permiso_id_UNIQUE` (`permiso_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Prof-permiso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Prof-permiso` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Prof-permiso` (
  `prof-permiso_id` INT NOT NULL AUTO_INCREMENT,
  `profesor_id` INT NULL,
  `permiso_id` INT NULL,
  `fecha` DATE NULL,
  INDEX `id_permiso_idx` (`permiso_id` ASC),
  INDEX `profesor-id_idx` (`profesor_id` ASC),
  PRIMARY KEY (`prof-permiso_id`),
  CONSTRAINT `permiso_id`
    FOREIGN KEY (`permiso_id`)
    REFERENCES `proyecto`.`Permiso` (`permiso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `prof-permiso_profesor-id`
    FOREIGN KEY (`profesor_id`)
    REFERENCES `proyecto`.`Profesor` (`profesor_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Curso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Curso` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Curso` (
  `curso_id` INT NOT NULL AUTO_INCREMENT,
  `alumno_id` INT NULL,
  `prof-permiso_id` INT NULL,
  `fecha_inicio` DATE NULL,
  `finalizado` TINYINT NULL,
  `fecha_finalizacion` DATE NULL,
  `pagado` TINYINT NULL,
  PRIMARY KEY (`curso_id`),
  UNIQUE INDEX `curso_id_UNIQUE` (`curso_id` ASC),
  INDEX `alumno_id_idx` (`alumno_id` ASC),
  INDEX `permiso_id_idx` (`prof-permiso_id` ASC),
  CONSTRAINT `curso_alumno_id`
    FOREIGN KEY (`alumno_id`)
    REFERENCES `proyecto`.`Alumno` (`alumno_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `curso_prof-permiso_id`
    FOREIGN KEY (`prof-permiso_id`)
    REFERENCES `proyecto`.`Prof-permiso` (`prof-permiso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `proyecto`.`Curso-profesor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Curso-profesor` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Curso` (
  `curso_id` INT NOT NULL,
  `profesor_id` INT NOT NULL,
  PRIMARY KEY (`curso_id`, `profesor_id`),
  CONSTRAINT `curso-prof_curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `proyecto`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `curso_prof-curso_id`
    FOREIGN KEY (`profesor_id`)
    REFERENCES `proyecto`.`Profesor` (`profesor_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Destreza`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Destreza` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Destreza` (
  `destreza_id` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NULL,
  PRIMARY KEY (`destreza_id`),
  UNIQUE INDEX `destrezas_id_UNIQUE` (`destreza_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Precio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Precio` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Precio` (
  `precio_id` INT NOT NULL,
  `importe` FLOAT NULL,
  PRIMARY KEY (`precio_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Enseñanza`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Enseñanza` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Enseñanza` (
  `enseñanza_id` INT NOT NULL AUTO_INCREMENT,
  `precio_id` INT NULL,
  `curso_id` INT NULL,
  `destreza_id` INT NULL,
  INDEX `destreza_id_idx` (`destreza_id` ASC),
  INDEX `precio_id_idx` (`precio_id` ASC),
  INDEX `curso_id_idx` (`curso_id` ASC),
  PRIMARY KEY (`enseñanza_id`),
  CONSTRAINT `enseñanza_curso_id`
    FOREIGN KEY (`curso_id`)
    REFERENCES `proyecto`.`Curso` (`curso_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `enseñanza_destreza_id`
    FOREIGN KEY (`destreza_id`)
    REFERENCES `proyecto`.`Destreza` (`destreza_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `enseñanza_precio_id`
    FOREIGN KEY (`precio_id`)
    REFERENCES `proyecto`.`Precio` (`precio_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Clase`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Clase` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Clase` (
  `clase_id` INT NOT NULL AUTO_INCREMENT,
  `enseñanza_id` INT NULL,
  `fecha` DATE NULL,
  `hora` INT NULL,
  PRIMARY KEY (`clase_id`),
  UNIQUE INDEX `clase_id_UNIQUE` (`clase_id` ASC),
  INDEX `enseñanza_id_idx` (`enseñanza_id` ASC),
  CONSTRAINT `enseñanza_id`
    FOREIGN KEY (`enseñanza_id`)
    REFERENCES `proyecto`.`Enseñanza` (`enseñanza_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto`.`Pago`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Pago` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Pago` (
  `pago_id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NULL,
  `Importe` FLOAT NULL,
  `clase_id` INT NULL,
  PRIMARY KEY (`pago_id`),
  UNIQUE INDEX `pago_id_UNIQUE` (`pago_id` ASC),
  INDEX `clase_id_idx` (`clase_id` ASC),
  CONSTRAINT `pago_clase_id`
    FOREIGN KEY (`clase_id`)
    REFERENCES `proyecto`.`Clase` (`clase_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `proyecto`.`Autoescuela`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto`.`Autoescuela` ;

CREATE TABLE IF NOT EXISTS `proyecto`.`Autoescuela` (
  `autoescuela_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NULL,
  `razon_social` VARCHAR(128) NULL,
  `n_centro` VARCHAR(128) NULL,
  `seccion` VARCHAR(32) NULL,
  `telefono` INT NULL,
  `email` VARCHAR(255) NULL,
  `direccion` VARCHAR(255) NULL,
  `codigo_postal` INT NULL,
  `localidad` VARCHAR(128) NULL,
  `provincia` VARCHAR(45) NULL,
  `CIF` INT NULL,
  'IVA' INT NULL,

  PRIMARY KEY (`autoescuela_id`))
ENGINE = InnoDB;

CREATE TABLE proyecto.Examen (
    `examen_id` INT NOT NULL AUTO_INCREMENT,
    `fecha_presentacion` DATE NULL,
    `fecha_examen` DATE NULL,
    `tipo` VARCHAR (255) NULL,
    `curso_id` INT NULL,
    `profesor_id` INT NULL,
    `vehiculo_id` INT NULL,
    `estado` VARCHAR (45) NULL,
    PRIMARY KEY (`examen_id`)
    CONSTRAINT `examen_curso_id`
      FOREIGN KEY (`curso_id`)
      REFERENCES `proyecto`.`Curso` (`curso_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE)
    ENGINE = InnoDB;

    
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
