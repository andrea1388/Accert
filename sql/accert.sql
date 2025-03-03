-- MariaDB dump 10.19  Distrib 10.8.2-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: accert
-- ------------------------------------------------------
-- Server version	10.8.2-MariaDB-1:10.8.2+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Accertamento`
--

CREATE DATABASE IF NOT EXISTS `accert` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;

USE accert;

DROP TABLE IF EXISTS `Accertamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Accertamento` (
  `idAccertamento` int(11) NOT NULL AUTO_INCREMENT,
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
  `idTipoAccertamento` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idAccertamento`),
  UNIQUE KEY `uniconumacc` (`anno`,`numero`,`idTipoAccertamento`),
  KEY `fktipoacc` (`idTipoAccertamento`),
  KEY `accertamentoPadre` (`idAccertamentoPadre`),
  CONSTRAINT `accertamentoPadre` FOREIGN KEY (`idAccertamentoPadre`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fktipoaccertamento` FOREIGN KEY (`idTipoAccertamento`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`),
  CONSTRAINT `annook` CHECK (`anno` > 1900)
) ENGINE=InnoDB AUTO_INCREMENT=2180 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Attivita`
--

DROP TABLE IF EXISTS `Attivita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Attivita` (
  `idAttivita` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `idAccertamento` int(11) NOT NULL,
  `data` datetime(3) DEFAULT NULL,
  `attivita` mediumtext COLLATE latin1_general_ci DEFAULT NULL,
  `dataScadenza` datetime DEFAULT NULL,
  PRIMARY KEY (`idAttivita`),
  KEY `R_13` (`idAccertamento`),
  CONSTRAINT `Acc_att` FOREIGN KEY (`idAccertamento`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=735 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AttivitàAccertamento`
--

DROP TABLE IF EXISTS `AttivitàAccertamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AttivitàAccertamento` (
  `idAttivitàAccertamento` int(11) NOT NULL AUTO_INCREMENT,
  `idTipoAccertamento` int(11) NOT NULL,
  `idTipoAccertamentoFiglio` int(11) NOT NULL,
  `descrizione` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idAttivitàAccertamento`),
  KEY `fkTipoAccFiglio` (`idTipoAccertamentoFiglio`),
  CONSTRAINT `fkTipoAcc` FOREIGN KEY (`idTipoAccertamento`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkTipoAccFiglio` FOREIGN KEY (`idTipoAccertamentoFiglio`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Documento`
--

DROP TABLE IF EXISTS `Documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Documento` (
  `idDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `idAccertamento` int(11) NOT NULL,
  `File` longblob NOT NULL,
  `filename` text COLLATE latin1_general_ci NOT NULL,
  `dataDocumento` datetime(3) DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `descrizione` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `conttype` tinytext COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`idDocumento`),
  KEY `AccDoc` (`idAccertamento`),
  CONSTRAINT `Acc_doc` FOREIGN KEY (`idAccertamento`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Log`
--

DROP TABLE IF EXISTS `Log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Log` (
  `idLog` int(11) NOT NULL AUTO_INCREMENT,
  `dataora` datetime NOT NULL DEFAULT current_timestamp(),
  `operazione` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `idPersona` int(11) DEFAULT NULL,
  `idAccertamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idLog`),
  KEY `R_11` (`idPersona`),
  KEY `R_12` (`idAccertamento`)
) ENGINE=InnoDB AUTO_INCREMENT=3484 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OperatoreNuovaAttività`
--

DROP TABLE IF EXISTS `OperatoreNuovaAttività`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OperatoreNuovaAttività` (
  `idOperatoriNuovaAttività` int(11) NOT NULL AUTO_INCREMENT,
  `idTipoAccertamento` int(11) NOT NULL,
  `idSoggetto` int(11) NOT NULL,
  `descrizione` tinytext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`idOperatoriNuovaAttività`),
  UNIQUE KEY `unicosoggettoaccertamento` (`idTipoAccertamento`,`idSoggetto`),
  KEY `fkSoggettoOperatorinuovo` (`idSoggetto`),
  CONSTRAINT `fkSoggettoOperatorinuovo` FOREIGN KEY (`idSoggetto`) REFERENCES `Soggetto` (`idSoggetto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkTipoOperatoreNuovo` FOREIGN KEY (`idTipoAccertamento`) REFERENCES `TipoAccertamento` (`idTipoAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RuoloSoggetto`
--

DROP TABLE IF EXISTS `RuoloSoggetto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RuoloSoggetto` (
  `idRuolo` smallint(6) NOT NULL,
  `nomeRuolo` varchar(60) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idRuolo`),
  UNIQUE KEY `nomeRuolo` (`nomeRuolo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Soggetto`
--

DROP TABLE IF EXISTS `Soggetto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Soggetto` (
  `idSoggetto` int(11) NOT NULL AUTO_INCREMENT,
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
  `note` longtext COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`idSoggetto`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=1142 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SoggettoAccertamento`
--

DROP TABLE IF EXISTS `SoggettoAccertamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SoggettoAccertamento` (
  `idSoggetto` int(11) NOT NULL,
  `idAccertamento` int(11) NOT NULL,
  `ruolo` smallint(6) NOT NULL,
  `idSoggettoAccertamento` int(11) NOT NULL AUTO_INCREMENT,
  `descRuolo` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`idSoggettoAccertamento`),
  UNIQUE KEY `XAK1PersonaAccertamento` (`idSoggetto`,`idAccertamento`,`ruolo`),
  KEY `AccPersAcc` (`idAccertamento`),
  KEY `idSoggetto` (`idSoggetto`),
  KEY `Ruolo_soggacc` (`ruolo`),
  CONSTRAINT `Acc_soggacc` FOREIGN KEY (`idAccertamento`) REFERENCES `Accertamento` (`idAccertamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Ruolo_soggacc` FOREIGN KEY (`ruolo`) REFERENCES `RuoloSoggetto` (`idRuolo`) ON UPDATE CASCADE,
  CONSTRAINT `Sogg_soggacc` FOREIGN KEY (`idSoggetto`) REFERENCES `Soggetto` (`idSoggetto`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4104 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TipoAccertamento`
--

DROP TABLE IF EXISTS `TipoAccertamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TipoAccertamento` (
  `idTipoAccertamento` int(11) NOT NULL AUTO_INCREMENT,
  `nome` tinytext COLLATE latin1_general_ci NOT NULL,
  `codice` tinytext COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idTipoAccertamento`),
  UNIQUE KEY `tipo` (`nome`) USING HASH,
  UNIQUE KEY `nomeTipoAccUnico` (`nome`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `listaAttività`
--

DROP TABLE IF EXISTS `listaAttività`;
/*!50001 DROP VIEW IF EXISTS `listaAttività`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `listaAttività` (
  `data` tinyint NOT NULL,
  `idAccertamento` tinyint NOT NULL,
  `tipo` tinyint NOT NULL,
  `numeroAccertamento` tinyint NOT NULL,
  `luogo` tinyint NOT NULL,
  `numero` tinyint NOT NULL,
  `anno` tinyint NOT NULL,
  `descrizione` tinyint NOT NULL,
  `soggetti` tinyint NOT NULL,
  `idAccertatori` tinyint NOT NULL,
  `idTipoAccertamento` tinyint NOT NULL,
  `descrizione_estesa` tinyint NOT NULL,
  `idAccertamentoPadre` tinyint NOT NULL,
  `descrizionePadre` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `listaAttività2`
--

DROP TABLE IF EXISTS `listaAttività2`;
/*!50001 DROP VIEW IF EXISTS `listaAttività2`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `listaAttività2` (
  `data` tinyint NOT NULL,
  `idAccertamento` tinyint NOT NULL,
  `tipo` tinyint NOT NULL,
  `numeroAccertamento` tinyint NOT NULL,
  `luogo` tinyint NOT NULL,
  `numero` tinyint NOT NULL,
  `anno` tinyint NOT NULL,
  `descrizione` tinyint NOT NULL,
  `responsabili` tinyint NOT NULL,
  `responsabiliPadre` tinyint NOT NULL,
  `idAccertatori` tinyint NOT NULL,
  `idTipoAccertamento` tinyint NOT NULL,
  `descrizione_estesa` tinyint NOT NULL,
  `idAccertamentoPadre` tinyint NOT NULL,
  `descrizionePadre` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'accert'
--
/*!50003 DROP FUNCTION IF EXISTS `accertatoriAttività` */;
ALTER DATABASE `accert` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` FUNCTION `accertatoriAttività`(IN ida INT) RETURNS text CHARSET utf8mb4
    READS SQL DATA
BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT('o',sa.idSoggetto,'o') into nomi from Soggetto s JOIN SoggettoAccertamento sa 
  on s.idSoggetto=sa.idSoggetto 
  JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento 
  where sa.ruolo =1 and sa.idAccertamento=ida;
  RETURN nomi;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `accert` CHARACTER SET latin1 COLLATE latin1_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `numeroNuovoAccertamento` */;
ALTER DATABASE `accert` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` FUNCTION `numeroNuovoAccertamento`(_anno INT, _tipo INT) RETURNS int(11)
    DETERMINISTIC
BEGIN
 DECLARE num INT;
 SELECT MAX(numero) INTO num FROM Accertamento where anno=_anno and idTipoAccertamento=_tipo;
 IF num IS NULL THEN
  SET num=0;
 END IF;
 RETURN num+1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `accert` CHARACTER SET latin1 COLLATE latin1_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `responsabiliAccertamento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = latin1_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` FUNCTION `responsabiliAccertamento`(IN ida INT) RETURNS text CHARSET latin1 COLLATE latin1_general_ci
    READS SQL DATA
BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT(s.nome) INTO nomi from Soggetto s JOIN SoggettoAccertamento sa on s.idSoggetto=sa.idSoggetto JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento where sa.ruolo =2 and sa.idAccertamento=ida;
  RETURN nomi;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `soggettiAccertamento` */;
ALTER DATABASE `accert` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` FUNCTION `soggettiAccertamento`(IN ida INT) RETURNS text CHARSET utf8mb4
    READS SQL DATA
BEGIN
  DECLARE nomi TEXT;
  select GROUP_CONCAT(s.nome) INTO nomi from Soggetto s JOIN SoggettoAccertamento sa on s.idSoggetto=sa.idSoggetto JOIN Accertamento a ON a.idAccertamento=sa.idAccertamento where sa.ruolo >1 and sa.idAccertamento=ida;
  RETURN nomi;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `accert` CHARACTER SET latin1 COLLATE latin1_general_ci ;
/*!50003 DROP PROCEDURE IF EXISTS `spCreaAccertamento` */;
ALTER DATABASE `accert` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `spCreaAccertamento`(
    IN idAccertamentoPadre INT,
    IN anno INT,
    IN targa CHAR(15),
    IN luogo VARCHAR(200),
    IN data DATETIME,
    IN descrizione VARCHAR(200),
    IN descrizione_estesa LONGTEXT,
    IN _idTipoAccertamento INT,
    IN idOperatore INT,
    IN ruolo INT,
    IN descRuolo VARCHAR(300),
    OUT _idAccertamento INT
)
    MODIFIES SQL DATA
BEGIN
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `accert` CHARACTER SET latin1 COLLATE latin1_general_ci ;
/*!50003 DROP PROCEDURE IF EXISTS `spImportaAttività` */;
ALTER DATABASE `accert` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `spImportaAttività`()
    MODIFIES SQL DATA
BEGIN
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `accert` CHARACTER SET latin1 COLLATE latin1_general_ci ;

--
-- Final view structure for view `listaAttività`
--

/*!50001 DROP TABLE IF EXISTS `listaAttività`*/;
/*!50001 DROP VIEW IF EXISTS `listaAttività`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = latin1_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`accert`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `listaAttività` AS select distinct `t2`.`data` AS `data`,`t2`.`idAccertamento` AS `idAccertamento`,`t4`.`nome` AS `tipo`,concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`,`t2`.`luogo` AS `luogo`,`t2`.`numero` AS `numero`,`t2`.`anno` AS `anno`,`t2`.`descrizione` AS `descrizione`,`soggettiAccertamento`(`t2`.`idAccertamento`) AS `soggetti`,`accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`,`t2`.`idTipoAccertamento` AS `idTipoAccertamento`,`t2`.`descrizione_estesa` AS `descrizione_estesa`,`t2`.`idAccertamentoPadre` AS `idAccertamentoPadre`,`t5`.`descrizione` AS `descrizionePadre` from ((((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) left join `Accertamento` `t5` on(`t5`.`idAccertamento` = `t2`.`idAccertamentoPadre`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `listaAttività2`
--

/*!50001 DROP TABLE IF EXISTS `listaAttività2`*/;
/*!50001 DROP VIEW IF EXISTS `listaAttività2`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = latin1_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `listaAttività2` AS select distinct `t2`.`data` AS `data`,`t2`.`idAccertamento` AS `idAccertamento`,`t4`.`nome` AS `tipo`,concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`,`t2`.`luogo` AS `luogo`,`t2`.`numero` AS `numero`,`t2`.`anno` AS `anno`,`t2`.`descrizione` AS `descrizione`,`responsabiliAccertamento`(`t2`.`idAccertamento`) AS `responsabili`,`responsabiliAccertamento`(`t2`.`idAccertamentoPadre`) AS `responsabiliPadre`,`accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`,`t2`.`idTipoAccertamento` AS `idTipoAccertamento`,`t2`.`descrizione_estesa` AS `descrizione_estesa`,`t2`.`idAccertamentoPadre` AS `idAccertamentoPadre`,`t5`.`descrizione` AS `descrizionePadre` from ((((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) left join `Accertamento` `t5` on(`t5`.`idAccertamento` = `t2`.`idAccertamentoPadre`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28  8:55:42


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



CREATE ALGORITHM=UNDEFINED DEFINER=`accert`@`%` SQL SECURITY DEFINER VIEW `Attività2`  AS SELECT DISTINCT `t2`.`data` AS `data`, `t2`.`idAccertamento` AS `idAccertamento`, `t4`.`nome` AS `tipo`, concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`, `t2`.`luogo` AS `luogo`, `t2`.`numero` AS `numero`, `t2`.`anno` AS `anno`, `t2`.`descrizione` AS `descrizione`, `responsabiliAccertamento`(`t2`.`idAccertamento`) AS `responsabili`, `accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`, `t2`.`idTipoAccertamento` AS `idTipoAccertamento`, `t2`.`descrizione_estesa` AS `descrizione_estesa`, `t2`.`idAccertamentoPadre` AS `idAccertamentoPadre` FROM (((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `listaAttività`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`accert`@`%` SQL SECURITY DEFINER VIEW `listaAttività`  AS SELECT DISTINCT `t2`.`data` AS `data`, `t2`.`idAccertamento` AS `idAccertamento`, `t4`.`nome` AS `tipo`, concat(`t2`.`numero`,'/',`t4`.`codice`,'/',`t2`.`anno`) AS `numeroAccertamento`, `t2`.`luogo` AS `luogo`, `t2`.`numero` AS `numero`, `t2`.`anno` AS `anno`, `t2`.`descrizione` AS `descrizione`, `soggettiAccertamento`(`t2`.`idAccertamento`) AS `soggetti`, `accertatoriAttività`(`t2`.`idAccertamento`) AS `idAccertatori`, `t2`.`idTipoAccertamento` AS `idTipoAccertamento`, `t2`.`descrizione_estesa` AS `descrizione_estesa`, `t2`.`idAccertamentoPadre` AS `idAccertamentoPadre`, `t5`.`descrizione` AS `descrizionePadre` FROM ((((`Accertamento` `t2` left join `SoggettoAccertamento` `t3` on(`t3`.`idAccertamento` = `t2`.`idAccertamento`)) left join `Soggetto` `t1` on(`t1`.`idSoggetto` = `t3`.`idSoggetto`)) left join `TipoAccertamento` `t4` on(`t2`.`idTipoAccertamento` = `t4`.`idTipoAccertamento`)) left join `Accertamento` `t5` on(`t5`.`idAccertamento` = `t2`.`idAccertamentoPadre`)) ;
