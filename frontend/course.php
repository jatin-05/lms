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
$lessonItems = [];
if ($lessons) {
    while ($lessonRow = $lessons->fetch_assoc()) {
        $lessonItems[] = $lessonRow;
    }
}
$lessonTotal = count($lessonItems);

// ✅ Get quiz (FIXED POSITION)
$quiz_sql = "SELECT * FROM quizzes WHERE course_id='$course_id'";
$quiz_result = $conn->query($quiz_sql);
$quizTotal = $quiz_result ? $quiz_result->num_rows : 0;

$pageTitle = $course['title'];
$cssPath = 'assets/styles.css';
$scriptPath = 'assets/js/app.js';
$brandHref = 'index.php';
$showSearch = false;
$navLinks = [
    ['href' => 'index.php', 'label' => 'Courses']
];
if (isset($_SESSION['user_id'])) {
    $navLinks[] = ['href' => '../backend/logout.php', 'label' => 'Logout', 'class' => 'btn btn-secondary'];
} else {
    $navLinks[] = ['href' => 'login.html', 'label' => 'Login', 'class' => 'btn'];
}
include 'partials/layout_start.php';
include 'partials/header.php';
?>

<section class="hero-panel hero-layout">
  <div>
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
  </div>
  <div class="hero-media">
    <img src="assets/images/hero-learning.jpg" alt="Keep learning hero image">
  </div>
</section>

<section class="stats-grid">
  <article class="stat-card">
    <div class="stat-value"><?php echo $lessonTotal; ?></div>
    <div class="stat-label">Total Lessons</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $quizTotal; ?></div>
    <div class="stat-label">Available Quizzes</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $is_enrolled ? 'Yes' : 'No'; ?></div>
    <div class="stat-label">Enrollment Status</div>
  </article>
</section>

<?php if ($lessonTotal > 0) { ?>
<section class="slider-shell">
  <div class="slider-header">
    <h3 class="section-heading">Lesson Preview Slider</h3>
    <div class="slider-nav">
      <a href="#lessonSlider">Start</a>
      <a href="#courseDetails">Details</a>
    </div>
  </div>
  <div class="slider-track" id="lessonSlider">
    <?php foreach ($lessonItems as $lessonCard) { ?>
      <article class="slider-card">
        <span class="label-pill">Lesson <?php echo $lessonCard['lesson_order']; ?></span>
        <h4><?php echo htmlspecialchars($lessonCard['title']); ?></h4>
        <p><?php echo htmlspecialchars(substr(strip_tags($lessonCard['content']), 0, 140)); ?>...</p>
        <a class="mini-btn" href="lesson.php?id=<?php echo $lessonCard['id']; ?>">Open Lesson</a>
      </article>
    <?php } ?>
  </div>
</section>
<?php } ?>

<section class="info-grid">
  <article class="panel-card">
    <h3>What you will learn</h3>
    <p>Complete structured lessons, then test understanding with quizzes linked to this course.</p>
  </article>
  <article class="panel-card">
    <h3>How to progress</h3>
    <p>Open lessons in order, mark completion, and attempt quizzes when available for this course.</p>
  </article>
</section>

<section id="courseDetails" class="course-detail-grid">
  <div class="course-summary">
    <div class="content-card">
      <h3 class="section-title">Lessons</h3>
      <?php if ($is_enrolled) { ?>
        <?php if ($lessonTotal > 0) { ?>
          <ul class="lesson-list">
            <?php foreach($lessonItems as $lesson) { ?>
              <li>
                <a href="lesson.php?id=<?php echo $lesson['id']; ?>">
                  <?php echo $lesson['lesson_order'] . '. ' . $lesson['title']; ?>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php } else { ?>
          <p class="empty-note">No lessons added yet.</p>
        <?php } ?>
      <?php } else { ?>
        <p class="locked">🔒 Please enroll to access lessons.</p>
      <?php } ?>
    </div>
  </div>

  <aside class="content-card quiz-card">
    <h3 class="section-title">Quiz</h3>
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
<?php include 'partials/layout_end.php'; ?>