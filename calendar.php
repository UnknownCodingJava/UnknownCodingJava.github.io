<?php
include "dbConn.php";

// Check if month and year are set in the URL, if not, use the current date
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $_GET['month'];
    $year = $_GET['year'];
} else {
    $month = date('m');  // Get the current month (01, 02, ..., 12)
    $year = date('Y');   // Get the current year (e.g., 2024)
}

// Handle "Previous" and "Next" buttons to change the month and year
if (isset($_GET['action']) && $_GET['action'] === 'next') {
    $month++;
    if ($month > 12) {
        $month = 1;
        $year++;
    }
} elseif (isset($_GET['action']) && $_GET['action'] === 'prev') {
    $month--;
    if ($month < 1) {
        $month = 12;
        $year--;
    }
}

// Get the first day of the month and the number of days in the month
$firstDay = strtotime("$year-$month-01");
$daysInMonth = date('t', $firstDay);  // Total days in the month
$startingDay = date('w', $firstDay);  // Day of the week (0 = Sunday, 1 = Monday, etc.)

// Get the current day (for highlighting the current day in the calendar)
$currentDayOfMonth = date('d');  // Current day (e.g., 7th, 15th, etc.)
$currentMonth = date('m');       // Current month (01, 02, ..., 12)
$currentYear = date('Y');        // Current year (e.g., 2024)

// Fetch tasks for the current month and year
$sql = "SELECT due_date, priority FROM tasks WHERE MONTH(due_date) = ? AND YEAR(due_date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

// Store task priorities by day
$taskPriorities = [];
while ($row = $result->fetch_assoc()) {
    $dueDate = strtotime($row['due_date']); // Convert to timestamp for easy comparison
    $day = date('d', $dueDate); // Get the day of the month
    $taskPriorities[$day] = $row['priority']; // Store the priority for each day
}

// Check if the form is being submitted via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addTask'])) {
    // Retrieve POST data
    $task = $_POST['task'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $status = "pending";  // Default status

    // Prepare the SQL statement to insert the task into the database
    $stmt = $conn->prepare("INSERT INTO tasks(name, priority, due_date, description, location, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $task, $priority, $due_date, $description, $location, $status);

    if ($stmt->execute()) {
        // Send success response
        echo json_encode(["status" => "success", "message" => "Task added successfully"]);
    } else {
        // Send error response
        echo json_encode(["status" => "error", "message" => "Error inserting task"]);
    }
    $stmt->close();
}
?>

<div class="mx-4 mt-2 p-2" id="cal_frame">
    <div class="row">
        <div class="col-12 text-center">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-center">
                    <h1><?php echo date('F Y', $firstDay); ?></h1>
                </div>
                <div class="ml-auto">
                    <a href="?month=<?php echo date('m'); ?>&year=<?php echo date('Y'); ?>"
                        class="btn btn-secondary mb-3 mx-2">
                        Today's Date
                    </a>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped mt-4" id="cal_table">
        <thead>
            <tr>
                <th>Sun</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentDay = 1;

            // Print blank spaces for days before the start of the month
            for ($i = 0; $i < $startingDay; $i++) {
                echo '<td></td>';
            }

            // Print the days of the month
            while ($currentDay <= $daysInMonth) {
                if ($startingDay > 6) {
                    $startingDay = 0;
                    echo '</tr><tr>';
                }

                $taskDot = '';
                if (isset($taskPriorities[$currentDay])) {
                    $priority = $taskPriorities[$currentDay];

                    if ($priority == 'High') {
                        $taskDot = "<span class='task-dot red'></span>";
                    } elseif ($priority == 'Medium') {
                        $taskDot = "<span class='task-dot yellow'></span>";
                    } else {
                        $taskDot = "<span class='task-dot green'></span>";
                    }
                }

                $isToday = ($currentDay == $currentDayOfMonth && $month == $currentMonth && $year == $currentYear);
                $dayClass = $isToday ? 'bg-warning text-white' : ''; // Highlight today
            
                echo "<td class='date-cell $dayClass' data-day='$currentDay' data-month='$month' data-year='$year'>$currentDay $taskDot</td>";

                $currentDay++;
                $startingDay++;
            }

            // Fill in the empty cells for the last week
            while ($startingDay <= 6) {
                echo '<td></td>';
                $startingDay++;
            }
            ?>
        </tbody>
    </table>

    <div class="mt-3 mx-auto text-center">
        <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&action=prev"
            class="btn btn-primary">Previous</a>
        <a href="?month=<?php echo $month; ?>&year=<?php echo $year; ?>&action=next" class="btn btn-primary">Next</a>
    </div>
</div>

<!-- Modal for Task Entry -->
<div id="dateModal" class="modal" style="display:none;">
    <div>
        <h5 id="modalDateTitle">Date:</h5>
        <form id="noteForm">
            <input type="text" name="name" placeholder="Name of task" class="form-control mb-3">
            <select class="form-select mb-3" name="priority" id="priority">
                <option selected>Open this select menu</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
            <textarea id="noteText" name="description" placeholder="Enter description of task..."
                class="form-control mb-3"></textarea>
            <input type="text" name="location" placeholder="Location" class="form-control mb-3">
            <input type="hidden" name="due_date" id="due_date">
            <div class="d-flex justify-content-end mx-2">
                <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fa fa-send"></i> Add Task</button>
            </div>
            <div class="d-flex justify-content-end mx-2 my-2">
                <button type="button" onclick="closeModal();" class="btn btn-danger btn-sm"><i class="fa fa-times"></i>
                    Close</button>
            </div>
        </form>
    </div>
</div>

<script>
    // jQuery for DOM readiness
    $(document).ready(function () {
        // Open modal on date cell click
        $('.date-cell').on('click', function () {
            var day = $(this).data('day');
            var month = $(this).data('month');
            var year = $(this).data('year');
            var formattedDate = year + '-' + ('0' + month).slice(-2) + '-' + ('0' + day).slice(-2);

            // Update the modal with selected date
            $('#modalDateTitle').text(formattedDate);
            $('#due_date').val(formattedDate);

            $('#dateModal').fadeIn();  // Show modal
        });

        

        // Form submission via AJAX
        $('#noteForm').on('submit', function (e) {
            e.preventDefault();  // Prevent form submission
            var task = $('input[name="name"]').val();
            var priority = $('#priority').val();
            var due_date = $('#due_date').val();
            var description = $('#noteText').val();
            var location = $('input[name="location"]').val();

            if (task && priority && due_date && description && location) {
                $.ajax({
                    url: "calendar.php",
                    method: "POST",
                    data: {
                        addTask: 1,
                        task: task,
                        priority: priority,
                        due_date: due_date,
                        description: description,
                        location: location
                    },
                    success: function (response) {
                        alert('Task added successfully');
                        closeModal();  // Close the modal after success
                        // You can also update the calendar content here via AJAX if needed
                    },
                    error: function (xhr, status, error) {
                        alert('Error uploading data: ' + error);
                    }
                });
            } else {
                alert('Please fill in all fields');
            }
        });
    });

    // Close modal function
        function closeModal() {
            $('#dateModal').fadeOut();  // Hide modal
        }
</script>