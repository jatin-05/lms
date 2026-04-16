<?php
session_start();
include '../../backend/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.html");
    exit();
}

$instructor_id = $_SESSION['user_id'];

$sql = "SELECT * FROM courses WHERE instructor_id='$instructor_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<header class="app-header">
  <div class="header-inner">
    <div class="app-brand">LMS Portal</div>
    <div class="search-container">
      <input type="text" placeholder="Search courses..." class="search-input">
      <button class="search-btn">🔍</button>
    </div>
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <nav class="app-nav" id="mobileNav">
      <span class="welcome-text">Welcome, <?php echo $_SESSION['name']; ?></span>
      <a href="dashboard.php">My Courses</a>
      <a href="../../backend/logout.php" class="btn btn-secondary">Logout</a>
    </nav>
  </div>
</header>
<div class="page-shell">
<div class="container">

<a class="btn btn-success" href="create_course.php">➕ Create New Course</a>

<h3>My Courses</h3>

<div class="course-container">

<?php
while($row = $result->fetch_assoc()) {

    $course_id = $row['id'];

    $lesson_sql = "SELECT * FROM lessons WHERE course_id='$course_id' ORDER BY lesson_order ASC";
    $lesson_result = $conn->query($lesson_sql);

    $quiz_sql = "SELECT * FROM quizzes WHERE course_id='$course_id'";
    $quiz_result = $conn->query($quiz_sql);
?>

<div class="course-card">

    <h4><?php echo $row['title']; ?></h4>

    <!-- Buttons -->
    <a class="btn" href="../course.php?id=<?php echo $course_id; ?>">View</a>
    <a class="btn" href="edit_course.php?id=<?php echo $course_id; ?>">Edit</a>
    <a class="btn" href="add_lesson.php?course_id=<?php echo $course_id; ?>">Add Lesson</a>
    <a class="btn" href="create_quiz.php?course_id=<?php echo $course_id; ?>">Quiz</a>
    <a class="btn btn-danger"
    href="../../backend/delete_course.php?id=<?php echo $course_id; ?>"
    onclick="return confirm('Delete this course? This will remove everything!')">
    Delete Course
    </a>

    <!-- Quiz status -->
    <div class="quiz-status">
        <?php if ($quiz_result->num_rows > 0) { 
            $quiz = $quiz_result->fetch_assoc();
        ?>
            <span class="status-message success">✔ Quiz Created</span><br>
            <a class="btn" href="../quiz.php?id=<?php echo $quiz['id']; ?>">View Quiz</a>
        <?php } else { ?>
            <span class="status-message error">No quiz</span>
        <?php } ?>
    </div>

    <!-- Lessons -->
    <div class="lesson-list">
        <strong>Lessons:</strong><br>

        <?php while($lesson = $lesson_result->fetch_assoc()) { ?>
            <div class="lesson-item">
                <?php echo $lesson['lesson_order'] . ". " . $lesson['title']; ?>

                <a class="btn" href="edit_lesson.php?id=<?php echo $lesson['id']; ?>">Edit</a>
                <a class="btn btn-danger"
                   href="../../backend/delete_lesson.php?id=<?php echo $lesson['id']; ?>"
                   onclick="return confirm('Delete this lesson?')">
                   X
                </a>
            </div>
        <?php } ?>
    </div>

</div>

<?php } ?>

</div>
</div>

<script>
function toggleMobileMenu() {
  const nav = document.getElementById('mobileNav');
  nav.classList.toggle('active');
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
  const nav = document.getElementById('mobileNav');
  const toggle = document.querySelector('.mobile-menu-toggle');
  
  if (!nav.contains(event.target) && !toggle.contains(event.target)) {
    nav.classList.remove('active');
  }
});
</script>
</body>
</html>