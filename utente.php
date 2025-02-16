<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'carpooling');
if ($conn->connect_error) die('Connessione fallita: ' . $conn->connect_error);
?>

<!-- REGISTRAZIONE UTENTE -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];
    $telefono = $_POST['telefono'];
    
    $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, password, tipo, telefono) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $nome, $cognome, $email, $password, $tipo, $telefono);
    $stmt->execute();
    echo "Registrazione completata!";
}
?>

<!-- LOGIN UTENTE -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, password FROM utenti WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hash);
    $stmt->fetch();
    
    if (password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        echo "Login riuscito!";
    } else {
        echo "Credenziali errate!";
    }
}
?>
