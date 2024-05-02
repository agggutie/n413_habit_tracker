<?php
session_start(); // Start the session

// Destroy the session and unset session variables
session_unset();
session_destroy();

// Return a success response
$response = ['success' => true];
echo json_encode($response);