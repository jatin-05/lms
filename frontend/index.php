<?php
include '../backend/db.php';
session_start();

$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
$courses = [];
while ($courseRow = $result->fetch_assoc()) {
    $courses[] = $courseRow;
}
$totalCourses = count($courses);
$lessonCountResult = $conn->query("SELECT COUNT(*) AS total FROM lessons");
$quizCountResult = $conn->query("SELECT COUNT(*) AS total FROM quizzes");
$totalLessons = $lessonCountResult ? (int) $lessonCountResult->fetch_assoc()['total'] : 0;
$totalQuizzes = $quizCountResult ? (int) $quizCountResult->fetch_assoc()['total'] : 0;

$pageTitle = 'LMS Home';
$cssPath = 'assets/styles.css';
$scriptPath = 'assets/js/app.js';
$brandHref = 'index.php';
$showSearch = true;
$searchPlaceholder = 'Search courses...';
$searchTarget = '.course-card';
$searchFields = 'h3,p';
$navLinks = [];
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'student') {
        $navLinks[] = ['href' => 'student/dashboard.php', 'label' => 'Dashboard'];
    } elseif ($_SESSION['role'] === 'instructor') {
        $navLinks[] = ['href' => 'instructor/dashboard.php', 'label' => 'Dashboard'];
    } elseif ($_SESSION['role'] === 'admin') {
        $navLinks[] = ['href' => 'admin/dashboard.php', 'label' => 'Dashboard'];
    }
    $navLinks[] = ['href' => '../backend/logout.php', 'label' => 'Logout', 'class' => 'btn btn-secondary'];
} else {
    $navLinks[] = ['href' => 'login.html', 'label' => 'Login'];
    $navLinks[] = ['href' => 'register.html', 'label' => 'Register', 'class' => 'btn'];
}

include 'partials/layout_start.php';
include 'partials/header.php';
?>

<section class="hero-panel hero-layout">
  <div>
    <div class="label-pill">Discover</div>
    <h1>Find the right course for your next milestone</h1>
    <p class="subheading">Browse high-quality learning paths for students, instructors, and administrators with lessons, quizzes, and progress tracking.</p>
    <div class="hero-actions">
      <a class="btn" href="#courses">Browse Courses</a>
      <a class="btn btn-secondary" href="login.html">Login</a>
    </div>
  </div>
  <div class="hero-media">
    <img src="assets/images/hero-learning.jpg" alt="Keep learning hero image">
  </div>
</section>

<section class="stats-grid">
  <article class="stat-card">
    <div class="stat-value"><?php echo $totalCourses; ?></div>
    <div class="stat-label">Published Courses</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $totalLessons; ?></div>
    <div class="stat-label">Learning Lessons</div>
  </article>
  <article class="stat-card">
    <div class="stat-value"><?php echo $totalQuizzes; ?></div>
    <div class="stat-label">Practice Quizzes</div>
  </article>
</section>

<section class="feature-grid">
  <article class="feature-card">
    <h3>Fast Course Access</h3>
    <p>Jump directly into any enrolled course in one click.</p>
    <div class="feature-actions">
      <a class="mini-btn" href="#courses">Explore</a>
      <a class="mini-btn" href="login.html">Sign In</a>
    </div>
  </article>
  <article class="feature-card">
    <h3>Instructor Workspace</h3>
    <p>Create lessons and quizzes from a clean dashboard flow.</p>
    <div class="feature-actions">
      <a class="mini-btn" href="register.html">Start Teaching</a>
    </div>
  </article>
  <article class="feature-card">
    <h3>Progress Tracking</h3>
    <p>Track completion status and continue where you left off.</p>
    <div class="feature-actions">
      <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student') { ?>
        <a class="mini-btn" href="student/dashboard.php">My Progress</a>
      <?php } elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === 'instructor') { ?>
        <a class="mini-btn" href="instructor/dashboard.php">My Workspace</a>
      <?php } elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') { ?>
        <a class="mini-btn" href="admin/dashboard.php">Admin Panel</a>
      <?php } else { ?>
        <a class="mini-btn" href="login.html">Get Started</a>
      <?php } ?>
    </div>
  </article>
</section>

<?php if ($totalCourses > 0) { ?>
<section class="slider-shell">
  <div class="slider-header">
    <h3 class="section-heading">Featured Tracks</h3>
    <div class="slider-nav">
      <a href="#featuredSlider">Start</a>
      <a href="#courses">All</a>
    </div>
  </div>
  <div class="slider-track" id="featuredSlider">
    <?php foreach ($courses as $featured) { ?>
      <article class="slider-card">
        <span class="label-pill">Featured</span>
        <h4><?php echo htmlspecialchars($featured['title']); ?></h4>
        <p><?php echo htmlspecialchars(substr($featured['description'], 0, 130)); ?>...</p>
        <a class="mini-btn" href="course.php?id=<?php echo $featured['id']; ?>">Open Course</a>
      </article>
    <?php } ?>
  </div>
</section>
<?php } ?>

<div id="courses" class="course-container">

<?php
if ($totalCourses > 0) {
    foreach($courses as $row) {

        $user_id = $_SESSION['user_id'] ?? 0;
        $is_enrolled = false;

        if ($user_id) {
            $check = "SELECT * FROM enrollments 
                      WHERE user_id='$user_id' AND course_id='{$row['id']}'";

            $res = $conn->query($check);
            $is_enrolled = $res->num_rows > 0;
        }
?>

<div class="course-card">
    <a class="card-link" href="course.php?id=<?php echo $row['id']; ?>">
        <div class="course-card-content">
            <span class="label-pill">Course</span>
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo substr($row['description'], 0, 100); ?>...</p>
        </div>
    </a>

    <div class="course-card-footer">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'student') { ?>
            <?php if ($is_enrolled) { ?>
                <span class="status-pill success">✔ Enrolled</span>
            <?php } else { ?>
                <form action="../backend/enroll.php" method="POST">
                    <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                    <button class="btn" type="submit">Enroll</button>
                </form>
            <?php } ?>
        <?php } else { ?>
            <span class="status-pill">View course details</span>
        <?php } ?>
    </div>
</div>

<?php
    }
} else {
    echo "<p class=\"empty-note\">No courses found yet.</p>";
}
?>

</div>

<?php include 'partials/layout_end.php'; ?>