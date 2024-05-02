<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php

// Database configuration
$host = 'localhost:8889'; // Change this if your database is hosted on a different server
$dbname = 'n413_habit_tracker'; // Your database name
$username = 'root'; // Your database username
$password = 'root'; // Your database password

// Attempt to connect to the database
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);

    // Query to retrieve data from the Users table
    $stmt = $pdo->query('SELECT * FROM Users');
    $users = $stmt->fetchAll();

    // Output retrieved data
    echo '<h1>Users</h1>';
    foreach ($users as $user) {
        echo 'UserID: ' . $user['UserID'] . ', Username: ' . $user['Username'] . ', Email: ' . $user['Email'] . '<br>';
    }

} catch (PDOException $e) {
    // Display error message if connection fails
    die('Database connection failed: ' . $e->getMessage());
}
?>
</body>
</html>
    


