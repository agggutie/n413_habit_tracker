$(document).ready(function() {
    // Fetch habits from the server
    fetchHabits();

    // Event listener for the form submission
    $('#newProgressForm').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        console.log('Form submitted');
        // Get the form data
        var habitId = $('#habitId').val();
        var date = $('#progressDate').val();
        var completionStatus = $('#completionStatus').is(':checked');

        // Call the updateProgressEntry function with isNew = true
        updateProgressEntry(habitId, date, completionStatus, true);
    });
});

function fetchHabits() {
    $.ajax({
        url: 'get_habits.php',
        type: 'GET',
        success: function(data) {
            if (data.success) {
                var habitSelectOptions = '';
                data.habits.forEach(function(habit) {
                    habitSelectOptions += `<option value="${habit.HabitID}">${habit.HabitName}</option>`;
                });
                $('#habitId').html(habitSelectOptions);
            } else {
                // Handle error
                alert(data.message);
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors
            alert('An error occurred while fetching your habits. Please try again later.');
        }


    })};

function updateProgressEntry(habitId, date, completionStatus, isNew = false) {
    if (isNew) {
        // Create a new progress entry
        $.ajax({
            url: 'add_progress.php',
            type: 'POST',
            data: {
                habit_id: habitId,
                date: date,
                completion_status: completionStatus ? 1 : 0
            },
            success: function(data) {
                if (data.success) {
                    // New progress entry added successfully
                    if (data.progress_entry) {
                        // Find the corresponding habit div
                        var habitDiv = $(`div.habit[data-habit-id="${habitId}"]`);

                        // Create a new progress entry li
                        var newProgressEntry = $(`
                            <li>
                                <span>${data.progress_entry.Date}</span>
                                <input type="checkbox" data-habit-id="${habitId}" data-date="${data.progress_entry.Date}" ${data.progress_entry.CompletionStatus ? 'checked' : ''} onchange="updateProgressEntry(${habitId}, '${data.progress_entry.Date}', this.checked)">
                            </li>
                        `);

                        // Append the new progress entry li to the progress entries ul
                        habitDiv.find('.progress-entries ul').append(newProgressEntry);

                        // Reset the form
                        $('#newProgressForm')[0].reset();
                    }
                } else {
                    // Handle error
                    alert(data.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                alert('An error occurred while adding the progress entry. Please try again later.');
            }
        });
    } else {
        // Update an existing progress entry
    }
}