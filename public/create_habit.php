<?php
session_start();

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
    // Retrieve the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Get the submitted habit data
    $habit_name = $_POST['habit_name'];
    $frequency = $_POST['frequency'];
    $duration = $_POST['duration'];
    $actions = $_POST['actions'];

    // Prepare the SQL statement to insert the habit record
    $sql = "INSERT INTO Habits (UserID, HabitName, Frequency, Duration, Actions) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("issss", $user_id, $habit_name, $frequency, $duration, $actions);

    // Execute the SQL statement
    if ($stmt->execute()) {
        // Habit creation successful, retrieve the user's habits
        $stmt->close();

        // Prepare the SQL statement to get the user's habits
        $sql = "SELECT HabitID, HabitName, Frequency, Duration, Actions FROM Habits WHERE UserID = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Convert the result to an array of habits
        $habits = [];
        while ($row = $result->fetch_assoc()) {
            $habits[] = $row;
        }

        // Return the user's habits as the response
        header("Location: index.html#display");
        exit();
    } else {
        // Habit creation failed
        $response = ['success' => false, 'message' => 'Error creating habit: ' . $connection->error];
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$connection->close();
