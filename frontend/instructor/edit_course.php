<?php
session_start();
include '../../backend/db.php';

if ($_SESSION['role'] != 'instructor') {
    die("Access denied");
}

$course_id = $_GET['id'];
$instructor_id = $_SESSION['user_id'];

// Get course (ONLY if owned by instructor)
$sql = "SELECT * FROM courses 
        WHERE id='$course_id' AND instructor_id='$instructor_id'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Course not found or access denied");
}

$course = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<header class="app-header">
  <div class="header-inner">
    <div class="app-brand">Edit Course</div>
    <nav class="app-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="../../backend/logout.php">Logout</a>
    </nav>
  </div>
</header>
<div class="page-shell">
<div class="container">
<section class="form-card">
  <h2 class="page-title">Edit Course</h2>
  <form action="../../backend/update_course.php" method="POST">
      <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
      <input type="text" name="title" value="<?php echo $course['title']; ?>">
      <textarea name="description"><?php echo $course['description']; ?></textarea>
      <button type="submit">Update Course</button>
  </form>
</section>
</div>
</div>
</body>
</html>