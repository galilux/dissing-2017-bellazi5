<?php
$servername = "localhost";
$user = "torsello";
$pw = "1234"; // La tua password del database, se presente
$dbname = "202425_5ib_gesualdo_carPoolingDB"; // Assicurati che sia il nome corretto del tuo DB carpooling

$conn = new mysqli($servername, $user, $pw, $dbname);

if ($conn->connect_error) {
    die("Errore durante la connessione al DB: " . $conn->connect_error);
}

$message = ""; // Messaggio per l'utente
$error = ""; // Messaggio di errore

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupera tutti i campi necessari per PASSEGGERO
    $nome = $_POST["nome"] ?? '';
    $cognome = $_POST["cognome"] ?? '';
    $dataNascita = $_POST["data_nascita"] ?? ''; // Assumi formato YYYY-MM-DD
    $docIdentita = $_POST["doc_identita"] ?? '';
    $recapito = $_POST["recapito"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? ''; // Password in chiaro

    // Controllo semplice
    if (empty($nome) || empty($cognome) || empty($dataNascita) || empty($docIdentita) || empty($recapito) || empty($email) || empty($password)) {
        $error = "Errore: Tutti i campi sono obbligatori.";
    } else {
         // Controllo se email esiste già (opzionale ma consigliato)
        $stmt_check = $conn->prepare("SELECT ID_Passeggero FROM PASSEGGERO WHERE Email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Errore: Email già registrata.";
        } else {
            // Prepara l'inserimento (PasswordHash conterrà la password in chiaro!)
            $stmt = $conn->prepare("INSERT INTO PASSEGGERO (Nome, Cognome, DataNascita, DocIdentita, RecapitoTelefonico, Email, PasswordHash) VALUES (?, ?, ?, ?, ?, ?, ?)");
             // 's' per stringa/date, 's' per password (in chiaro qui)
            $stmt->bind_param("sssssss", $nome, $cognome, $dataNascita, $docIdentita, $recapito, $email, $password);

            if ($stmt->execute()) {
                $message = "Account passeggero creato con successo!";
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
    <title>Registrazione Passeggero</title>
</head>
<body>
    <h2>Registrazione Passeggero</h2>

    <?php if (!empty($message)): ?>
        <p style="color:green;"><?php echo $message; ?> Puoi <a href="login.php">accedere ora</a>.</p>
    <?php endif; ?>
     <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (empty($message)): // Mostra form solo se non c'è messaggio di successo ?>
    <form method="post" action="registra_passeggero.php">
        Nome: <input type="text" name="nome" required><br>
        Cognome: <input type="text" name="cognome" required><br>
        Data Nascita: <input type="date" name="data_nascita" required><br>
        Doc. Identità: <input type="text" name="doc_identita" required><br>
        Recapito Tel.: <input type="tel" name="recapito" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Registrati</button>
    </form>
     <p>Sei già registrato? <a href="login.php">Accedi</a></p>
     <p>Vuoi registrarti come autista? <a href="registra_autista.php">Clicca qui</a></p>
     <?php endif; ?>
</body>
</html>