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

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

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

// Prepare the SQL statement to get the user's habit progress entries
$sql = "SELECT HabitID, ProgressID, Date, CompletionStatus FROM HabitProgress WHERE UserID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Convert the result to an array of progress entries
$progress_entries = [];
while ($row = $result->fetch_assoc()) {
    $progress_entries[] = $row;
}

// Return the user's habits and progress entries as the response
$response = ['success' => true, 'habits' => $habits, 'progress_entries' => $progress_entries];

// Close the statement and connection
$stmt->close();
$connection->close();

// Return the response
header('Content-Type: application/json');
echo json_encode($response);