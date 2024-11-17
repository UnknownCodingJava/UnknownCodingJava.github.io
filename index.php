<div class="main_container">
    <?php include "header.php"; ?>
    <div class="d-flex mt-4 text-center">
        <p class="h1 col-6 row-4"><b>Calendar</b></p>
        <p class="h1 col-6 row-4"><b>Task List:</b></p>
    </div>
    <div class="content mt-1 d-flex mb-4">
        <?php include "calendar.php"; ?>
        <?php include "list.php"; ?>
    </div>
    <div class="mt-4 mb-4 col-12" style="width:100%; height:10vh;"> </div>
    <div class="content mt-1 ">
        <?php include "notes.php"; ?>
    </div>

    <?php include "footer.php"; ?>
</div>