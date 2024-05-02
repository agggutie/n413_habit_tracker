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

// Retrieve the habit_id, date, and completion_status from the POST data
$habit_id = $_POST['habit_id'];
$date = $_POST['date'];
$completion_status = $_POST['completion_status'] == 1 ? true : false;

// Prepare the SQL statement to insert the new progress entry
$sql = "INSERT INTO HabitProgress (HabitID, UserID, Date, CompletionStatus) VALUES (?, ?, ?, ?)";
$stmt = $connection->prepare($sql);
$completion_status_value = $completion_status ? 1 : 0;
$stmt->bind_param("iiss", $habit_id, $user_id, $date, $completion_status_value);
$result = $stmt->execute();

if ($result) {
    $progress_id = $stmt->insert_id;

    // Prepare a new SQL statement to fetch the details of the newly added progress entry
    $sql = "SELECT ProgressID, Date, CompletionStatus FROM HabitProgress WHERE ProgressID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $progress_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $progress_entry = $result->fetch_assoc();
        $response = ['success' => true, 'message' => 'Progress entry added successfully', 'progress_entry' => $progress_entry];
    } else {
        $response = ['success' => false, 'message' => 'Error retrieving the new progress entry'];
    }
} else {
    $response = ['success' => false, 'message' => 'Error adding progress entry: ' . $stmt->error];
}

$stmt->close();
$connection->close();

header('Content-Type: application/json');
echo json_encode($response);