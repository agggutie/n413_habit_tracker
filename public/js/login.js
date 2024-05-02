$(document).ready(function() {
    $('#loginForm').submit(function(event) {
        event.preventDefault();

        // Extract form data
        var formData = $(this).serialize();

        // Send AJAX request to login.php
        fetch('login.php', {
            method: 'POST',
            body: formData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Login successful, redirect to index.html
                    window.location.href = 'index.html';
                } else {
                    // Login failed, display error message
                    alert(data.message);
                }
            })
            .catch(error => {
                // Handle AJAX errors
                console.error('An error occurred while processing your request:', error);
            });
    });
});
