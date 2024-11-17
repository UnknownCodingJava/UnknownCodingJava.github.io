<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Your other scripts (Spline, etc.) -->
    <script type="module" src="https://unpkg.com/@splinetool/viewer/build/spline-viewer.js"></script>
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="myStyle.css">
</head>

<body>
    <!-- Main content section -->
    <nav class="navbar navbar-expand-lg navbar-light bg-dark bg-gradient p-1">
    <div class="container-fluid d-flex justify-content-between">
        <!-- Left-aligned "To-do List" -->
        <p class="h5 text-white font-weight-bold p-1 mb-0">To-do List</p>
        
        <!-- Right-aligned "Notes" -->
        <p class="h5 text-white font-weight-bold p-1 mb-0 text-end" onClick="document.getElementById('noteBanner').scrollIntoView();" style="cursor:pointer;">Notes</p>
    </div>
</nav>