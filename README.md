# Accert
Applicazione web per la gestione collaborativa delle pratiche d'ufficio.
Le pratiche gestite sono personalizzabili. Gestione dell'anagrafica dei
soggetti afferenti alle pratiche. Creazione automatica del fascicolo
personale con le pratiche relative ad un soggetto. Gestione attività da
completare che vengono visualizzate agli utenti assegnatari. Utilizzabile
anche da dispositivi mobili. Ogni pratica può essere composta da altre
pratiche che a loro volta possono essere composta da altre pratiche
(albero). 

Per altre informazioni vedi [https://www.acsoft.top/accert/](https://www.acsoft.top/accert/)

SPDX short identifier: EUPL-1.2

## Istruzioni per l'installazione
Questa procedura è pensata per un installazione su debian con Docker. Non è sttaa testata su windoze. Dopo aver clonato il repo con:
```
git clone https://github.com/andrea1388/Accert.git
```
cambiate dir:
```
cd Accert
```
### Modifica delle password di default

Negli script vi sono alcune password preimpostate. Se volete potete cambiarle. Eccole
1. password dell'utente db root nel file compose.yaml (MARIADB_ROOT_PASSWORD:)
2. password dell'utente accert nel file sql/accert.sql. Se cambiate questa password dovete anche aggiornare il file db.inc.php 
3. password dell'utente mariabackup nel file sql/accert.sql. Se cambiate questa password dovete anche aggiornare il file backup.sh dove viene usata

### Scaricate e costruite le immagini
```
docker compose build
```
### lanciate il servizio
```
docker compose up -d
```
All'inizio la cartella datadir, che contiene i file del db, è vuota. Solo in questo caso crea un db di partenza lanciando lo script sql che si trova in /sql

Per fare un backup del db (a caldo) lanciate lo script
```
./backup.sh
```
Creerà un backup (tar.gz) nella cartella /tar. Salvate il tar da qualche altra parte. Fate un backup almeno quotidiano.

Per fare un restore date il comando
```
./restore.sh
```

Questo script si aspetta di trovare un file chiamato bkup.tar.gz nella cartella backup. Quindi prima di lanciarlo fategli trovare il backup che volete ripristinare