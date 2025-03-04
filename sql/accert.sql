SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `accert` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;
USE `accert`;

DELIMITER $$
CREATE DEFINER=`root`@`%` PROCEDURE `spCreaAccertamento` (IN `idAccertamentoPadre` INT, IN `anno` INT, IN `targa` CHAR(15), IN `luogo` VARCHAR(200), IN `data` DATETIME, IN `descrizione` VARCHAR(200), IN `descrizione_estesa` LONGTEXT, IN `_idTipoAccertamento` INT, IN `idOperatore` INT, IN `ruolo` INT, IN `descRuolo` VARCHAR(300), OUT `_idAccertamento` INT)  MODIFIES SQL DATA BEGIN
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

CREATE DEFINER=`root`@`%` PROCEDURE `spImportaAttività` ()  MODIFIES SQL DATA BEGIN
    DECLARE rec1 ROW TYPE OF Attivita;
    DECLARE ihh INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
      ROLLBACK;
      RESIGNAL;
    END;
    SET @@SESSION.max_sp_recursion_depth=25; 

    START TRANSACTION;
    FOR rec1 IN ( select * from Attivita WHERE data IS NULL)
     DO CALL spCreaAccertamento(rec1.idAccertamento,2023,NULL,'Ufficio',NULL,rec1.descrizione,rec1.attivita,10,2,1,'Creatore',@ihh);
    END FOR;

    FOR rec1 IN ( select * from Attivita WHERE data IS NOT NULL)
     DO CALL spCreaAccertamento(rec1.idAccertamento,YEAR(rec1.data),NULL,'Ufficio',rec1.data,rec1.descrizione,rec1.attivita,10,2,1,'Creatore',@ihh);
    END FOR;
    COMMIT WORK;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `accertatoriAttività` (IN `ida` INT) RETURNS TEXT CHARSET utf8mb4 READS SQL DATA BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT('o',sa.idSoggetto,'o') into nomi from Soggetto s JOIN SoggettoAccertamento sa 
  on s.idSoggetto=sa.idSoggetto 
  JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento 
  where sa.ruolo =1 and sa.idAccertamento=ida;
  RETURN nomi;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `numeroNuovoAccertamento` (`_anno` INT, `_tipo` INT) RETURNS INT(11) DETERMINISTIC BEGIN
 DECLARE num INT;
 SELECT MAX(numero) INTO num FROM Accertamento where anno=_anno and idTipoAccertamento=_tipo;
 IF num IS NULL THEN
  SET num=0;
 END IF;
 RETURN num+1;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `responsabiliAccertamento` (IN `ida` INT) RETURNS TEXT CHARSET latin1 COLLATE latin1_general_ci READS SQL DATA BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT(s.nome) INTO nomi from Soggetto s JOIN SoggettoAccertamento sa on s.idSoggetto=sa.idSoggetto JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento where sa.ruolo =2 and sa.idAccertamento=ida;
  RETURN nomi;
END$$

CREATE DEFINER=`root`@`%` FUNCTION `soggettiAccertamento` (IN `ida` INT) RETURNS TEXT CHARSET utf8mb4 READS SQL DATA BEGIN
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
CREATE TABLE `Attività2` (
`data` datetime(3)
,`idAccertamento` int(11)
,`tipo` tinytext
,`numeroAccertamento` varchar(279)
,`luogo` varchar(200)
,`numero` int(11)
,`anno` int(11)
,`descrizione` varchar(200)
,`responsabili` text
,`idAccertatori` text
,`idTipoAccertamento` int(11)
,`descrizione_estesa` longtext
,`idAccertamentoPadre` int(11)
);

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
  `conttype` tinytext COLLATE latin1_general_ci DEFAULT NULL,
  `File` longblob DEFAULT NULL
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
CREATE TABLE `listaAttività3` (
`idAccertamento` int(11)
,`descrizione` varchar(200)
,`data` datetime(3)
,`responsabili` text
,`idAccertamentoPadre` int(11)
,`numeroAccertamentoPadre` varchar(279)
,`descrizionePadre` varchar(200)
,`responsabiliPadre` text
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
  `nomeRuolo` varchar(60) COLLATE latin1_general_ci NOT NULL
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
  `codice` tinytext COLLATE latin1_general_ci NOT NULL,
  `mostraInNuovaAttivita` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
DROP TABLE IF EXISTS `Attività2`;

CREATE OR REPLACE VIEW `Attività2`  AS SELECT DISTINCT `t2`.`data` AS `data`, `t2`.`idAccertamento` AS `idAccertamento`, `t4`.`nome` AS `tipo`, concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`, `t2`.`luogo` AS `luogo`, `t2`.`numero` AS `numero`, `t2`.`anno` AS `anno`, `t2`.`descrizione` AS `descrizione`, `responsabiliAccertamento`(`t2`.`idAccertamento`) AS `responsabili`, `accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`, `t2`.`idTipoAccertamento` AS `idTipoAccertamento`, `t2`.`descrizione_estesa` AS `descrizione_estesa`, `t2`.`idAccertamentoPadre` AS `idAccertamentoPadre` FROM (((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) ;
DROP TABLE IF EXISTS `listaAttività`;

CREATE OR REPLACE VIEW `listaAttività`  AS SELECT DISTINCT `t2`.`data` AS `data`, `t2`.`idAccertamento` AS `idAccertamento`, `t4`.`nome` AS `tipo`, concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`, `t2`.`luogo` AS `luogo`, `t2`.`numero` AS `numero`, `t2`.`anno` AS `anno`, `t2`.`descrizione` AS `descrizione`, `soggettiAccertamento`(`t2`.`idAccertamento`) AS `soggetti`, `accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`, `t2`.`idTipoAccertamento` AS `idTipoAccertamento`, `t2`.`descrizione_estesa` AS `descrizione_estesa`, `t2`.`idAccertamentoPadre` AS `idAccertamentoPadre`, `t5`.`descrizione` AS `descrizionePadre` FROM ((((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) left join `Accertamento` `t5` on(`t5`.`idAccertamento` = `t2`.`idAccertamentoPadre`)) ;
DROP TABLE IF EXISTS `listaAttività2`;

CREATE OR REPLACE VIEW `listaAttività2`  AS SELECT DISTINCT `t2`.`data` AS `data`, `t2`.`idAccertamento` AS `idAccertamento`, `t4`.`nome` AS `tipo`, concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`, `t2`.`luogo` AS `luogo`, `t2`.`numero` AS `numero`, `t2`.`anno` AS `anno`, `t2`.`descrizione` AS `descrizione`, `responsabiliAccertamento`(`t2`.`idAccertamento`) AS `responsabili`, `responsabiliAccertamento`(`t2`.`idAccertamentoPadre`) AS `responsabiliPadre`, `accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`, `t2`.`idTipoAccertamento` AS `idTipoAccertamento`, `t2`.`descrizione_estesa` AS `descrizione_estesa`, `t2`.`idAccertamentoPadre` AS `idAccertamentoPadre`, `t5`.`descrizione` AS `descrizionePadre` FROM ((((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) left join `Accertamento` `t5` on(`t5`.`idAccertamento` = `t2`.`idAccertamentoPadre`)) ;
DROP TABLE IF EXISTS `listaAttività3`;

CREATE OR REPLACE VIEW `listaAttività3`  AS SELECT `t1`.`idAccertamento` AS `idAccertamento`, `t1`.`descrizione` AS `descrizione`, `t1`.`data` AS `data`, `t1`.`responsabili` AS `responsabili`, `t1`.`idAccertamentoPadre` AS `idAccertamentoPadre`, `t2`.`numeroAccertamento` AS `numeroAccertamentoPadre`, `t2`.`descrizione` AS `descrizionePadre`, `t2`.`responsabili` AS `responsabiliPadre` FROM (`Attività2` `t1` left join `Attività2` `t2` on(`t1`.`idAccertamentoPadre` = `t2`.`idAccertamento`)) ;


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



INSERT INTO `TipoAccertamento` (`idTipoAccertamento`, `nome`, `codice`) VALUES
(1, 'Sopralluogo, Ispezione, Perquisizione', 'SPL'),
(2, 'Violazione amministrativa', 'AMM'),
(3, 'Reato', 'NDR'),
(4, 'Notifica', 'NOT'),
(5, 'Dichiarazione di ospitalità, cessione di fabbricato', 'OSP'),
(6, 'Autorizzazioni/Ordinanze', 'AUT'),
(7, 'Segnalazioni/Esposti/Denunce', 'ESP'),
(8, 'Opposizioni a sanzioni amministrative (cds,689)', 'RIC'),
(9, 'Ricezione documento', 'RD'),
(10, 'Assunzione informazioni (sit, 350,13)', 'SIT'),
(11, 'Trasmissione documento', 'TD'),
(12, 'Indagini in ufficio', 'UFF'),
(13, 'Altro', 'ALT'),
(14, 'Sequestro', 'SEQ');

INSERT INTO `RuoloSoggetto` (`idRuolo`, `nomeRuolo`) VALUES
(1, 'Accertatore'),
(3, 'Altro'),
(2, 'Responsabile');

-- default password Adm1n$
INSERT INTO `Soggetto` (`idSoggetto`, `nome`, `dataNascita`, `luogoNascita`, `residenza`, `tel`, `mail`, `documento`, `indirizzo`, `tipo`, `winUN`, `login`, `permessi`, `societa`, `pwdhash`, `note`) VALUES
(1, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'admin', 'HP', NULL, '$2y$10$P15Y5wo0qpVxtZ.bHqBofenvXOzcNWoQLQbpNC0zpWjK3WmAX/z6.', NULL);

CREATE USER 'accert'@'%' IDENTIFIED BY 'metterelapassword';
GRANT all privileges ON accert.* TO 'accert'@'%';
CREATE USER 'mariabackup'@'localhost' IDENTIFIED BY 'metterelapassword';
GRANT RELOAD, PROCESS, LOCK TABLES, BINLOG MONITOR ON *.* TO 'mariabackup'@'localhost';
FLUSH PRIVILEGES;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
