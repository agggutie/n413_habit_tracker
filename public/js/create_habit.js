console.log("create_habit.js loaded");

$(document).ready(function() {
    $('#createHabitForm').submit(function(event) {
        event.preventDefault();

        // Extract form data
        var formData = new FormData(this);

        // Debug: Log form data before sending
        console.log("Form data:", formData);

        // Send AJAX request to create_habit.php
        fetch('create_habit.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Habit creation successful, redirect to the display page and display the user's habits
                    $.ajax({
                        url: 'get_habits.php',
                        type: 'GET',
                        success: function(habitsData) {
                            if (habitsData.success) {
                                // Redirect to the display page and display the user's habits
                                window.location.href = '#display';
                                var habitsList = $('#habitsList');
                                habitsData.habits.forEach(function(habit) {
                                    var habitHtml = `
                                        <div class="habit">
                                            <h3>${habit.HabitName}</h3>
                                            <p>Frequency: ${habit.Frequency}</p>
                                            <p>Duration: ${habit.Duration}</p>
                                            <p>Actions: ${habit.Actions}</p>
                                        </div>
                                    `;
                                    habitsList.append(habitHtml);
                                });
                            } else {
                                // Handle error
                                alert(habitsData.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX errors
                            alert('An error occurred while fetching your habits. Please try again later.');
                        }
                    });
                } else {
                    // Habit creation failed, display error message
                    alert(data.message);
                }
            })
            .catch(error => {
                // Handle AJAX errors
                console.error('An error occurred while processing your request:', error);
                alert('An error occurred while processing your request. Please try again later.');
            });
    });
});