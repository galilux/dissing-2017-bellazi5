[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/T6gmiR_L)
[![Open in Visual Studio Code](https://classroom.github.com/assets/open-in-vscode-2e0aaae1b6195c2367325f4f02e2d04e9abb55f0b24a779b69b11b9e10269abc.svg)](https://classroom.github.com/online_ide?assignment_repo_id=18186879&assignment_repo_type=AssignmentRepo)

# Carpooling - Sistema di Registrazione e Login

Questo progetto implementa un sistema di **registrazione** e **login** per un'applicazione di carpooling, dove gli utenti possono registrarsi e accedere con credenziali sicure.

## Descrizione

Il sistema permette agli utenti di registrarsi, effettuare il login e accedere a un'area protetta utilizzando un'architettura con **MySQL** per la gestione dei dati degli utenti. L'applicazione è costruita con **PHP** e interagisce con un database MySQL per memorizzare le informazioni degli utenti.

### Funzionalità principali:
- **Registrazione dell'utente**: L'utente fornisce nome, cognome, email, password (che viene criptata), tipo di utente e numero di telefono. Le informazioni vengono salvate nel database.
- **Login dell'utente**: L'utente fornisce la propria email e password per accedere al sistema. La password viene verificata tramite hashing per garantire la sicurezza.
  
## Requisiti

- **PHP 7.0+**
- **MySQL**
- Un server web come **XAMPP** o **WAMP** per eseguire il progetto in locale.
