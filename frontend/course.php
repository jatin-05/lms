<?php
session_start();
include '../backend/db.php';

// Validate course ID
if (!isset($_GET['id'])) {
    die("Invalid request");
}

$course_id = $_GET['id'];

// Get course
$course_sql = "SELECT * FROM courses WHERE id='$course_id'";
$course_result = $conn->query($course_sql);

if (!$course_result || $course_result->num_rows == 0) {
    die("Course not found");
}

$course = $course_result->fetch_assoc();

// Default: allow access
$is_enrolled = true;

// Check enrollment only for students
if (isset($_SESSION['role']) && $_SESSION['role'] == 'student') {

    $user_id = $_SESSION['user_id'];

    $check = "SELECT * FROM enrollments 
              WHERE user_id='$user_id' AND course_id='$course_id'";

    $res = $conn->query($check);

    $is_enrolled = $res->num_rows > 0;
}

// Get lessons
$lesson_sql = "SELECT * FROM lessons 
               WHERE course_id='$course_id' 
               ORDER BY lesson_order ASC";

$lessons = $conn->query($lesson_sql);

// ✅ Get quiz (FIXED POSITION)
$quiz_sql = "SELECT * FROM quizzes WHERE course_id='$course_id'";
$quiz_result = $conn->query($quiz_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course['title']; ?></title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="page-shell">
<div class="container">

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
      <a href="index.php">Courses</a>
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="../backend/logout.php" class="btn btn-secondary">Logout</a>
      <?php } else { ?>
        <a href="login.html" class="btn">Login</a>
      <?php } ?>
    </nav>
  </div>
</header>

<section class="hero-panel">
  <div class="label-pill">Course</div>
  <h1><?php echo $course['title']; ?></h1>
  <p class="subheading"><?php echo $course['description']; ?></p>
  <?php if (!$is_enrolled && isset($_SESSION['role']) && $_SESSION['role'] == 'student') { ?>
    <div class="hero-actions">
      <form action="../backend/enroll.php" method="POST">
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
        <button class="btn" type="submit">Enroll Now</button>
      </form>
      <a class="btn btn-secondary" href="index.php">← Back to Courses</a>
    </div>
  <?php } else { ?>
    <div class="hero-actions">
      <a class="btn btn-secondary" href="index.php">← Back to Courses</a>
    </div>
  <?php } ?>
</section>

<div class="course-detail-grid">
  <div class="course-summary">
    <div class="card">
      <h3>Lessons</h3>
      <?php if ($is_enrolled) { ?>
        <?php if ($lessons->num_rows > 0) { ?>
          <ul class="lesson-list">
            <?php while($lesson = $lessons->fetch_assoc()) { ?>
              <li>
                <a href="lesson.php?id=<?php echo $lesson['id']; ?>">
                  <?php echo $lesson['lesson_order'] . '. ' . $lesson['title']; ?>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php } else { ?>
          <p>No lessons added yet.</p>
        <?php } ?>
      <?php } else { ?>
        <p class="locked">🔒 Please enroll to access lessons.</p>
      <?php } ?>
    </div>
  </div>

  <aside class="quiz-box">
    <h3>Quiz</h3>
    <?php if ($is_enrolled) { ?>
      <?php if ($quiz_result->num_rows > 0) { 
        $quiz = $quiz_result->fetch_assoc();
      ?>
        <p class="subheading">Prepare for your course with this quiz.</p>
        <a class="btn" href="quiz.php?id=<?php echo $quiz['id']; ?>">Attempt Quiz</a>
      <?php } else { ?>
        <p>No quiz available for this course yet.</p>
      <?php } ?>
    <?php } else { ?>
      <p class="locked">🔒 Enroll to access quiz.</p>
    <?php } ?>
  </aside>
</div>
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