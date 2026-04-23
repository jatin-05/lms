<?php
session_start();
include '../../backend/db.php';

if ($_SESSION['role'] != 'instructor') {
    die("Access denied");
}

$lesson_id = $_GET['id'];

// Get lesson + ensure ownership
$sql = "SELECT lessons.*, courses.instructor_id 
        FROM lessons
        JOIN courses ON lessons.course_id = courses.id
        WHERE lessons.id='$lesson_id'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Lesson not found");
}

$lesson = $result->fetch_assoc();

// Ownership check
if ($lesson['instructor_id'] != $_SESSION['user_id']) {
    die("Access denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lesson</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<header class="app-header">
  <div class="header-inner">
    <div class="app-brand">Edit Lesson</div>
    <nav class="app-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="../../backend/logout.php">Logout</a>
    </nav>
  </div>
</header>
<div class="page-shell">
<div class="container">
<section class="form-card">
  <h2 class="page-title">Edit Lesson</h2>
  <form action="../../backend/update_lesson.php" method="POST">
      <input type="hidden" name="lesson_id" value="<?php echo $lesson['id']; ?>">
      <input type="text" name="title" value="<?php echo $lesson['title']; ?>">
      <input type="text" name="video_url" value="<?php echo $lesson['video_url']; ?>">
      <textarea name="content"><?php echo $lesson['content']; ?></textarea>
      <input type="number" name="lesson_order" value="<?php echo $lesson['lesson_order']; ?>">
      <button type="submit">Update Lesson</button>
  </form>
</section>
</div>
</div>
</body>
</html>