# Progetto Car Pooling - Svolgimento Esame di Stato 2017



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
