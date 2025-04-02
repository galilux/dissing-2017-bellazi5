<?php
session_start();

// Controllo semplice: l'utente è loggato ed è un passeggero?
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'passeggero') {
    // Se non è loggato o non è un passeggero, reindirizza al login
    header('Location: login.php');
    exit; // Termina lo script
}

// Recupera il nome dalla sessione per il saluto (usa htmlspecialchars per sicurezza)
$nome_utente = isset($_SESSION['user_nome']) ? htmlspecialchars($_SESSION['user_nome']) : 'Passeggero';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Passeggero</title>
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
    <p>Questa è la tua area riservata come passeggero.</p>

    <h2>Vantaggi di usare la nostra app come Passeggero:</h2>
    <ul>
        <li><strong>Risparmia denaro:</strong> Viaggia a costi contenuti, spesso inferiori ai mezzi pubblici o all'auto privata.</li>
        <li><strong>Comodità:</strong> Trova passaggi diretti per la tua destinazione, a volte più comodi dei trasporti tradizionali.</li>
        <li><strong>Flessibilità:</strong> Cerca viaggi adatti ai tuoi orari e alle tue esigenze specifiche.</li>
        <li><strong>Socializza:</strong> Incontra persone nuove e condividi esperienze durante il viaggio.</li>
        <li><strong>Sostenibilità:</strong> Scegli un modo di viaggiare più ecologico, contribuendo a ridurre il traffico.</li>
        <li><strong>Raggiungi più luoghi:</strong> Trova passaggi anche per destinazioni meno servite dai mezzi pubblici.</li>
    </ul>

     <p class="logout-link">
        <a href="logout.php">Effettua il Logout</a>
    </p>

    <!-- Qui potresti aggiungere link per cercare viaggi, visualizzare prenotazioni, ecc. -->

</body>
</html>