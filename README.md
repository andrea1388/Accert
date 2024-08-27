# Accert
Applicazione web per la gestione collaborativa delle pratiche d'ufficio.
Le pratiche gestite sono personalizzabili. Gestione dell'anagrafica dei
soggetti afferenti alle pratiche. Creazione automatica del fascicolo
personale con le pratiche relative ad un soggetto. Gestione attività da
completare che vengono visualizzate agli utenti assegnatari. Utilizzabile
anche da dispositivi mobili. Ogni pratica può essere composta da altre
pratiche che a loro volta possono essere composta da altre pratiche
(albero). 

SPDX short identifier: EUPL-1.2


Creazione dei certificati (autofirmati)
    sudo openssl req -x509 -nodes -days 36500 -newkey rsa:2048 -keyout key.key -out cert.crt