-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Creato il: Nov 12, 2023 alle 11:48
-- Versione del server: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- Versione PHP: 8.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

SET @nomedb = "Accert";

SET @query1 = CONCAT
('CREATE DATABASE IF NOT EXISTS `',@nomedb,'` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci'
);

PREPARE stmt FROM @query1;
EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @query1 = CONCAT('use ',@nomedb);
PREPARE stmt FROM @query1;
EXECUTE stmt; DEALLOCATE PREPARE stmt;

DELIMITER $$

DELIMITER $$
CREATE PROCEDURE `spCreaAccertamento` (IN `idAccertamentoPadre` INT, IN `anno` INT, IN `targa` CHAR(15), IN `luogo` VARCHAR(200), IN `data` DATETIME, IN `descrizione` VARCHAR(200), IN `descrizione_estesa` LONGTEXT, IN `_idTipoAccertamento` INT, IN `idOperatore` INT, IN `ruolo` INT, IN `descRuolo` VARCHAR(300), OUT `_idAccertamento` INT)  MODIFIES SQL DATA BEGIN
    DECLARE rec1 ROW TYPE OF AttivitàAccertamento;
    DECLARE rec2 ROW TYPE OF OperatoreNuovaAttività;
    DECLARE ihh INT;
    DECLARE cnt INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
      ROLLBACK;
      RESIGNAL;
    END;
    SET @@SESSION.max_sp_recursion_depth=25; 

    START TRANSACTION;
    
    INSERT INTO Accertamento(idAccertamentoPadre,numero,anno,targa,luogo,data,descrizione,descrizione_estesa,idTipoAccertamento)
    VALUES(idAccertamentoPadre,numeroNuovoAccertamento(anno,_idTipoAccertamento),anno,targa,luogo,data,descrizione,descrizione_estesa,_idTipoAccertamento);
    SET _idAccertamento=LAST_INSERT_ID();

    FOR rec2 IN ( select * from OperatoreNuovaAttività where idTipoAccertamento=_idTipoAccertamento)
        DO INSERT INTO SoggettoAccertamento(idSoggetto,idAccertamento,ruolo,descRuolo) VALUES(rec2.idSoggetto,_idAccertamento,1,rec2.descrizione);
    END FOR;
    
    SELECT COUNT(*) INTO cnt FROM SoggettoAccertamento WHERE idAccertamento=_idAccertamento AND idSoggetto=idOperatore;
    IF (cnt=0) THEN
        INSERT INTO SoggettoAccertamento(idSoggetto,idAccertamento,ruolo,descRuolo) VALUES(idOperatore,_idAccertamento,ruolo,descRuolo);
    END IF;

    FOR rec1 IN ( select * from AttivitàAccertamento where idTipoAccertamento=_idTipoAccertamento)
     DO CALL spCreaAccertamento(_idAccertamento,anno,NULL,'Ufficio',NULL,rec1.descrizione,NULL,rec1.idTipoAccertamentoFiglio,idOperatore,ruolo,'Creatore',@ihh);
    END FOR;

    COMMIT WORK;
END$$

CREATE FUNCTION `accertatoriAttività` (IN `ida` INT) RETURNS TEXT CHARSET utf8mb4 READS SQL DATA BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT('o',sa.idSoggetto,'o') into nomi from Soggetto s JOIN SoggettoAccertamento sa 
  on s.idSoggetto=sa.idSoggetto 
  JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento 
  where sa.ruolo =1 and sa.idAccertamento=ida;
  RETURN nomi;
END$$

CREATE FUNCTION `numeroNuovoAccertamento` (`_anno` INT, `_tipo` INT) RETURNS INT(11) DETERMINISTIC BEGIN
 DECLARE num INT;
 SELECT MAX(numero) INTO num FROM Accertamento where anno=_anno and idTipoAccertamento=_tipo;
 IF num IS NULL THEN
  SET num=0;
 END IF;
 RETURN num+1;
END$$

CREATE FUNCTION `responsabiliAccertamento` (IN `ida` INT) RETURNS TEXT CHARSET latin1 COLLATE latin1_general_ci READS SQL DATA BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT(s.nome) INTO nomi from Soggetto s JOIN SoggettoAccertamento sa on s.idSoggetto=sa.idSoggetto JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento where sa.ruolo =2 and sa.idAccertamento=ida;
  RETURN nomi;
END$$

CREATE FUNCTION `soggettiAccertamento` (IN `ida` INT) RETURNS TEXT CHARSET utf8mb4 READS SQL DATA BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT(s.nome) INTO nomi from Soggetto s JOIN SoggettoAccertamento sa on s.idSoggetto=sa.idSoggetto JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento where sa.ruolo >1 and sa.idAccertamento=ida;
  RETURN nomi;
END$$

DELIMITER ;

CREATE TABLE `Accertamento` (
  `idAccertamento` int(11) NOT NULL,
  `idAccertamentoPadre` int(11) DEFAULT NULL,
  `numero` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `targa` char(15) COLLATE latin1_general_ci DEFAULT NULL,
  `luogo` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `data` datetime(3) DEFAULT NULL,
  `descrizione` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `descrizione_estesa` longtext COLLATE latin1_general_ci DEFAULT NULL,
  `eliminato` tinyint(4) NOT NULL DEFAULT 0,
  `NumNdR` int(11) DEFAULT NULL,
  `idTipoAccertamento` int(11) NOT NULL DEFAULT 1
) ;

CREATE TABLE `Attivita` (
  `idAttivita` int(11) NOT NULL,
  `descrizione` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `idAccertamento` int(11) NOT NULL,
  `data` datetime(3) DEFAULT NULL,
  `attivita` mediumtext COLLATE latin1_general_ci DEFAULT NULL,
  `dataScadenza` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `AttivitàAccertamento` (
  `idAttivitàAccertamento` int(11) NOT NULL,
  `idTipoAccertamento` int(11) NOT NULL,
  `idTipoAccertamentoFiglio` int(11) NOT NULL,
  `descrizione` text COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `Documento` (
  `idDocumento` int(11) NOT NULL,
  `idAccertamento` int(11) NOT NULL,
  `filename` text COLLATE latin1_general_ci NOT NULL,
  `dataDocumento` datetime(3) DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `descrizione` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `conttype` tinytext COLLATE latin1_general_ci NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
CREATE TABLE `listaAttività` (
`data` datetime(3)
,`idAccertamento` int(11)
,`tipo` tinytext
,`numeroAccertamento` varchar(279)
,`luogo` varchar(200)
,`numero` int(11)
,`anno` int(11)
,`descrizione` varchar(200)
,`soggetti` text
,`idAccertatori` text
,`idTipoAccertamento` int(11)
,`descrizione_estesa` longtext
,`idAccertamentoPadre` int(11)
,`descrizionePadre` varchar(200)
);
CREATE TABLE `listaAttività2` (
`data` datetime(3)
,`idAccertamento` int(11)
,`tipo` tinytext
,`numeroAccertamento` varchar(279)
,`luogo` varchar(200)
,`numero` int(11)
,`anno` int(11)
,`descrizione` varchar(200)
,`responsabili` text
,`responsabiliPadre` text
,`idAccertatori` text
,`idTipoAccertamento` int(11)
,`descrizione_estesa` longtext
,`idAccertamentoPadre` int(11)
,`descrizionePadre` varchar(200)
);

CREATE TABLE `Log` (
  `idLog` int(11) NOT NULL,
  `dataora` datetime NOT NULL DEFAULT current_timestamp(),
  `operazione` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `idPersona` int(11) DEFAULT NULL,
  `idAccertamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `OperatoreNuovaAttività` (
  `idOperatoriNuovaAttività` int(11) NOT NULL,
  `idTipoAccertamento` int(11) NOT NULL,
  `idSoggetto` int(11) NOT NULL,
  `descrizione` tinytext CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `RuoloSoggetto` (
  `idRuolo` smallint(6) NOT NULL,
  `nomeRuolo` varchar(30) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `Soggetto` (
  `idSoggetto` int(11) NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `dataNascita` datetime(3) DEFAULT NULL,
  `luogoNascita` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `residenza` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `tel` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `mail` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `documento` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `indirizzo` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `tipo` smallint(6) NOT NULL,
  `winUN` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `login` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `permessi` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `societa` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `pwdhash` text COLLATE latin1_general_ci DEFAULT NULL,
  `note` longtext COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `SoggettoAccertamento` (
  `idSoggetto` int(11) NOT NULL,
  `idAccertamento` int(11) NOT NULL,
  `ruolo` smallint(6) NOT NULL,
  `idSoggettoAccertamento` int(11) NOT NULL,
  `descRuolo` varchar(300) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `TipoAccertamento` (
  `idTipoAccertamento` int(11) NOT NULL,
  `nome` tinytext COLLATE latin1_general_ci NOT NULL,
  `codice` tinytext COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
DROP TABLE IF EXISTS `listaAttività`;

CREATE VIEW `listaAttività`  AS SELECT DISTINCT `t2`.`data` AS `data`, `t2`.`idAccertamento` AS `idAccertamento`, `t4`.`nome` AS `tipo`, concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`, `t2`.`luogo` AS `luogo`, `t2`.`numero` AS `numero`, `t2`.`anno` AS `anno`, `t2`.`descrizione` AS `descrizione`, `soggettiAccertamento`(`t2`.`idAccertamento`) AS `soggetti`, `accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`, `t2`.`idTipoAccertamento` AS `idTipoAccertamento`, `t2`.`descrizione_estesa` AS `descrizione_estesa`, `t2`.`idAccertamentoPadre` AS `idAccertamentoPadre`, `t5`.`descrizione` AS `descrizionePadre` FROM ((((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) left join `Accertamento` `t5` on(`t5`.`idAccertamento` = `t2`.`idAccertamentoPadre`)) ;
DROP TABLE IF EXISTS `listaAttività2`;

CREATE VIEW `listaAttività2`  AS SELECT DISTINCT `t2`.`data` AS `data`, `t2`.`idAccertamento` AS `idAccertamento`, `t4`.`nome` AS `tipo`, concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`, `t2`.`luogo` AS `luogo`, `t2`.`numero` AS `numero`, `t2`.`anno` AS `anno`, `t2`.`descrizione` AS `descrizione`, `responsabiliAccertamento`(`t2`.`idAccertamento`) AS `responsabili`, `responsabiliAccertamento`(`t2`.`idAccertamentoPadre`) AS `responsabiliPadre`, `accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`, `t2`.`idTipoAccertamento` AS `idTipoAccertamento`, `t2`.`descrizione_estesa` AS `descrizione_estesa`, `t2`.`idAccertamentoPadre` AS `idAccertamentoPadre`, `t5`.`descrizione` AS `descrizionePadre` FROM ((((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) left join `Accertamento` `t5` on(`t5`.`idAccertamento` = `t2`.`idAccertamentoPadre`)) ;


ALTER TABLE `Accertamento`
  ADD PRIMARY KEY (`idAccertamento`),
  ADD UNIQUE KEY `uniconumacc` (`anno`,`numero`,`idTipoAccertamento`),
  ADD KEY `fktipoacc` (`idTipoAccertamento`),
  ADD KEY `accertamentoPadre` (`idAccertamentoPadre`);

ALTER TABLE `Attivita`
  ADD PRIMARY KEY (`idAttivita`),
  ADD KEY `R_13` (`idAccertamento`);

ALTER TABLE `AttivitàAccertamento`
  ADD PRIMARY KEY (`idAttivitàAccertamento`),
  ADD KEY `fkTipoAccFiglio` (`idTipoAccertamentoFiglio`);

ALTER TABLE `Documento`
  ADD PRIMARY KEY (`idDocumento`),
  ADD KEY `AccDoc` (`idAccertamento`);

ALTER TABLE `Log`
  ADD PRIMARY KEY (`idLog`),
  ADD KEY `R_11` (`idPersona`),
  ADD KEY `R_12` (`idAccertamento`);

ALTER TABLE `OperatoreNuovaAttività`
  ADD PRIMARY KEY (`idOperatoriNuovaAttività`),
  ADD UNIQUE KEY `unicosoggettoaccertamento` (`idTipoAccertamento`,`idSoggetto`),
  ADD KEY `fkSoggettoOperatorinuovo` (`idSoggetto`);

ALTER TABLE `RuoloSoggetto`
  ADD PRIMARY KEY (`idRuolo`),
  ADD UNIQUE KEY `nomeRuolo` (`nomeRuolo`);

ALTER TABLE `Soggetto`
  ADD PRIMARY KEY (`idSoggetto`),
  ADD UNIQUE KEY `nome` (`nome`);

ALTER TABLE `SoggettoAccertamento`
  ADD PRIMARY KEY (`idSoggettoAccertamento`),
  ADD UNIQUE KEY `XAK1PersonaAccertamento` (`idSoggetto`,`idAccertamento`,`ruolo`),
  ADD KEY `AccPersAcc` (`idAccertamento`),
  ADD KEY `idSoggetto` (`idSoggetto`),
  ADD KEY `Ruolo_soggacc` (`ruolo`);

ALTER TABLE `TipoAccertamento`
  ADD PRIMARY KEY (`idTipoAccertamento`),
  ADD UNIQUE KEY `tipo` (`nome`) USING HASH,
  ADD UNIQUE KEY `nomeTipoAccUnico` (`nome`) USING HASH;


ALTER TABLE `Accertamento`
  MODIFY `idAccertamento` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Attivita`
  MODIFY `idAttivita` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `AttivitàAccertamento`
  MODIFY `idAttivitàAccertamento` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Documento`
  MODIFY `idDocumento` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Log`
  MODIFY `idLog` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `OperatoreNuovaAttività`
  MODIFY `idOperatoriNuovaAttività` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Soggetto`
  MODIFY `idSoggetto` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `SoggettoAccertamento`
  MODIFY `idSoggettoAccertamento` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `TipoAccertamento`
  MODIFY `idTipoAccertamento` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `Accertamento`
  ADD CONSTRAINT `accertamentoPadre` FOREIGN KEY (`idAccertamentoPadre`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fktipoaccertamento` FOREIGN KEY (`idTipoAccertamento`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`);

ALTER TABLE `Attivita`
  ADD CONSTRAINT `Acc_att` FOREIGN KEY (`idAccertamento`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `AttivitàAccertamento`
  ADD CONSTRAINT `fkTipoAcc` FOREIGN KEY (`idTipoAccertamento`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkTipoAccFiglio` FOREIGN KEY (`idTipoAccertamentoFiglio`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Documento`
  ADD CONSTRAINT `Acc_doc` FOREIGN KEY (`idAccertamento`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `OperatoreNuovaAttività`
  ADD CONSTRAINT `fkSoggettoOperatorinuovo` FOREIGN KEY (`idSoggetto`) REFERENCES `Soggetto` (`idSoggetto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkTipoOperatoreNuovo` FOREIGN KEY (`idTipoAccertamento`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `SoggettoAccertamento`
  ADD CONSTRAINT `Acc_soggacc` FOREIGN KEY (`idAccertamento`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Ruolo_soggacc` FOREIGN KEY (`ruolo`) REFERENCES `RuoloSoggetto` (`idRuolo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Sogg_soggacc` FOREIGN KEY (`idSoggetto`) REFERENCES `Soggetto` (`idSoggetto`) ON UPDATE CASCADE;
COMMIT;

