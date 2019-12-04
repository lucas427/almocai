CREATE DATABASE IF NOT EXISTS `almocai`;
USE `almocai`;

CREATE TABLE IF NOT EXISTS `SemanaCardapio` (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
  `data_inicio` DATE -- O primeiro dia (segunda) da semana, p. ex	. 2019-08-12
);

CREATE TABLE IF NOT EXISTS `DiaAlmoco` (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
	`data` DATE,
	`semanaCardapio_codigo` INT,
	`diaSemana` VARCHAR(45),
	FOREIGN KEY (`semanaCardapio_codigo`) REFERENCES `SemanaCardapio`(`codigo`) 
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

DELIMITER :)

CREATE TRIGGER `cria_dias_semana`
AFTER INSERT ON `SemanaCardapio`
FOR EACH ROW
BEGIN
	INSERT INTO `DiaAlmoco` (`data`, `semanaCardapio_codigo`, `diaSemana`) VALUES
    (new.data_inicio, new.codigo, 'Segunda-feira'),
    (date_add(NEW.data_inicio, interval 1 day), NEW.codigo, 'Terça-feira'),
    (date_add(NEW.data_inicio, interval 2 day), NEW.codigo, 'Quarta-feira'),
    (date_add(NEW.data_inicio, interval 3 day), NEW.codigo, 'Quinta-feira');
END :)
DELIMITER ;

CREATE TABLE IF NOT EXISTS Alimento (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
	`descricao` VARCHAR(100),
	`diaAlmoco_codigo` INT,
	`tipo` VARCHAR(45),
	FOREIGN KEY (`diaAlmoco_codigo`) REFERENCES `DiaAlmoco`(`codigo`) 
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `Frequencia` (
	-- se o usuário geralmente almoça no IF ou nunca almoça
  -- usado para determinar, automaticamente, a presença do aluno varchar
	`codigo` INT PRIMARY KEY,
	`descricao` VARCHAR(100)
);

INSERT INTO `Frequencia` (`codigo`, `descricao`) 
VALUES (1, 'Sempre'), 
			 (2, 'Geralmente'), 
			 (3, 'Pouca vezes'), 
			 (4, 'Nunca');

CREATE TABLE IF NOT EXISTS `Alimentacao` (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
  `descricao` VARCHAR(45)
);

INSERT INTO `Alimentacao` (`codigo`, `descricao`) 
VALUES (1, 'Come Carne'), (2, 'Vegetariano'), (3, 'Vegano');

CREATE TABLE IF NOT EXISTS `Usuario` (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
	`username` VARCHAR(100) UNIQUE,
	`senha` VARCHAR(255),
	`nome` VARCHAR(100),
	`tipo` VARCHAR(50),
	`email` VARCHAR(255),
    `token` VARCHAR(255),
	
	`alimentacao` INT DEFAULT 1,
	FOREIGN KEY (`alimentacao`) REFERENCES `Alimentacao`(`codigo`) 
		ON DELETE SET null
		ON UPDATE SET null,
	
	`frequencia` INT DEFAULT 1,
	FOREIGN KEY (`frequencia`) REFERENCES `Frequencia`(`codigo`)  
		ON DELETE SET null
		ON UPDATE SET null
);

CREATE TABLE IF NOT EXISTS `Presenca` (
	`usuario_cod` INT,
	`diaAlmoco_codigo` INT,
	`presenca` TINYINT,
	PRIMARY KEY (`usuario_cod`, `diaAlmoco_codigo`),

	FOREIGN KEY (`usuario_cod`) REFERENCES `Usuario`(`codigo`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
        
	FOREIGN KEY (`diaAlmoco_codigo`) REFERENCES `DiaAlmoco`(`codigo`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `Intolerancia` (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
	`descricao` VARCHAR(150)
);

CREATE TABLE IF NOT EXISTS `Estado_intolerancia` (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
    `descricao` VARCHAR(255)
);
INSERT INTO `Estado_intolerancia` VALUES (1, 'Pendente'), (2, 'Rejeitada'), (3, 'Validada');

CREATE TABLE IF NOT EXISTS `Usuario_intolerancia` (
	`codigo` INT PRIMARY KEY AUTO_INCREMENT,
    `usuario_cod` INT,
	`intolerancia_cod` INT,
    `estado_cod` INT DEFAULT 1,
    `motivo_rejeicao` TEXT,
	`arquivo` VARCHAR(255), 
	FOREIGN KEY (`usuario_cod`) REFERENCES `Usuario`(`codigo`),
	FOREIGN KEY (`intolerancia_cod`) REFERENCES `Intolerancia`(`codigo`),
	FOREIGN KEY (`estado_cod`) REFERENCES `Estado_intolerancia`(`codigo`)
);

DELIMITER :)
CREATE TRIGGER `AdicionaPresenca`
AFTER INSERT ON `DiaAlmoco` 
FOR EACH ROW
BEGIN
	DECLARE `idFrequencia` INT;
	DECLARE `tipoUsuario` VARCHAR(40);
	DECLARE `finished` INT DEFAULT 0;
	DECLARE `id` INT;
	DECLARE `usuarioCursor` CURSOR FOR SELECT `codigo` FROM `Usuario`;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET `finished` = 1;
    
	OPEN `usuarioCursor`;
    
    `add_presenca` : LOOP FETCH `usuarioCursor` INTO `id`;
    IF `finished` = 1 THEN
      LEAVE `add_presenca`;
    END IF;

    SELECT `frequencia` INTO `idFrequencia` FROM `Usuario` WHERE `codigo` = `id`;
    SELECT `tipo` INTO `tipoUsuario` FROM `Usuario` WHERE `codigo` = `id`;
    IF `tipoUsuario` = 'Aluno' THEN
		IF `idFrequencia` = 1 OR `idFrequencia` = 2 THEN
			INSERT INTO `Presenca` VALUE(id, NEW.codigo, 1);
		ELSE
			INSERT INTO `Presenca` VALUE(id, NEW.codigo, 0);
		END IF;
    END IF;
  END LOOP;

  CLOSE `usuarioCursor`;
END :)
DELIMITER ;

/* create view Semana as Select s.data_inicio, d.diaSemana, a.descricao, a.tipo from SemanaCardapio s, DiaAlmoco d,
 Alimento a where s.codigo = d.semanaCardapio_codigo and d.codigo = a.diaAlmoco_codigo; */