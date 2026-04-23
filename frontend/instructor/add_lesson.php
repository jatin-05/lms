<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.html");
    exit();
}

$course_id = $_GET['course_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<header class="app-header">
  <div class="header-inner">
    <div class="app-brand">Add Lesson</div>
    <nav class="app-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="../../backend/logout.php">Logout</a>
    </nav>
  </div>
</header>
<div class="page-shell">
<div class="container">
<section class="form-card">
  <h2 class="page-title">Add Lesson</h2>
  <form action="../../backend/add_lesson.php" method="POST">
      <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
      <input type="text" name="title" placeholder="Lesson Title" required>
      <input type="text" name="video_url" placeholder="YouTube Video URL">
      <textarea name="content" placeholder="Lesson Content"></textarea>
      <button type="submit">Add Lesson</button>
  </form>
</section>
</div>
</div>
</body>
</html>