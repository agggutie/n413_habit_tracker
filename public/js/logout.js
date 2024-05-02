$('#logoutLink').click(function(event) {
    event.preventDefault(); // Prevent the default link behavior

    // Send an AJAX request to the server to destroy the session
    $.ajax({
        type: 'POST',
        url: 'logout.php',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Logout successful, redirect or update the UI
                window.location.hash = '#login'; // Redirect to the login page
            } else {
                // Logout failed, display an error message
                alert('Error logging out. Please try again.');
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors
            console.error('An error occurred while logging out:', error);
        }
    });
});