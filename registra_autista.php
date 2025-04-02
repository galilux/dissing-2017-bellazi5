<?php
$servername = "localhost";
$user = "root";
$pw = ""; // La tua password del database, se presente
$dbname = "db_scuola"; // Assicurati che sia il nome corretto del tuo DB carpooling

$conn = new mysqli($servername, $user, $pw, $dbname);

if ($conn->connect_error) {
    die("Errore durante la connessione al DB: " . $conn->connect_error);
}

$message = ""; // Messaggio per l'utente
$error = ""; // Messaggio di errore

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupera tutti i campi necessari per AUTISTA
    $nome = $_POST["nome"] ?? '';
    $cognome = $_POST["cognome"] ?? '';
    $dataNascita = $_POST["data_nascita"] ?? ''; // Assumi formato YYYY-MM-DD dal form
    $numPatente = $_POST["num_patente"] ?? '';
    $scadenzaPatente = $_POST["scadenza_patente"] ?? ''; // Assumi formato YYYY-MM-DD
    $datiAuto = $_POST["dati_auto"] ?? '';
    $recapito = $_POST["recapito"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? ''; // Password in chiaro

    // Controllo semplice (puoi aggiungere controlli più specifici se vuoi)
    if (empty($nome) || empty($cognome) || empty($dataNascita) || empty($numPatente) || empty($scadenzaPatente) || empty($datiAuto) || empty($recapito) || empty($email) || empty($password)) {
        $error = "Errore: Tutti i campi sono obbligatori.";
    } else {
        // Controllo se email o patente esistono già (opzionale ma consigliato)
        $stmt_check = $conn->prepare("SELECT ID_Autista FROM AUTISTA WHERE Email = ? OR NumPatente = ?");
        $stmt_check->bind_param("ss", $email, $numPatente);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Errore: Email o Numero Patente già registrati.";
        } else {
            // Prepara l'inserimento (PasswordHash conterrà la password in chiaro!)
            $stmt = $conn->prepare("INSERT INTO AUTISTA (Nome, Cognome, DataNascita, NumPatente, ScadenzaPatente, DatiAuto, RecapitoTelefonico, Email, PasswordHash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            // 's' per stringa/date, 's' per password (in chiaro qui)
            $stmt->bind_param("sssssssss", $nome, $cognome, $dataNascita, $numPatente, $scadenzaPatente, $datiAuto, $recapito, $email, $password);

            if ($stmt->execute()) {
                $message = "Account autista creato con successo!";
            } else {
                $error = "Errore durante la creazione dell'account: " . $stmt->error;
            }
            $stmt->close();
        }
        $stmt_check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrazione Autista</title>
</head>
<body>
    <h2>Registrazione Autista</h2>

    <?php if (!empty($message)): ?>
        <p style="color:green;"><?php echo $message; ?> Puoi <a href="login.php">accedere ora</a>.</p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (empty($message)): // Mostra form solo se non c'è messaggio di successo ?>
    <form method="post" action="registra_autista.php">
        Nome: <input type="text" name="nome" required><br>
        Cognome: <input type="text" name="cognome" required><br>
        Data Nascita: <input type="date" name="data_nascita" required><br>
        Num. Patente: <input type="text" name="num_patente" required><br>
        Scadenza Patente: <input type="date" name="scadenza_patente" required><br>
        Dati Auto (Targa, Modello, Colore): <input type="text" name="dati_auto" required><br>
        Recapito Tel.: <input type="tel" name="recapito" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Registrati</button>
    </form>
     <p>Sei già registrato? <a href="login.php">Accedi</a></p>
     <p>Vuoi registrarti come passeggero? <a href="registra_passeggero.php">Clicca qui</a></p>
    <?php endif; ?>
</body>
</html>