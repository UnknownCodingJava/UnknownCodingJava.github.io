<?php 
include "dbConn.php";

//get all the notes
$sql = "SELECT title, content FROM notes";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$entries = array();
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $entries[] = $row;
    }
}

//add note to the database
if(isset($_POST['addNote'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO notes(title, content) VALUES(?,?)");
    $stmt->bind_param("ss", $title, $content);
    if($stmt->execute()){
        echo json_encode(["status" => "success", "message" => "note added"]);
    } else{
        echo json_encode(["status" => "error", "message" => "Error inserting task"]);
    }
}


?>



<div class="banner mt-4 text-center" id="noteBanner">
    <span><b>NOTES</b></span>
</div>

<div class="container border" id="stickyNotes">
    <div class="border col-3 row-4 mt-4" id="noteContainer">
        <div id="noteClose"><b>x</b></div>
        <div id="noteTitle" contenteditable="true">New Note</div>
        <div id="noteBody" contenteditable="true">rest of the note goes here</div>
        <div id="addNote" onclick="addNote();">+Add note</div>
    </div>

<?php if(!empty($entries)): ?>
    <?php foreach ($entries as $entry): ?>
    <div class="border col-3 row-4 mt-4" id="noteContainer">
        <div id="noteClose"><b>x</b></div>
        <div id="noteTitle" contenteditable="true">
            <?php echo htmlspecialchars($entry['title']); ?>
        </div>
        <div id="noteBody" contenteditable="true">
            <?php echo htmlspecialchars($entry['content']); ?>
        </div>
    </div>
<?php endforeach; ?>
<?php else: ?>
    <div class="border col-3 row-4 mt-4" id="noteContainer">
        <div id="noteClose"><b>x</b></div>
        <div id="noteTitle" contenteditable="true">no notes</div>
        <div id="noteBody" contenteditable="true">Add new notes</div>
    </div>
    <?php endif; ?>
</div>

<script>
    function addNote(){
        var title = document.getElementById('noteTitle').innerText;
        var content = document.getElementById('noteBody').innerText;
        $.ajax({
            url:"notes.php",
            method: "POST",
            data:{
                addNote:1,
                title: title,
                content: content
            },
            success: function(response){
                alert('Note added successfully');
            },
            error: function(xhr, status, error){
                alert('there was an error: ' + error)
            }
        });
    }
</script>