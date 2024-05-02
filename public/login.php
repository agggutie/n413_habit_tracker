<?php
// Database connection details
$host = 'localhost:8889';
$database = 'n413_habit_tracker';
$username = 'root';
$password = 'root';

// Establish the database connection
$connection = new mysqli($host, $username, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $submitted_username = $_POST['username_or_email'];
    $submitted_password = $_POST['password'];

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $submitted_username, $submitted_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the provided username/email exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($submitted_password, $user['Password'])) {
            // Password is correct, start a session and store the user ID
            session_start();
            $_SESSION['user_id'] = $user['UserID']; // Store the user ID in the session

            // Redirect to index.html or send a success response
            header("Location: index.html#display");
            exit();
        } else {
            $response = ['success' => false, 'message' => 'Invalid password'];
            echo json_encode($response);
        }
    } else {
        $response = ['success' => false, 'message' => 'User not found'];
        echo json_encode($response);
    }
}
?>
