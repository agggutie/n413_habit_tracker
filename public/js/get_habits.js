$(document).ready(function() {
    updateHabits();
    setInterval(updateHabits, 5000);
});

function updateHabits() {
    $.ajax({
        url: 'get_habits.php',
        type: 'GET',
        success: function(data) {
            if (data.success) {
                // Clear the existing habits list
                $('#habitsList').empty();

                // Display the user's habits
                data.habits.forEach(function(habit) {
                    var habitHtml = `<div class="habit" data-habit-id="${habit.HabitID}">
                        <h3>${habit.HabitName}</h3>
                        <p>Frequency: ${habit.Frequency}</p>
                        <p>Duration: ${habit.Duration}</p>
                        <p>Actions: ${habit.Actions}</p>
                        <div class="progress-entries">
                            <h4>Progress:</h4>
                            <ul></ul>
                        </div>
                        <button class="delete-habit-btn" data-habit-id="${habit.HabitID}">Delete</button>
                    </div>`;
                    $('#habitsList').append(habitHtml);
                });

                // Display the progress entries for each habit
                data.progress_entries.forEach(function(progress_entry) {
                    var habitDiv = $(`div.habit[data-habit-id="${progress_entry.HabitID}"]`);
                    var progressEntryHtml = `
        <li>
            <span>${progress_entry.Date}</span>
            <input type="checkbox" data-habit-id="${progress_entry.HabitID}" data-date="${progress_entry.Date}" ${progress_entry.CompletionStatus ? 'checked' : ''} onchange="updateProgressEntry(${progress_entry.HabitID}, '${progress_entry.Date}', this.checked)">
        </li>
    `;
                    habitDiv.find('.progress-entries ul').append(progressEntryHtml);
                });

                // Attach click event handler for delete buttons
                $('.delete-habit-btn').click(function() {
                    var habitId = $(this).data('habit-id');
                    if (confirm('Are you sure you want to delete this habit?')) {
                        deleteHabit(habitId);
                    }
                });
            } else {
                // Handle error
                alert(data.message);
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors
            alert('An error occurred while fetching your habits. Please try again later.');
        }
    });
}
function deleteHabit(habitId) {
    $.ajax({
        url: 'delete_habit.php',
        type: 'POST',
        data: { habit_id: habitId },
        success: function(response) {
            if (response.success) {
                // Habit deleted successfully
                alert(response.message);
                // Refresh the page or update UI as needed
                updateHabits();
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