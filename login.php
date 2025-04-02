<?php
session_start();

$servername = "localhost";
$user = "torsello";
$pw = "1234"; // La tua password del database, se presente
$dbname = "202425_5ib_gesualdo_carPoolingDB"; // Assicurati che sia il nome corretto del tuo DB carpooling

$conn = new mysqli($servername, $user, $pw, $dbname);

if ($conn->connect_error) {
    // In un'app reale, non mostrare l'errore esatto all'utente
    die("Errore durante la connessione al DB: " . $conn->connect_error);
}

$login_error = ""; // Messaggio di errore

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Usiamo 'email' come identificativo, come nelle tue tabelle
    $email = $conn->real_escape_string($_POST["email"]); // Cambiato da username
    $password = $conn->real_escape_string($_POST["password"]);

    $user_found = false;

    // 1. Prova a cercare nella tabella AUTISTA
    $sql_autista = "SELECT ID_Autista, Nome, PasswordHash FROM AUTISTA WHERE Email = '$email'";
    $result_autista = $conn->query($sql_autista);

    if ($result_autista && $result_autista->num_rows > 0) {
        $row = $result_autista->fetch_assoc();
        // Confronto diretto della password (INSICURO!)
        if ($password === $row['PasswordHash']) {
            $_SESSION['user_id'] = $row['ID_Autista'];
            $_SESSION['user_email'] = $email; // Salva l'email
            $_SESSION['user_nome'] = $row['Nome']; // Salva il nome per salutarlo
            $_SESSION['user_type'] = 'autista'; // Identifica il tipo di utente
            $_SESSION['logged_in'] = true;

            $user_found = true;
            header("Location: area_autista.php"); // Reindirizza all'area autista
            exit();
        }
    }

    // 2. Se non trovato o password errata come autista, prova come PASSEGGERO
    if (!$user_found) {
        $sql_passeggero = "SELECT ID_Passeggero, Nome, PasswordHash FROM PASSEGGERO WHERE Email = '$email'";
        $result_passeggero = $conn->query($sql_passeggero);

        if ($result_passeggero && $result_passeggero->num_rows > 0) {
            $row = $result_passeggero->fetch_assoc();
            // Confronto diretto della password (INSICURO!)
            if ($password === $row['PasswordHash']) {
                $_SESSION['user_id'] = $row['ID_Passeggero'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_nome'] = $row['Nome'];
                $_SESSION['user_type'] = 'passeggero'; // Identifica il tipo di utente
                $_SESSION['logged_in'] = true;

                $user_found = true;
                header("Location: area_passeggero.php"); // Reindirizza all'area passeggero
                exit();
            }
        }
    }

    // Se non è stato trovato in nessuna tabella o la password era errata
    if (!$user_found) {
         $login_error = "Email o password non corretti.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($login_error)): ?>
        <p style="color:red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Non hai un account? <a href="registra_autista.php">Registrati come Autista</a> | <a href="registra_passeggero.php">Registrati come Passeggero</a></p>
</body>
</html>