<?php
session_start();

// Check if the habit ID is provided
if (isset($_POST['habit_id'])) {
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

    // Retrieve the habit ID from the POST data
    $habit_id = $_POST['habit_id'];

    // Prepare the SQL statement to delete the habit
    $sql = "DELETE FROM Habits WHERE HabitID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $habit_id);

    // Execute the SQL statement
    if ($stmt->execute()) {
        // Habit deletion successful
        $response = ['success' => true, 'message' => 'Habit deleted successfully'];
    } else {
        // Habit deletion failed
        $response = ['success' => false, 'message' => 'Error deleting habit'];
    }

    // Close the statement and connection
    $stmt->close();
    $connection->close();
} else {
    // If habit ID is not provided, return error response
    $response = ['success' => false, 'message' => 'Habit ID not provided'];
}

// Return the response
header('Content-Type: application/json');
echo json_encode($response);
?>
