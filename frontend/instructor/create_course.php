<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<header class="app-header">
  <div class="header-inner">
    <div class="app-brand">Create Course</div>
    <nav class="app-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="../../backend/logout.php">Logout</a>
    </nav>
  </div>
</header>
<div class="page-shell">
<div class="container">
<section class="form-card">
  <h2 class="page-title">Create Course</h2>
  <form action="../../backend/create_course.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
      <input type="text" name="title" id="title" placeholder="Course Title">
      <textarea name="description" id="description" placeholder="Course Description"></textarea>
      <input type="file" name="thumbnail">
      <button type="submit">Create Course</button>
  </form>
</section>

<script>
function validateForm() {
    let title = document.getElementById("title").value;
    let desc = document.getElementById("description").value;

    if (title === "" || desc === "") {
        alert("All fields are required!");
        return false;
    }
    return true;
}
</script>
</div>
</div>
</body>
</html>