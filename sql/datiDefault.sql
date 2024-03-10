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

INSERT INTO `Soggetto` (`idSoggetto`, `nome`, `dataNascita`, `luogoNascita`, `residenza`, `tel`, `mail`, `documento`, `indirizzo`, `tipo`, `winUN`, `login`, `permessi`, `societa`, `pwdhash`, `note`) VALUES
(1, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'admin', 'HP', NULL, '$2y$10$AaQ.LJZJ.9Lt9sdbMsMOROu8mMQqChxUKTgbNnnFXhDANWYoxjTpu', NULL);
