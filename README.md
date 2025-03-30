# Prova d'Esame Informatica 2017 - Car Pooling

L'azienda vuole creare un sito di **car pooling** dove:
- **Autisti** offrono passaggi inserendo dati personali, auto e dettagli del viaggio (partenza, arrivo, data, ora, costo).
- **Passeggeri** cercano viaggi disponibili e si prenotano.  
- Dopo il viaggio, sia autisti che passeggeri lasciano **feedback** (voto e commento) l'uno sull'altro.  

Scopo: creare un database per gestire utenti, viaggi, prenotazioni e feedback.

---

## Diagramma E/R
![Diagramma E/R](diagramma.png)

Entità principali:
- **Utente** (superclasse, con attributi comuni: nome, email, telefono).
- **Autista** (sottoclasse, con patente, foto, dati auto).
- **Passeggero** (sottoclasse, con documento identità).
- **Viaggio** (città partenza/arrivo, data, ora, posti, costo).
- **Prenotazione** (collega passeggero a viaggio, con stato "accettato/rifiutato").
- **Feedback** (voto, commento, collegato a viaggio e utente).

---

## Cardinalità
- **Utente** → **Viaggio**: 1:N (un utente può creare molti viaggi se è autista).
- **Viaggio** → **Prenotazione**: 1:N (un viaggio può avere molte prenotazioni).
- **Passeggero** → **Prenotazione**: 1:N (un passeggero può prenotare molti viaggi).
- **Feedback**: N:M tra utenti (ogni feedback è legato a un viaggio specifico).

---

## Modello Logico (Tabelle)
1. **Utente** (`id_utente`, nome, cognome, email, telefono, tipo [autista/passeggero]).
2. **Autista** (`id_autista`, `id_utente`, patente, scadenza_patente, modello_auto, targa).
3. **Passeggero** (`id_passeggero`, `id_utente`, documento_identità).
4. **Viaggio** (`id_viaggio`, `id_autista`, città_partenza, città_arrivo, data, ora, posti_disponibili, costo).
5. **Prenotazione** (`id_prenotazione`, `id_viaggio`, `id_passeggero`, stato [accettato/rifiutato/in attesa]).
6. **Feedback** (`id_feedback`, `id_viaggio`, `id_mittente`, `id_destinatario`, voto, commento).

---

## Modello Fisico (SQL)
```sql
CREATE TABLE Utente (
    id_utente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    tipo ENUM('autista', 'passeggero') NOT NULL
);

CREATE TABLE Autista (
    id_autista INT AUTO_INCREMENT PRIMARY KEY,
    id_utente INT UNIQUE NOT NULL,
    patente VARCHAR(20) NOT NULL,
    scadenza_patente DATE NOT NULL,
    modello_auto VARCHAR(50),
    targa VARCHAR(10),
    FOREIGN KEY (id_utente) REFERENCES Utente(id_utente) 
);

CREATE TABLE Passeggero (
    id_passeggero INT AUTO_INCREMENT PRIMARY KEY,
    id_utente INT UNIQUE NOT NULL,
    documento_identita VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_utente) REFERENCES Utente(id_utente) 
);

CREATE TABLE Viaggio (
    id_viaggio INT AUTO_INCREMENT PRIMARY KEY,
    id_autista INT NOT NULL,
    citta_partenza VARCHAR(50) NOT NULL,
    citta_arrivo VARCHAR(50) NOT NULL,
    data DATE NOT NULL,
    ora TIME NOT NULL,
    posti_disponibili INT NOT NULL,
    costo DECIMAL NOT NULL,
    FOREIGN KEY (id_autista) REFERENCES Autista(id_autista) 
);

CREATE TABLE Prenotazione (
    id_prenotazione INT AUTO_INCREMENT PRIMARY KEY,
    id_viaggio INT NOT NULL,
    id_passeggero INT NOT NULL,
    stato ENUM('accettato', 'rifiutato', 'in attesa') DEFAULT 'in attesa',
    FOREIGN KEY (id_viaggio) REFERENCES Viaggio(id_viaggio) ,
    FOREIGN KEY (id_passeggero) REFERENCES Passeggero(id_passeggero) 
);

CREATE TABLE Feedback (
    id_feedback INT AUTO_INCREMENT PRIMARY KEY,
    id_viaggio INT NOT NULL,
    id_mittente INT NOT NULL,
    id_destinatario INT NOT NULL,
    voto INT CHECK (voto BETWEEN 1 AND 5),
    commento TEXT,
    FOREIGN KEY (id_viaggio) REFERENCES Viaggio(id_viaggio) ,
    FOREIGN KEY (id_mittente) REFERENCES Utente(id_utente) ,
    FOREIGN KEY (id_destinatario) REFERENCES Utente(id_utente) 
);
