CREATE TABLE Autista (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50),
    cognome VARCHAR(50)
);

CREATE TABLE Passeggero (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50),
    cognome VARCHAR(50)
);

CREATE TABLE Viaggio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    autista_id INT,
    citta_partenza VARCHAR(50),
    citta_destinazione VARCHAR(50),
    data_partenza DATETIME,
    FOREIGN KEY (autista_id) REFERENCES Autista(id)
);

CREATE TABLE Prenotazione (
    id INT PRIMARY KEY AUTO_INCREMENT,
    viaggio_id INT,
    passeggero_id INT,
    FOREIGN KEY (viaggio_id) REFERENCES Viaggio(id),
    FOREIGN KEY (passeggero_id) REFERENCES Passeggero(id)
);

CREATE TABLE Feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prenotazione_id INT,
    voto INT CHECK (voto BETWEEN 1 AND 5),
    FOREIGN KEY (prenotazione_id) REFERENCES Prenotazione(id)
);
