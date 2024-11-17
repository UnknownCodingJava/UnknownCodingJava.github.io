<?php
include "dbConn.php";

// SQL query to fetch task details
$sql = "SELECT name, priority, due_date, description, location, status FROM tasks";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $entries = array();

    // Fetch each row from the result and add to $entries array
    while ($row = $result->fetch_assoc()) {
        $entries[] = $row;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the connection
$conn->close();
?>

<div class="mx-4 mt-2 rounded" style="width:100%;">
    <table class="table table-striped rounded  table-hover mt-2" id="tableList">
        <thead class="bg-secondary bg-gradient">
            <tr>
                <th>Name</th>
                <th>Priority</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($entries)): ?>
                <?php foreach ($entries as $entry): ?>
                    <tr
                        onclick="openModal('<?php echo addslashes($entry['name']); ?>', '<?php echo addslashes($entry['priority']); ?>', '<?php echo addslashes(date('M d, Y', strtotime($entry['due_date']))); ?>', '<?php echo addslashes($entry['description']); ?>', '<?php echo addslashes($entry['status']); ?>')">
                        <td><?php echo htmlspecialchars($entry['name']); ?></td>
                        <td><?php echo htmlspecialchars($entry['priority']); ?></td>
                        <td><?php echo htmlspecialchars(date('M d, Y', strtotime($entry['due_date']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No tasks found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="taskModal" class="modal" style="display: none;">
    <div class="modal-content" id="mod_cont">
        <span class="close" style="font-size:40px; font-weight:bolder; cursor:pointer; width:10%;"  onclick="closelistModal()">×</span>
        <h2 class="text-center">Task Details</h2>
        <p><strong>Name:</strong> <span id="taskName"></span></p>
        <p><strong>Priority:</strong> <span id="taskPriority"></span></p>
        <p><strong>Due Date:</strong> <span id="taskDueDate"></span></p>
        <p><strong>Description:</strong> <span id="taskDescription"></span></p>
        <p class="status_change"><strong>Status:</strong></p>
        <p class="text-center"><span class="status_change text-center" id="taskStatus"></span></p>
    </div>
</div>

<script>
    // Open Modal Function
function openModal(name, priority, dueDate, location, status) {
    console.log("Status received: ", status);  // Debugging line to check status value
    
    if (status == "Pending") {
        document.getElementById("taskModal").style.backgroundColor = "rgba(255, 0, 0, 0.502)";
    } else if(status == "In Progress") {
        document.getElementById("taskModal").style.backgroundColor = "rgba(235, 174, 6, 0.648)";
        document.getElementById("taskModal").style.color = "black";
    }
    else if(status == "Completed"){
        document.getElementById("taskModal").style.backgroundColor = "rgba(0, 128, 0, 0.594)";
    }

        // Set the modal content based on the row data
        document.getElementById("taskName").innerText = name;
        document.getElementById("taskPriority").innerText = priority;
        document.getElementById("taskDueDate").innerText = dueDate;
        document.getElementById("taskDescription").innerText = location;
        document.getElementById("taskStatus").innerText = status;
        // Show the modal (change style to display:block)
        document.getElementById("taskModal").style.display = "block";
        
    }

function closelistModal() {
    var modal = document.getElementById("taskModal");
    modal.style.display = "none"; // Try hiding the modal
}


    // Close modal if clicked outside the modal content
    window.onclick = function (event) {
        var modal = document.getElementById("taskModal");
        if (event.target === modal) {
            closeModal();
        }
    }
</script>