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
Nota: La procedura crea 3 utenti nel database (root, accert e mariabackup). In un ambiente di produzione conviene le password dei 3 utenti siano diverse e forti. La password di root va impostata nel file compose.yaml. Le altre 2 nel file sql/accert.sql dove ci sono le istruzioni create user. La password dell'utente accert va riportata nel file db.inc.php, quella di mariabackup nello script backup.sh

Impostate la password dell'utente root del database (MARIADB_ROOT_PASSWORD: password). Questa è la password dell'utente root. Quella impostata nel punto precedente è la password dell'utente accert e possono essere diverse.
```
nano compose.yaml
```
Impostate la password dell'utente accert del database. Cercate la riga "CREATE USER 'accert'@'%' IDENTIFIED BY 'metterelapassword';" e cambiate la password mettendo la stessa che avete messo nel file db.inc.php al punto più sopra. Nello stesso file (accert.sql) modificate anche la password dell'utente mariabackup.
```
nano sql/accert.sql
```

creare il file db.inc.php partendo dal modello:
```
cp wwwroot/db.inc.modello.php wwwroot/db.inc.php 
```
Impostate la password dell'utente accert (variabile $dbpwd)
```
nano wwwroot/db.inc.php
```
Nello script backup.sh mettete la password dell'utente mariabackup.
```
nano backup.sh
```

Create i certificati
```
./generaCertificati.sh
```

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
