<?php
session_start();
include '../../backend/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch enrolled courses
$sql = "SELECT courses.* FROM courses
        JOIN enrollments ON courses.id = enrollments.course_id
        WHERE enrollments.user_id = '$user_id'";

$result = $conn->query($sql);
$enrolledCourses = [];
if ($result) {
    while ($courseRow = $result->fetch_assoc()) {
        $enrolledCourses[] = $courseRow;
    }
}
$enrolledTotal = count($enrolledCourses);
$completedLessonsResult = $conn->query("SELECT COUNT(*) AS total FROM progress WHERE user_id='$user_id'");
$completedLessons = $completedLessonsResult ? (int) $completedLessonsResult->fetch_assoc()['total'] : 0;

$pageTitle = 'Student Dashboard';
$cssPath = '../assets/styles.css';
$scriptPath = '../assets/js/app.js';
$brandHref = '../index.php';
$showSearch = false;
$navLinks = [
    ['href' => '../index.php', 'label' => 'Home'],
    ['href' => 'dashboard.php', 'label' => 'My Courses'],
    ['href' => '../../backend/logout.php', 'label' => 'Logout', 'class' => 'btn btn-secondary']
];
include '../partials/layout_start.php';
include '../partials/header.php';
?>
<section class="hero-panel hero-layout">
  <div>
    <h2 class="page-title">Welcome, <?php echo $_SESSION['name']; ?></h2>
    <p class="subheading">Track your active courses, continue lessons, and keep progress moving from one place.</p>
    <div class="hero-actions">
      <a class="btn" href="../index.php">Explore New Courses</a>
      <a class="btn btn-secondary" href="dashboard.php">My Dashboard</a>
    </div>
  </div>
  <div class="hero-media">
    <img src="../assets/images/hero-learning.jpg" alt="Keep learning hero image">
  </div>
</section>

<section class="stats-grid">
  <article class="stat-card">
    <div class="stat-value"><?php echo $enrolledTotal; ?></div>
    <div class="stat-label">Enrolled Courses</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $completedLessons; ?></div>
    <div class="stat-label">Completed Lessons</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $enrolledTotal > 0 ? 'Active' : 'Start'; ?></div>
    <div class="stat-label">Learning Status</div>
  </article>
</section>

<?php if ($enrolledTotal > 0) { ?>
<section class="slider-shell">
  <div class="slider-header">
    <h3 class="section-heading">Continue Learning Slider</h3>
    <div class="slider-nav">
      <a href="#studentSlider">Start</a>
      <a href="#myCourses">Courses</a>
    </div>
  </div>
  <div class="slider-track" id="studentSlider">
    <?php foreach($enrolledCourses as $learningCard) { ?>
      <article class="slider-card">
        <span class="label-pill">In Progress</span>
        <h4><?php echo htmlspecialchars($learningCard['title']); ?></h4>
        <p><?php echo htmlspecialchars(substr($learningCard['description'], 0, 130)); ?>...</p>
        <a class="mini-btn" href="../course.php?id=<?php echo $learningCard['id']; ?>">Resume</a>
      </article>
    <?php } ?>
  </div>
</section>
<?php } ?>

<h3 id="myCourses" class="section-heading">My Courses</h3>

<div class="course-container">

<?php
if ($enrolledTotal > 0) {
    foreach($enrolledCourses as $row) {
?>
<a href="../course.php?id=<?php echo $row['id']; ?>">
    <div class="course-card">
      <div class="course-card-content">
        <span class="label-pill">Enrolled</span>
        <h4 class="course-card-title"><?php echo $row['title']; ?></h4>
        <p><?php echo substr($row['description'], 0, 80); ?>...</p>
      </div>
      <div class="course-card-footer">
        <span class="status-pill">Continue Learning</span>
      </div>
    </div>
</a>
<?php
    }
} else {
    echo "<p class=\"empty-note\">You have not enrolled in any courses yet.</p>";
}
?>

</div>

<?php include '../partials/layout_end.php'; ?>