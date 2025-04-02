# Progetto Car Pooling - Svolgimento Esame di Stato 2017
## Indice

*   [1. Analisi della Traccia](#1-analisi-della-traccia)
*   [2. Schema Concettuale (Diagramma E/R)](#2-schema-concettuale-diagramma-er)
*   [3. Espressione delle Cardinalità](#3-espressione-delle-cardinalità)
*   [4. Modello Logico (Schema Relazionale)](#4-modello-logico-schema-relazionale)
*   [5. Descrizione Dettagliata degli Attributi](#5-descrizione-dettagliata-degli-attributi)
*   [Modello Fisico (SQL DDL) e Progetto Applicazione Web](#modello-fisico-sql-ddl-e-progetto-applicazione-web)
*   [Come importare e collegare il Database](#come-importare-e-collegare-il-database)
*   [Setup del Database](#setup-del-database)
    *   [Metodo 1: Installazione di XAMPP (Consigliato)](#metodo-1-installazione-di-xampp-consigliato)
    *   [Metodo 2: Esecuzione Manuale delle Query](#metodo-2-esecuzione-manuale-delle-query-se-non-usi-xamppphpmyadmin)
*   [Connessione tra PHP e Database (con XAMPP)](#connessione-tra-php-e-database-con-xampp)
*   [Avvio dell'Applicazione](#avvio-dellapplicazione)


## 1. Analisi della Traccia

La traccia chiede di creare una piattaforma web per mettere in contatto persone che vogliono offrire un passaggio in macchina (gli **Autisti**) con persone che cercano un passaggio (i **Passeggeri**).

*   **Autisti:** Si registrano, inseriscono i loro dati (patente, auto, contatti), creano i **Viaggi** specificando partenza, destinazione, data/ora, costo, tempo stimato. Possono accettare o rifiutare le **Prenotazioni** dei passeggeri e (nella versione base) chiudere le prenotazioni. Danno e ricevono **Feedback**.
*   **Passeggeri:** Si registrano (dati personali, documento, contatti), cercano viaggi, vedono i dettagli e il feedback medio dell'autista, si prenotano, ricevono conferma/rifiuto via email. Danno e ricevono **Feedback**.
*   **Feedback:** Sia autisti che passeggeri possono lasciare un voto numerico e un commento sull'altra persona dopo il viaggio. Questi feedback sono visibili agli altri utenti.

L'obiettivo è creare il database per gestire queste informazioni e progettare una parte dell'applicazione web.

Per la **seconda parte**, ho scelto il **Quesito I**, che chiede di modificare il sistema per gestire automaticamente i posti disponibili nei viaggi, senza che l'autista debba chiudere manualmente le prenotazioni.

## 2. Schema Concettuale (Diagramma E/R)

Per rappresentare la realtà descritta, ho pensato a queste entità principali: `AUTISTA`, `PASSEGGERO`, `VIAGGIO`, `PRENOTAZIONE`, e `FEEDBACK`.

Ecco come le ho messe in relazione:
*![Diagramma E/R](PXL_20250331_173249342.jpg "Diagramma e/r")*



**Spiegazione delle Relazioni (Schema E/R):**

Le relazioni principali che ho identificato e rappresentato nello schema E/R sono:

*   **`AUTISTA` CREA `VIAGGIO` (1 a N):** Definisce che un autista può offrire più viaggi, ma ogni viaggio è proposto da un unico autista. La partecipazione dell'autista è obbligatoria (1,1) per il viaggio, mentre un autista può esistere senza aver creato viaggi (0,N).
*   **`PASSEGGERO` EFFETTUA `PRENOTAZIONE` (1 a N):** Indica che un passeggero può effettuare più prenotazioni nel tempo, ma ogni singola prenotazione è legata a un solo passeggero. La partecipazione del passeggero è obbligatoria (1,1) per la prenotazione, mentre un passeggero può essere registrato senza aver fatto prenotazioni (0,N).
*   **`VIAGGIO` RIGUARDA `PRENOTAZIONE` (1 a N):** Collega le prenotazioni ai viaggi specifici. Un viaggio può ricevere molte prenotazioni, ma una prenotazione si riferisce sempre a un unico viaggio. La partecipazione del viaggio è obbligatoria (1,1) per la prenotazione, mentre un viaggio può esistere senza prenotazioni (0,N).
*   **`PRENOTAZIONE` RICEVE `FEEDBACK` (1 a N, opzionale):** Modella la possibilità di lasciare un feedback dopo un viaggio. Una prenotazione completata può ricevere fino a due feedback (uno dall'autista, uno dal passeggero). Ogni feedback è associato univocamente a una prenotazione. La partecipazione della prenotazione è obbligatoria (1,1) per il feedback, mentre una prenotazione può non avere feedback associati (0,2).

*Nota sulla gestione dei posti:* Per soddisfare il Quesito I della seconda parte, ho incluso l'attributo `PostiMax` nell'entità `VIAGGIO`. Questo permette di gestire il numero massimo di passeggeri per viaggio direttamente e rende superfluo un eventuale attributo booleano per indicare se le prenotazioni sono chiuse.

## 3. Espressione delle Cardinalità

Le cardinalità minime e massime per le relazioni identificate sono:

1.  `AUTISTA` (0,N) --- CREA --- (1,1) `VIAGGIO`
2.  `PASSEGGERO` (0,N) --- EFFETTUA --- (1,1) `PRENOTAZIONE`
3.  `VIAGGIO` (0,N) --- RIGUARDA --- (1,1) `PRENOTAZIONE`
4.  `PRENOTAZIONE` (0,2) --- RICEVE --- (1,1) `FEEDBACK`

## 4. Modello Logico (Schema Relazionale)

La traduzione dello schema E/R nel modello logico relazionale porta alle seguenti tabelle, con indicate le chiavi primarie (PK) e le chiavi esterne (FK):

*   **AUTISTA** (`ID_Autista` PK, Nome, Cognome, DataNascita, NumPatente, ScadenzaPatente, DatiAuto, RecapitoTelefonico, Email UNIQUE, Foto, PasswordHash)
*   **PASSEGGERO** (`ID_Passeggero` PK, Nome, Cognome, DataNascita, DocIdentita, RecapitoTelefonico, Email UNIQUE, PasswordHash)
*   **VIAGGIO** (`ID_Viaggio` PK, `ID_Autista` FK -> AUTISTA, CittaPartenza, CittaDestinazione, DataOraPartenza, TempoStimato, ContributoRichiesto, PostiMax, Note)
*   **PRENOTAZIONE** (`ID_Prenotazione` PK, `ID_Viaggio` FK -> VIAGGIO, `ID_Passeggero` FK -> PASSEGGERO, DataOraPrenotazione, Stato)
*   **FEEDBACK** (`ID_Feedback` PK, `ID_Prenotazione` FK -> PRENOTAZIONE, DatoDa, VotoNumerico, GiudizioTestuale, DataOraFeedback)

## 5. Descrizione Dettagliata degli Attributi

Di seguito presento il dettaglio degli attributi per ciascuna tabella definita nel modello logico, specificando il tipo di dato scelto e i vincoli di integrità applicati.

**Tabella: AUTISTA**

| Nome Attributo      | Descrizione                        | Tipo        | Vincoli                                  |
| :------------------ | :--------------------------------- | :---------- | :--------------------------------------- |
| ID_Autista          | Identificativo univoco autista     | INT         | PK, AUTO_INCREMENT                       |
| Nome                | Nome dell'autista                  | VARCHAR(50) | NOT NULL                                 |
| Cognome             | Cognome dell'autista               | VARCHAR(50) | NOT NULL                                 |
| DataNascita         | Data di nascita                    | DATE        | NULL (Opzionale, in base ai requisiti)   |
| NumPatente          | Numero patente di guida            | VARCHAR(20) | NOT NULL, UNIQUE                         |
| ScadenzaPatente     | Data scadenza patente              | DATE        | NOT NULL                                 |
| DatiAuto            | Info sull'auto (targa, modello...) | VARCHAR(100)| NOT NULL                                 |
| RecapitoTelefonico  | Numero di telefono                 | VARCHAR(20) | NOT NULL                                 |
| Email               | Indirizzo email (per login/comm.)  | VARCHAR(50) | NOT NULL, UNIQUE                         |
| Foto                | Riferimento al file foto          | VARCHAR(255)| NULL (Facoltativo)                       |
| PasswordHash        | Hash della password per accesso    | VARCHAR(255)| NOT NULL                                 |

**Tabella: PASSEGGERO**

| Nome Attributo      | Descrizione                       | Tipo        | Vincoli                                  |
| :------------------ | :-------------------------------- | :---------- | :--------------------------------------- |
| ID_Passeggero       | Identificativo univoco passeggero | INT         | PK, AUTO_INCREMENT                       |
| Nome                | Nome del passeggero               | VARCHAR(50) | NOT NULL                                 |
| Cognome             | Cognome del passeggero            | VARCHAR(50) | NOT NULL                                 |
| DataNascita         | Data di nascita                   | DATE        | NULL (Opzionale, in base ai requisiti)   |
| DocIdentita         | Estremi documento identità        | VARCHAR(50) | NOT NULL                                 |
| RecapitoTelefonico  | Numero di telefono                | VARCHAR(20) | NOT NULL                                 |
| Email               | Indirizzo email (per login/comm.) | VARCHAR(50) | NOT NULL, UNIQUE                         |
| PasswordHash        | Hash della password per accesso   | VARCHAR(255)| NOT NULL                                 |

**Tabella: VIAGGIO**

| Nome Attributo      | Descrizione                       | Tipo           | Vincoli                                  |
| :------------------ | :-------------------------------- | :------------- | :--------------------------------------- |
| ID_Viaggio          | Identificativo univoco viaggio    | INT            | PK, AUTO_INCREMENT                       |
| ID_Autista          | Riferimento all'autista (FK)      | INT            | NOT NULL, FK -> AUTISTA(ID_Autista)      |
| CittaPartenza       | Città di partenza                 | VARCHAR(50)    | NOT NULL                                 |
| CittaDestinazione   | Città di destinazione             | VARCHAR(50)    | NOT NULL                                 |
| DataOraPartenza     | Data e ora di partenza previste   | DATETIME       | NOT NULL                                 |
| TempoStimato        | Durata stimata in minuti          | INT            | NULL (Facoltativo)                       |
| ContributoRichiesto | Costo per singolo passeggero      | DECIMAL(5,2)   | NOT NULL, CHECK (ContributoRichiesto >= 0) |
| PostiMax            | Num. max posti per passeggeri     | INT            | NOT NULL, CHECK (PostiMax > 0)           |
| Note                | Eventuali note aggiuntive         | TEXT           | NULL (Facoltativo)                       |

**Tabella: PRENOTAZIONE**

| Nome Attributo        | Descrizione                       | Tipo                                                     | Vincoli                                    |
| :-------------------- | :-------------------------------- | :------------------------------------------------------- | :----------------------------------------- |
| ID_Prenotazione       | Identificativo univoco prenotaz.  | INT                                                      | PK, AUTO_INCREMENT                         |
| ID_Viaggio            | Riferimento al viaggio (FK)       | INT                                                      | NOT NULL, FK -> VIAGGIO(ID_Viaggio)        |
| ID_Passeggero         | Riferimento al passeggero (FK)    | INT                                                      | NOT NULL, FK -> PASSEGGERO(ID_Passeggero)  |
| DataOraPrenotazione   | Timestamp della prenotazione      | DATETIME                                                 | NOT NULL, DEFAULT CURRENT_TIMESTAMP        |
| Stato                 | Stato attuale della prenotazione  | ENUM('In Attesa', 'Accettata', 'Rifiutata', 'Annullata') | NOT NULL, DEFAULT 'In Attesa'              |

**Tabella: FEEDBACK**

| Nome Attributo      | Descrizione                       | Tipo                       | Vincoli                                      |
| :------------------ | :-------------------------------- | :------------------------- | :------------------------------------------- |
| ID_Feedback         | Identificativo univoco feedback   | INT                        | PK, AUTO_INCREMENT                           |
| ID_Prenotazione     | Riferimento alla prenotazione (FK)| INT                        | NOT NULL, FK -> PRENOTAZIONE(ID_Prenotazione)|
| DatoDa              | Indica chi ha lasciato il feedback| ENUM('Autista', 'Passeggero') | NOT NULL                                     |
| VotoNumerico        | Voto numerico (es. 1-5)           | INT                        | NOT NULL, CHECK (VotoNumerico BETWEEN 1 AND 5) |
| GiudizioTestuale    | Commento testuale                 | TEXT                       | NULL (Facoltativo)                           |
| DataOraFeedback     | Timestamp del feedback            | DATETIME                   | NOT NULL, DEFAULT CURRENT_TIMESTAMP          |







# Modello Fisico (SQL DDL) e Progetto Applicazione Web

-- Tabella AUTISTA
```sql
CREATE TABLE AUTISTA (
    ID_Autista INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Cognome VARCHAR(50) NOT NULL,
    DataNascita DATE,
    NumPatente VARCHAR(20) NOT NULL UNIQUE,
    ScadenzaPatente DATE NOT NULL,
    DatiAuto VARCHAR(100) NOT NULL COMMENT 'Es: Targa, Modello, Colore',
    RecapitoTelefonico VARCHAR(20) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    Foto VARCHAR(255) NULL COMMENT 'Path del file foto',
    PasswordHash VARCHAR(255) NOT NULL COMMENT 'Per login'
);


```
-- Tabella PASSEGGERO

```sql

CREATE TABLE PASSEGGERO (
    ID_Passeggero INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Cognome VARCHAR(50) NOT NULL,
    DataNascita DATE,
    DocIdentita VARCHAR(50) NOT NULL COMMENT 'Es: CI Num, Passaporto Num',
    RecapitoTelefonico VARCHAR(20) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL COMMENT 'Per login'
);
```

-- Tabella VIAGGIO
```sql
CREATE TABLE VIAGGIO (
    ID_Viaggio INT AUTO_INCREMENT PRIMARY KEY,
    ID_Autista INT NOT NULL,
    CittaPartenza VARCHAR(50) NOT NULL,
    CittaDestinazione VARCHAR(50) NOT NULL,
    DataOraPartenza DATETIME NOT NULL,
    TempoStimato INT NULL COMMENT 'In minuti',
    ContributoRichiesto DECIMAL(5,2) NOT NULL CHECK (ContributoRichiesto >= 0),
    PostiMax INT NOT NULL CHECK (PostiMax > 0) COMMENT 'Numero massimo passeggeri',
    Note TEXT NULL,
    FOREIGN KEY (ID_Autista) REFERENCES AUTISTA(ID_Autista) ON DELETE CASCADE ON UPDATE CASCADE
);
```
-- Tabella PRENOTAZIONE
```sql
CREATE TABLE PRENOTAZIONE (
    ID_Prenotazione INT AUTO_INCREMENT PRIMARY KEY,
    ID_Viaggio INT NOT NULL,
    ID_Passeggero INT NOT NULL,
    DataOraPrenotazione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stato ENUM('In Attesa', 'Accettata', 'Rifiutata', 'Annullata') NOT NULL DEFAULT 'In Attesa',
    FOREIGN KEY (ID_Viaggio) REFERENCES VIAGGIO(ID_Viaggio) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ID_Passeggero) REFERENCES PASSEGGERO(ID_Passeggero) ON DELETE CASCADE ON UPDATE CASCADE
);
```
-- Tabella FEEDBACK
```sql
CREATE TABLE FEEDBACK (
    ID_Feedback INT AUTO_INCREMENT PRIMARY KEY,
    ID_Prenotazione INT NOT NULL,
    DatoDa ENUM('Autista', 'Passeggero') NOT NULL COMMENT 'Indica chi ha scritto il feedback',
    VotoNumerico INT NOT NULL CHECK (VotoNumerico BETWEEN 1 AND 5),
    GiudizioTestuale TEXT NULL,
    DataOraFeedback DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Prenotazione) REFERENCES PRENOTAZIONE(ID_Prenotazione) ON DELETE CASCADE ON UPDATE CASCADE
);
```
-- Indici per le performance
```sql
CREATE INDEX idx_viaggio_partenza ON VIAGGIO(CittaPartenza);
CREATE INDEX idx_viaggio_destinazione ON VIAGGIO(CittaDestinazione);
CREATE INDEX idx_viaggio_data ON VIAGGIO(DataOraPartenza);
CREATE INDEX idx_prenotazione_viaggio ON PRENOTAZIONE(ID_Viaggio);
CREATE INDEX idx_prenotazione_passeggero ON PRENOTAZIONE(ID_Passeggero);
CREATE INDEX idx_feedback_prenotazione ON FEEDBACK(ID_Prenotazione);
```
-- NOTA: Ho aggiunto ON DELETE CASCADE alle chiavi esterne per gestire automaticamente la cancellazione dei record correlati.

--------------------------------------------------

-- Interrogazioni SQL (Quesiti 3a, 3b, 3c)

-- a) Elencare autisti, dati auto e costo per un viaggio (partenza, arrivo, data) con posti disponibili, ordinati per ora
```sql
SELECT
    A.Nome AS NomeAutista,
    A.Cognome AS CognomeAutista,
    A.RecapitoTelefonico,
    V.ID_Viaggio,
    V.DatiAuto,
    V.DataOraPartenza,
    V.ContributoRichiesto,
    (V.PostiMax - IFNULL(NumPrenotazioniAccettate.Conteggio, 0)) AS PostiDisponibili
FROM VIAGGIO AS V
JOIN AUTISTA AS A ON V.ID_Autista = A.ID_Autista
LEFT JOIN (
    SELECT ID_Viaggio, COUNT(*) AS Conteggio
    FROM PRENOTAZIONE
    WHERE Stato = 'Accettata'
    GROUP BY ID_Viaggio
) AS NumPrenotazioniAccettate ON V.ID_Viaggio = NumPrenotazioniAccettate.ID_Viaggio
WHERE
    V.CittaPartenza = 'NomeCittaPartenza'
    AND V.CittaDestinazione = 'NomeCittaDestinazione'
    AND DATE(V.DataOraPartenza) = 'YYYY-MM-DD'
    AND (V.PostiMax - IFNULL(NumPrenotazioniAccettate.Conteggio, 0)) > 0
    AND V.DataOraPartenza >= NOW()
ORDER BY
    V.DataOraPartenza ASC;
```
-- NOTA: Sostituire 'NomeCittaPartenza', 'NomeCittaDestinazione' e 'YYYY-MM-DD' con i valori reali.

-- b) Estrarre dati per email di promemoria per una prenotazione accettata
```sql
SELECT
    P.Nome AS NomePasseggero,
    P.Cognome AS CognomePasseggero,
    P.Email AS EmailPasseggero,
    V.CittaPartenza,
    V.CittaDestinazione,
    V.DataOraPartenza,
    A.Nome AS NomeAutista,
    A.Cognome AS CognomeAutista,
    A.RecapitoTelefonico AS TelAutista,
    A.DatiAuto
FROM PRENOTAZIONE AS PR
JOIN PASSEGGERO AS P ON PR.ID_Passeggero = P.ID_Passeggero
JOIN VIAGGIO AS V ON PR.ID_Viaggio = V.ID_Viaggio
JOIN AUTISTA AS A ON V.ID_Autista = A.ID_Autista
WHERE
    PR.ID_Prenotazione = 123
    AND PR.Stato = 'Accettata';
```
-- NOTA: Sostituire 123 con l'ID della prenotazione specifica.

-- c) Dato un viaggio, elencare i passeggeri prenotati (accettati) con voto medio ricevuto superiore a X
```sql
SELECT
    P.ID_Passeggero,
    P.Nome,
    P.Cognome,
    AVG(F.VotoNumerico) AS VotoMedioRicevuto
FROM PASSEGGERO AS P
JOIN PRENOTAZIONE AS PR ON P.ID_Passeggero = PR.ID_Passeggero
LEFT JOIN FEEDBACK AS F ON PR.ID_Prenotazione = F.ID_Prenotazione AND F.DatoDa = 'Autista'
WHERE
    PR.ID_Viaggio = 456
    AND PR.Stato = 'Accettata'
GROUP BY
    P.ID_Passeggero, P.Nome, P.Cognome
HAVING
    AVG(F.VotoNumerico) > 3.5 OR AVG(F.VotoNumerico) IS NULL
ORDER BY
    P.Cognome, P.Nome;
```


--------------------------------------------------

-- Progetto di Massima dell'Applicazione Web (Punto 4 + Quesito II.I)

-- Architettura:
-- Client: Browser web con HTML, CSS per interfaccia dinamica (validazione form, aggiornamenti in tempo reale).
-- Server: Linguaggio (PHP) che interagisce con un database MySQL, gestendo logica applicativa, sessioni utente e accesso ai dati.

-- Gestione Automatica Posti (Quesito II.I):
-- Il campo PostiMax nella tabella VIAGGIO viene usato per calcolare dinamicamente i posti disponibili:
--   PostiDisponibili = VIAGGIO.PostiMax - (Numero di PRENOTAZIONI con Stato='Accettata' per quel VIAGGIO)

-- Segmento Significativo: Ricerca e Visualizzazione Viaggi per il Passeggero

-- Pagina HTML/PHP (es. cerca_viaggio.php):
--   - Contiene un form HTML in cui il passeggero inserisce:
--       * Città di Partenza (input text)
--       * Città di Destinazione (input text)
--       * Data del Viaggio (input date)
--       * Pulsante "Cerca"

-- Logica Server (nel file cerca_viaggio.php o script associato):
--   1. Ricezione dei parametri dal form (città di partenza, destinazione, data).
--   2. Connessione al database MySQL con le credenziali.
--   3. Esecuzione di una query simile a quella del quesito 3a per selezionare i viaggi corrispondenti e calcolare i PostiDisponibili (mostrando solo viaggi con posti > 0).
--   4. Recupero dei risultati e chiusura della connessione.

-- Visualizzazione Risultati:
--   Sotto il form, la pagina visualizza una tabella HTML in cui per ogni viaggio vengono mostrati:
--       * Nome dell'autista (con possibile link al profilo/feedback)
--       * Data e ora di partenza
--       * Contributo Richiesto (€)
--       * Posti Disponibili (calcolati dinamicamente dalla query)
--       * Pulsante "Dettagli" o "Prenota" che passa l'ID_Viaggio ad una pagina o azione successiva.



## Come importare e collegare il Database

Per far funzionare questo progetto in locale, avrai bisogno di:

1.  **XAMPP:** Un ambiente di sviluppo locale che include Apache (web server), MariaDB/MySQL (database) e PHP.
    *   Puoi scaricarlo da [https://www.apachefriends.org/it/index.html](https://www.apachefriends.org/it/index.html)

## Setup del Database

Ci sono due modi principali per configurare il database necessario.

### Metodo 1: Installazione di XAMPP (Consigliato)

1.  **Installa XAMPP:** Scarica e installa XAMPP seguendo le istruzioni per il tuo sistema operativo.
2.  **Avvia XAMPP:** Apri il Pannello di Controllo di XAMPP (XAMPP Control Panel).
3.  **Avvia i Moduli:** Avvia i moduli **Apache** e **MySQL** cliccando sui rispettivi pulsanti "Start".
4.  **Crea il Database:**
    *   Apri il tuo browser web e vai a `http://localhost/phpmyadmin`.
    *   Clicca su "Nuovo" (o "New") nel menu a sinistra.
    *   Inserisci un nome per il database. **Importante:** Usa lo stesso nome specificato nei file di configurazione PHP del progetto (ad esempio, `db_carpooling` o `db_scuola` come negli esempi precedenti - **verifica quale nome è usato nei tuoi file PHP!**). Scegli `utf8mb4_general_ci` come codifica (collation) se richiesto.
    *   Clicca su "Crea" (o "Create").
5.  **Importa la Struttura:**
    *   Una volta creato il database, selezionalo dal menu a sinistra.
    *   Vai alla scheda "SQL" in alto.
    *   Copia **tutto** il blocco di codice SQL qui sotto.
    *   Incolla il codice nella grande casella di testo della scheda "SQL".
    *   Clicca sul pulsante "Esegui" (o "Go") in basso a destra.
    *   Se tutto va bene, vedrai un messaggio di successo e le tabelle appariranno nel menu a sinistra sotto il nome del tuo database.

### Metodo 2: Esecuzione Manuale delle Query (Se non usi XAMPP/phpMyAdmin)

Se hai un server MySQL/MariaDB già configurato e preferisci usare la riga di comando o un altro strumento:

1.  **Connettiti al tuo server database.**
2.  **Crea il database** (se non esiste già), assicurandoti che il nome corrisponda a quello usato nei file PHP:
    ```sql
    CREATE DATABASE IF NOT EXISTS nome_tuo_database; -- Sostituisci nome_tuo_database!
    USE nome_tuo_database; -- Seleziona il database appena creato
    ```
3.  **Esegui le seguenti query SQL** per creare tutte le tabelle e gli indici necessari:

```sql
-- Tabella AUTISTA
CREATE TABLE AUTISTA (
    ID_Autista INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Cognome VARCHAR(50) NOT NULL,
    DataNascita DATE,
    NumPatente VARCHAR(20) NOT NULL UNIQUE,
    ScadenzaPatente DATE NOT NULL,
    DatiAuto VARCHAR(100) NOT NULL COMMENT 'Es: Targa, Modello, Colore',
    RecapitoTelefonico VARCHAR(20) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    Foto VARCHAR(255) NULL COMMENT 'Path del file foto',
    PasswordHash VARCHAR(255) NOT NULL COMMENT 'Per login (ATTENZIONE: nel codice fornito salva la password in chiaro!)'
);

-- Tabella PASSEGGERO
CREATE TABLE PASSEGGERO (
    ID_Passeggero INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Cognome VARCHAR(50) NOT NULL,
    DataNascita DATE,
    DocIdentita VARCHAR(50) NOT NULL COMMENT 'Es: CI Num, Passaporto Num',
    RecapitoTelefonico VARCHAR(20) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL COMMENT 'Per login (ATTENZIONE: nel codice fornito salva la password in chiaro!)'
);

-- Tabella VIAGGIO
CREATE TABLE VIAGGIO (
    ID_Viaggio INT AUTO_INCREMENT PRIMARY KEY,
    ID_Autista INT NOT NULL,
    CittaPartenza VARCHAR(50) NOT NULL,
    CittaDestinazione VARCHAR(50) NOT NULL,
    DataOraPartenza DATETIME NOT NULL,
    TempoStimato INT NULL COMMENT 'In minuti',
    ContributoRichiesto DECIMAL(5,2) NOT NULL CHECK (ContributoRichiesto >= 0),
    PostiMax INT NOT NULL CHECK (PostiMax > 0) COMMENT 'Numero massimo passeggeri',
    Note TEXT NULL,
    FOREIGN KEY (ID_Autista) REFERENCES AUTISTA(ID_Autista) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabella PRENOTAZIONE
CREATE TABLE PRENOTAZIONE (
    ID_Prenotazione INT AUTO_INCREMENT PRIMARY KEY,
    ID_Viaggio INT NOT NULL,
    ID_Passeggero INT NOT NULL,
    DataOraPrenotazione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stato ENUM('In Attesa', 'Accettata', 'Rifiutata', 'Annullata') NOT NULL DEFAULT 'In Attesa',
    FOREIGN KEY (ID_Viaggio) REFERENCES VIAGGIO(ID_Viaggio) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ID_Passeggero) REFERENCES PASSEGGERO(ID_Passeggero) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabella FEEDBACK
CREATE TABLE FEEDBACK (
    ID_Feedback INT AUTO_INCREMENT PRIMARY KEY,
    ID_Prenotazione INT NOT NULL,
    DatoDa ENUM('Autista', 'Passeggero') NOT NULL COMMENT 'Indica chi ha scritto il feedback',
    VotoNumerico INT NOT NULL CHECK (VotoNumerico BETWEEN 1 AND 5),
    GiudizioTestuale TEXT NULL,
    DataOraFeedback DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Prenotazione) REFERENCES PRENOTAZIONE(ID_Prenotazione) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Indici per le performance
CREATE INDEX idx_viaggio_partenza ON VIAGGIO(CittaPartenza);
CREATE INDEX idx_viaggio_destinazione ON VIAGGIO(CittaDestinazione);
CREATE INDEX idx_viaggio_data ON VIAGGIO(DataOraPartenza);
CREATE INDEX idx_prenotazione_viaggio ON PRENOTAZIONE(ID_Viaggio);
CREATE INDEX idx_prenotazione_passeggero ON PRENOTAZIONE(ID_Passeggero);
CREATE INDEX idx_feedback_prenotazione ON FEEDBACK(ID_Prenotazione);

```
## Connessione tra PHP e Database (con XAMPP)

I file PHP di questo progetto (non inclusi qui, ma parte dell'implementazione web) si connetteranno al database usando delle credenziali specificate solitamente all'inizio degli script o in un file di configurazione separato (es: `config.php`).

Con una installazione standard di XAMPP, i parametri di connessione sono generalmente:

*   **Server/Host:** `localhost` (o `127.0.0.1`)
*   **Nome Utente:** `root`
*   **Password:** `` (vuota, nessuna password)
*   **Nome Database:** Il nome che hai scelto durante la creazione nel passaggio precedente (es: `db_carpooling`, `db_scuola` - **deve corrispondere!**).

Tuttavia nel seguente progetto sono state scelte le seguente credenziali:
*   **Server/Host:** `localhost`
*   **Nome Utente:** `torsello`
*   **Password:** `1234` 
*   **Nome Database:`202425_5ib_gesualdo_carpoolingdb`

**Verifica i file PHP:** Quando importerai l'applicazione web, assicurati che le variabili usate per la connessione (`$servername`, `$user`, `$pw`, `$dbname` o nomi simili) nei tuoi script PHP corrispondano a questi valori predefiniti di XAMPP e al nome del database che hai creato.

## Avvio dell'Applicazione

Questa sezione descrive come avviare l'applicazione web una volta che avrai sviluppato il codice PHP e l'interfaccia:

1.  Assicurati che Apache e MySQL siano in esecuzione nel Pannello di Controllo XAMPP.
2.  Copia l'intera cartella del tuo progetto PHP/HTML/CSS dentro la cartella `htdocs` di XAMPP (solitamente si trova in `C:\xampp\htdocs` su Windows o `/Applications/XAMPP/htdocs` su macOS).
3.  Apri il browser e vai a `http://localhost/nome_cartella_progetto/` (sostituisci `nome_cartella_progetto` con il nome effettivo della cartella che hai copiato in `htdocs`).
4.  Dovresti vedere la pagina iniziale dell'applicazione (la pagina di login o registrazione).
