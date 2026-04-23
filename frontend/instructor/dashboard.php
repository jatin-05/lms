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
$courses = [];
if ($result) {
    while ($courseRow = $result->fetch_assoc()) {
        $courses[] = $courseRow;
    }
}
$courseTotal = count($courses);
$lessonTotalResult = $conn->query("SELECT COUNT(*) AS total FROM lessons WHERE course_id IN (SELECT id FROM courses WHERE instructor_id='$instructor_id')");
$quizTotalResult = $conn->query("SELECT COUNT(*) AS total FROM quizzes WHERE course_id IN (SELECT id FROM courses WHERE instructor_id='$instructor_id')");
$lessonTotal = $lessonTotalResult ? (int) $lessonTotalResult->fetch_assoc()['total'] : 0;
$quizTotal = $quizTotalResult ? (int) $quizTotalResult->fetch_assoc()['total'] : 0;

$pageTitle = 'Instructor Dashboard';
$cssPath = '../assets/styles.css';
$scriptPath = '../assets/js/app.js';
$brandHref = '../index.php';
$showSearch = false;
$navLinks = [
    ['href' => 'dashboard.php', 'label' => 'My Courses'],
    ['href' => '../../backend/logout.php', 'label' => 'Logout', 'class' => 'btn btn-secondary']
];
include '../partials/layout_start.php';
include '../partials/header.php';
?>

<section class="hero-panel hero-layout">
  <div>
    <h2 class="page-title">Instructor Workspace</h2>
    <p class="subheading">Welcome, <?php echo $_SESSION['name']; ?>. Manage your content, organize lessons, and keep quizzes ready for learners.</p>
    <div class="hero-actions">
      <a class="btn btn-success" href="create_course.php">Create New Course</a>
      <a class="btn btn-secondary" href="../index.php">View Catalog</a>
    </div>
  </div>
  <div class="hero-media">
    <img src="../assets/images/hero-learning.jpg" alt="Keep learning hero image">
  </div>
</section>

<section class="stats-grid">
  <article class="stat-card">
    <div class="stat-value"><?php echo $courseTotal; ?></div>
    <div class="stat-label">Courses Owned</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $lessonTotal; ?></div>
    <div class="stat-label">Lessons Published</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $quizTotal; ?></div>
    <div class="stat-label">Quizzes Created</div>
  </article>
</section>

<?php if ($courseTotal > 0) { ?>
<section class="slider-shell">
  <div class="slider-header">
    <h3 class="section-heading">Course Management Slider</h3>
    <div class="slider-nav">
      <a href="#instructorSlider">Start</a>
      <a href="#instructorCourses">Courses</a>
    </div>
  </div>
  <div class="slider-track" id="instructorSlider">
    <?php foreach ($courses as $courseCard) { ?>
      <article class="slider-card">
        <span class="label-pill">Manage</span>
        <h4><?php echo htmlspecialchars($courseCard['title']); ?></h4>
        <p><?php echo htmlspecialchars(substr($courseCard['description'], 0, 130)); ?>...</p>
        <div class="feature-actions">
          <a class="mini-btn" href="edit_course.php?id=<?php echo $courseCard['id']; ?>">Edit</a>
          <a class="mini-btn" href="add_lesson.php?course_id=<?php echo $courseCard['id']; ?>">Lesson</a>
        </div>
      </article>
    <?php } ?>
  </div>
</section>
<?php } ?>

<h3 id="instructorCourses" class="section-heading">My Courses</h3>

<div class="course-container">

<?php
foreach($courses as $row) {

    $course_id = $row['id'];

    $lesson_sql = "SELECT * FROM lessons WHERE course_id='$course_id' ORDER BY lesson_order ASC";
    $lesson_result = $conn->query($lesson_sql);

    $quiz_sql = "SELECT * FROM quizzes WHERE course_id='$course_id'";
    $quiz_result = $conn->query($quiz_sql);
?>

<div class="course-card">
    <div class="course-card-content">
      <span class="label-pill">Course</span>
      <h4 class="course-card-title"><?php echo $row['title']; ?></h4>
      <p><?php echo htmlspecialchars(substr($row['description'], 0, 120)); ?>...</p>
      <div class="feature-actions">
        <a class="mini-btn" href="../course.php?id=<?php echo $course_id; ?>">View</a>
        <a class="mini-btn" href="edit_course.php?id=<?php echo $course_id; ?>">Edit</a>
        <a class="mini-btn" href="add_lesson.php?course_id=<?php echo $course_id; ?>">Add Lesson</a>
        <a class="mini-btn" href="create_quiz.php?course_id=<?php echo $course_id; ?>">Quiz</a>
      </div>
    </div>
    <div class="course-card-footer">
      <a class="btn btn-danger"
      href="../../backend/delete_course.php?id=<?php echo $course_id; ?>"
      onclick="return confirm('Delete this course? This will remove everything!')">
      Delete Course
      </a>
    </div>

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

<?php if ($courseTotal === 0) { ?>
  <p class="empty-note">No courses yet. Create your first course to start building content.</p>
<?php } ?>

<?php include '../partials/layout_end.php'; ?>