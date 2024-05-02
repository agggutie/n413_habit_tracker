<?php
// Database configuration
$host = 'localhost:8889'; // Change this if your database is hosted on a different server
$dbname = 'n413_habit_tracker'; // Your database name
$username = 'root'; // Your database username
$password = 'root'; // Your database password

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $usernameForm = $_POST['username'];
    $passwordForm = $_POST['password']; // Plaintext password
    $emailForm = $_POST['email'];

    try {
        // Connect to the database
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, $username, $password, $options);

        // Hash the password
        $hashedPassword = password_hash($passwordForm, PASSWORD_DEFAULT);

        // Prepare and execute SQL statement to insert user data into the database
        $stmt = $pdo->prepare("INSERT INTO Users (Username, Password, Email) VALUES (:username, :password, :email)");
        $stmt->execute(['username' => $usernameForm, 'password' => $hashedPassword, 'email' => $emailForm]);

        // Redirect to a success page or do something else upon successful registration
        header("Location: index.html");
        exit();
    } catch (PDOException $e) {
        // Display error message if registration fails
        echo 'Error registering user: ' . $e->getMessage();
    }
}
?>
