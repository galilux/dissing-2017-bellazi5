<?php
session_start();

// Controllo semplice: l'utente è loggato ed è un autista?
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'autista') {
    // Se non è loggato o non è un autista, reindirizza al login
    header('Location: login.php');
    exit; // Termina lo script
}

// Recupera il nome dalla sessione per il saluto (usa htmlspecialchars per sicurezza)
$nome_utente = isset($_SESSION['user_nome']) ? htmlspecialchars($_SESSION['user_nome']) : 'Autista';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Autista</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        h1, h2 { color: #333; }
        ul { list-style: disc; margin-left: 20px; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .logout-link { margin-top: 30px; }
    </style>
</head>
<body>

    <h1>Benvenuto, <?php echo $nome_utente; ?>!</h1>
    <p>Questa è la tua area riservata come autista.</p>

    <h2>Vantaggi di usare la nostra app come Autista:</h2>
    <ul>
        <li><strong>Condividi le spese:</strong> Riduci i costi dei tuoi viaggi abituali ricevendo contributi dai passeggeri.</li>
        <li><strong>Viaggia in compagnia:</strong> Rendi i tuoi tragitti più piacevoli conoscendo nuove persone.</li>
        <li><strong>Ottimizza i posti liberi:</strong> Non viaggiare con l'auto vuota, sfrutta al meglio ogni sedile.</li>
        <li><strong>Riduci l'impatto ambientale:</strong> Contribuisci a diminuire il numero di auto in circolazione e l'inquinamento.</li>
        <li><strong>Flessibilità:</strong> Offri passaggi quando vuoi tu, secondo i tuoi orari e percorsi.</li>
    </ul>

    <p class="logout-link">
        <a href="logout.php">Effettua il Logout</a>
    </p>

    <!-- Qui potresti aggiungere link per creare nuovi viaggi, gestire quelli esistenti, ecc. -->

</body>
</html>