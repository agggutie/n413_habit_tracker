$(document).ready(function() {
    // Event listener for delete buttons
    $('.delete-habit-btn').click(function() {
        var habitId = $(this).data('habit-id');

        // Confirmation prompt
        if (confirm('Are you sure you want to delete this habit?')) {
            // Send AJAX request to delete habit
            $.ajax({
                url: 'delete_habit.php',
                type: 'POST',
                data: { habit_id: habitId },
                success: function(response) {
                    if (response.success) {
                        // Habit deleted successfully
                        alert(response.message);
                        // Refresh the page or update UI as needed
                        window.location.reload();
                    } else {
                        // Error deleting habit
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    console.error('Error deleting habit:', error);
                }
            });
        }
    });
});
